<?php
namespace app\Workers;

use app\Requests\Request;
use DomainObjectAssembler\DomainModel\NullModel;
use DomainObjectAssembler\DomainModel\UserTextModel;
use DomainObjectAssembler\DomainObjectAssembler;
use DomainObjectAssembler\IdentityMap\ObjectWatcher;

class StatisticsDataWorker
{
    private $userId = null;
    private $textId = null;
    private $time   = null;
    private $speed  = null;
    private $assembler;

    /**
     * Получение данных Request, проверка наличия сессии и id текста
     */
    public function __construct(Request $request)
    {
        if (! isset($_SESSION["auth_subsystem"]["user_id"])) throw new \Exception('StatisticsDataWorker(18): ID пользователя отсутствует в сессии');
        
        $this->userId = $_SESSION["auth_subsystem"]["user_id"];
        $this->textId = $request->getProperty('id');
        $this->time   = $request->getProperty('time');
        $this->speed  = $request->getProperty('speed');

        $this->assembler = new DomainObjectAssembler('UserText');

        if (! isset($this->textId) ) return 'StatisticsDataWorker(25): отсутствует id текста';
    }

    /**
     * Получение строк статистики текста из БД.
     * 
     * Возможно стоило вынести в отдельную таблицу, отдельную модель, но уже не буду менять
     * 
     * return DomainObjectAssembler\DomainModel\UserTextModel $userTextModel
     */
    public function getOldStatisticsData()
    {
        $identityObj = $this->assembler->getIdentityObject();
        $identityObj->setEnforrceFields(['id', 'statistics', 'statistics_best']);
        $identityObj->field('id')->eq($this->textId);

        $userTextModel = $this->assembler->findOne($identityObj);

        if($userTextModel instanceof NullModel) throw new \Exception('StatisticsDataWorker(44): Что-то пошло не так и не удалось получить модель текста.');

        return $userTextModel;
    }

    public function getNewStatisticsData()
    {

    }

    public function addNewStatistics()
    {
        if( isset($time, $speed) ) throw new \Exception('StatisticsDataWorker(53): попытка вызывать addNewStatistics без параметров для записи в Request');

        //Получение общей статистики ($userTextModel)
        $userTextModel = $this->getOldStatisticsData();
        $statistics    = $userTextModel->getStatistics();

        //Данные для записи есть
        //этот код возвращает true или текст ошибки ? можно убрать. Ошибки отслеживаются при записис в DOA
        if(! $statistics){

            //Строка пустая, Данные для записи есть
            //По типу: {3,11.8.2021-468.750,3}
            $it   = '{' . $this->userId . ',' . $this->time . '-' . $this->speed . ',' . $this->userId . '}';
            $data = $this->doUpdate($userTextModel, $it);
        }else{
            //Строка есть, Данные для записи есть

            //Позиция начала строки
            $open_str       = '{' . $this->userId;
            $start_position = strripos($statistics, $open_str);
            
            //Позиция конца строки
            $end_str        = ',' . $this->userId . '}';
            $end_position   = strripos($statistics, $end_str);
            
            $len_stat       = strlen($statistics);
            $first_string   = substr($statistics, 0, $start_position);
            $last_string    = substr($statistics, $end_position, $len_stat);
            
            if(!$end_position){

                //статистика есть, но не для этого пользователя. Просто добавляем в конец данные для записи
                $it   = $statistics . '{' . $this->userId . ',' . $this->time . '-' . $this->speed . ',' . $this->userId . '}';
                $data = $this->doUpdate($userTextModel, $it);
                return $data;
            }else{

                //Статистика для этого пользователя есть, работаем с ней
                $length_stat_str  = $end_position - $start_position;
                $user_stat_string = substr($statistics, $start_position, $length_stat_str) . ',' . $this->time . '-' . $this->speed;
                $it               = $first_string . $user_stat_string . $last_string;
                $data             = $this->doUpdate($userTextModel, $it);
                return $data;
            }
        }
    }

    //Обновление строки статистики в базе
    private function doUpdate(UserTextModel $userTextModel, string $it){
        $userTextModel->setStatistics($it);
        $objWatcher = ObjectWatcher::getInstance();
        $objWatcher->performOperations();

        return true;
    }




















    
    //Получение строк статистики
    // public function stat_get($pdo, $id, $column){
            
    //     try{
    //         $query = "SELECT $column FROM texts WHERE id = :id";
    //         $stmt  = $pdo->prepare($query);
    //         $stmt->bindParam(':id', $id);
    //         $stmt->execute();
            
    //         $statistics = $stmt->fetch();
    //         if(is_array($statistics)){
    //             $data = $statistics[0];
    //         }else{
    //             $data = false;
    //         }
            
    //     }catch(PDOException $e){
    //         $data = "Ошибка в model -- statistics при попытке получить статистику из $column -- texts:" . $e->getMessage() . "<br>";
    //     }
        
    //     return $data;
    // }
    
    //Статистика общая
    public function statistics($pdo, $id, $time, $speed){
        
        
        //Получение общей статистики
        $statistics = stat_get($pdo, $id, 'statistics');
        
        if($id && $time && $speed){
            
            //Данные для записи есть
            //этот код возвращает true или текст ошибки
            if(!$statistics){
                
                //Строка пустая, Данные для записи есть
                $it   = '{' . $this->userId . ',' . $time . '-' . $speed . ',' . $this->userId . '}';
                $data = $this->doUpdate($pdo, $id, $it, 'statistics');;
                return $data;
            }else{
                //Строка есть, Данные для записи есть
                
                //Позиция начала строки
                $open_str       = '{' . $this->userId;
                $start_position = strripos($statistics, $open_str);
                
                //Позиция конца строки
                $end_str        = ',' . $this->userId . '}';
                $end_position   = strripos($statistics, $end_str);
                
                $len_stat       = strlen($statistics);
                $first_string   = substr($statistics, 0, $start_position);
                $last_string    = substr($statistics, $end_position, $len_stat);
                
                if(!$end_position){
                    
                    //статистика есть, но не для этого пользователя. Просто добавляем в конец данные для записи
                    $it   = $statistics . '{' . $this->userId . ',' . $time . '-' . $speed . ',' . $this->userId . '}';
                    $data = $this->doUpdate($pdo, $id, $it, 'statistics');
                    return $data;
                }else{
                    
                    //Статистика для этого пользователя есть, работаем с ней
                    $length_stat_str  = $end_position - $start_position;
                    $user_stat_string = substr($statistics, $start_position, $length_stat_str) . ',' . $time . '-' . $speed;
                    $it               = $first_string . $user_stat_string . $last_string;
                    $data             = $this->doUpdate($pdo, $id, $it, 'statistics');
                    return $data;
                }
            }
        }else{
            
            //Данных для записи нет
            //этот код возвращает данные статистики пользователю
            if(!$statistics){
                
                //Строка пустая, Данных для записи нет
                return "Статистика пуста";
            }else{
                //Строка есть, Данных для записи нет
                
                $open_str        = '{' . $this->userId;
                $start_position = strripos($statistics, $open_str) + strlen($open_str) + 1;
                
                $end_str        = ',' . $this->userId . '}';
                $end_position   = strripos($statistics, $end_str);
                
                $length_stat_str  = $end_position - $start_position;
                $user_stat_string = substr($statistics, $start_position, $length_stat_str);
                
//                $arr_of_stat_val = explode(',', $user_stat_string);
                //здесь надо разбирать массив на данные и возвращать
//                print_r($user_stat_string);
                return $user_stat_string;
            }
        }
    }


    
    
    //Работа с лучшим результатом
    public function statistics_best($pdo, $id, $time, $speed){
        $this->userId = $_SESSION['id'];
        //Получение строки статистики лучших результатов
        $statistics_best = stat_get($pdo, $id, 'statistics_best');
        
        if($id && $time && $speed){
            //Данные для записи есть
            
            if(!$statistics_best){
                //Строки нет, Данные есть
                
                $it   = '{' . $this->userId . ',' . $time . '-' . $speed . ',' . $this->userId . '}';
                $data = $this->doUpdate($pdo, $id, $it, 'statistics_best');
                
                //Если пришла строка - это текст ошибки, если true - просто возвращаем текущую сткорость
                if(is_string($data)){
                    return $data;
                }else{
                    return $speed;
                }
            }else{
                //Строка есть, Данные есть
                
                $open_str_best         = '{' . $this->userId;
                $start_position_best   = strripos($statistics_best, $open_str_best);
                
                $end_str_best      = ',' . $this->userId . '}';
                $end_position_best = strripos($statistics_best, $end_str_best);
                
                if(!$end_position_best){
                    //Если пользовательской cтроки нет и есть данные для записи, просто записываем в конец общей строки
                    
                    $it = $statistics_best . '{' . $this->userId . ',' . $time . '-' . $speed . ',' . $this->userId . '}';
                    $data = $this->doUpdate($pdo, $id, $it, 'statistics_best');
                    
                    //Если пришла строка - это текст ошибки, если true - просто возвращаем текущую сткорость
                    if(is_string($data)){
                        return $data;
                    }else{
                        return $speed;
                    }
                }else{
                    //Есть пользовательская строка и есть данные для записи
                    
                    //Длина строки пользователя и сама строка
                    $length_stat_str_best  = $end_position_best - $start_position_best;
                    $user_stat_string_best = substr($statistics_best, $start_position_best, $length_stat_str_best);
                    $arr_best              = explode('-', $user_stat_string_best);
                    
                    //Если новый результат больше того, что в базе, просто обновляем его
                    if($arr_best[1] < $speed){
                        $len_stat_best     = strlen($statistics_best);
                        $first_string_best = substr($statistics_best, 0, $start_position_best);
                        $last_string_best  = substr($statistics_best, $end_position_best, $len_stat_best);
                        $it                = $first_string_best . '{' . $this->userId . ',' . $time . '-' . $speed . $last_string_best;
                        $data              = $this->doUpdate($pdo, $id, $it, 'statistics_best');
                        //Если пришла строка - это текст ошибки, если true - просто возвращаем текущую сткорость
                        if(is_string($data)){
                            return $data;
                        }else{
                            return $speed;
                        }
                    }
                } 
            }
        }else{
            //Данных для записи нет
            //Данный код возврщает значение лучшего результата
            
            $end_str_best          = ',' . $this->userId . '}';
            $end_position_best     = strripos($statistics_best, $end_str_best);
            
            if(!$end_position_best){
                //Нет строки, нет данных для записи
                
                return "Статистика пуста.";
            }else{
                //Есть строка, нет данных для записи
                
                $open_str_best       = '{' . $this->userId;
                $start_position_best = strripos($statistics_best, $open_str_best);
                
                //Длина строки пользователя и сама строка
                $length_stat_str_best  = $end_position_best - $start_position_best;
                $user_stat_string_best = substr($statistics_best, $start_position_best, $length_stat_str_best);
                $arr_best              = explode('-', $user_stat_string_best);
                return  $arr_best[1];
            }
        }
    }
}
