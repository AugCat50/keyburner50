<?php
/**
 * Класс заниматеся работой с строками статистики. Получение, сохранение. Вызывается в основном из StatisticsWorker
 * 
 * Пример того, что получается - {3,11.8.2021-444.444,11.8.2021-19.487,3}
 * Разделение участков статистики по пользователям {userId,time-speed,time-speed,time-speed,userId}{nextUser,...
 * 
 * Возможно стоило вынести статистику в отдельную таблицу, отдельную модель, но уже не буду менять. Оставлю как было в первом проекте.
 */
namespace app\Workers;

use app\Requests\Request;
use DomainObjectAssembler\DomainModel\UserTextModel;
use DomainObjectAssembler\DomainObjectAssembler;
use DomainObjectAssembler\IdentityMap\ObjectWatcher;

class StatisticsDataWorker
{
    private $assembler;
    private $userId        = null;
    private $textId        = null;
    private $time          = null;
    private $speed         = 0;
    private $userTextModel = null;

    /**
     * Получение данных Request, проверка наличия сессии и id текста
     * 
     * @param  app\Requests\Request $request
     * 
     * @return null
     */
    public function __construct(Request $request)
    {
        if (! isset($_SESSION["auth_subsystem"]["user_id"])) throw new \Exception('>>>>> StatisticsDataWorker(35): ID пользователя отсутствует в сессии <<<<<');
        
        $this->userId = $_SESSION["auth_subsystem"]["user_id"];
        $this->textId = $request->getProperty('id');
        $this->time   = $request->getProperty('time');
        $this->speed  = $request->getProperty('speed');

        $this->assembler = new DomainObjectAssembler('UserText');

        if (! isset($this->textId) ) return 'StatisticsDataWorker(44): отсутствует id текста';
    }

    /**
     * Получение строки общей статистики текста из БД.
     * 
     * return DomainObjectAssembler\DomainModel\UserTextModel
     */
    public function getGeneralStatistics()
    {
        //Если модель получена ранее, возвращаем без лишних обращений в БД
        if ( $this->userTextModel instanceof UserTextModel ) return $this->userTextModel;

        $identityObj = $this->assembler->getIdentityObject();
        $identityObj->addEnforrceFields(['statistics', 'statistics_best']);
        $identityObj->field('id')->eq($this->textId);

        $this->userTextModel = $this->assembler->findOne($identityObj);

        //Если NullModel, то по какой-то причене не удалось прочитать данные текста из БД, что происходить не должно.
        if (! $this->userTextModel instanceof UserTextModel ) {
            throw new \Exception('>>>>> StatisticsDataWorker(65): Что-то пошло не так и не удалось получить модель текста, получено: '. get_class($this->userTextModel). ' <<<<<');
        }
        
        return $this->userTextModel;
    }

    /**
     * Получение строки статистики текущего пользователя
     * 
     * @return string
     */
    public function getUserStatistics(): string
    {
        //Получение общей статистики ($userTextModel)
        $userTextModel = $this->getGeneralStatistics();
        $statistics    = $userTextModel->getStatistics();

        //Данных для записи нет
        //этот код возвращает данные статистики пользователю
        if(!$statistics){
                
            //Строка статистики в модели пустая, Данных для записи нет
            return "Статистика пуста";
        }else{
            //Строка статистики в модели есть, Данных для записи нет
            $open_str         = '{' . $this->userId;
            $start_position   = strripos($statistics, $open_str) + strlen($open_str) + 1;
            
            $end_str          = ',' . $this->userId . '}';
            $end_position     = strripos($statistics, $end_str);
            
            $length_stat_str  = $end_position - $start_position;
            $user_stat_string = substr($statistics, $start_position, $length_stat_str);
            
            return $user_stat_string;
        }
    }

    /**
     * Генерация строки статистики и сохранение её в БД.
     * 
     * @return bool
     */
    public function addNewStatistics()
    {
        if(! isset($this->time, $this->speed) ) throw new \Exception('StatisticsDataWorker(53): попытка вызывать addNewStatistics без параметров для записи в Request');

        //Получение общей статистики ($userTextModel)
        $userTextModel = $this->getGeneralStatistics();
        $statistics    = $userTextModel->getStatistics();

        //Данные для записи есть
        if(! $statistics){

            //Строка статистики в модели пустая, данные на запись есть

            //По типу: {3,11.8.2021-468.750,3}
            $it   = '{' . $this->userId . ',' . $this->time . '-' . $this->speed . ',' . $this->userId . '}';

        }else{

            //Строка статистики в модели есть, данные на запись есть

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

            }else{

                //Статистика для этого пользователя есть, работаем с ней
                $length_stat_str  = $end_position - $start_position;
                $user_stat_string = substr($statistics, $start_position, $length_stat_str) . ',' . $this->time . '-' . $this->speed;
                $it               = $first_string . $user_stat_string . $last_string;

            }
        }

        $userTextModel->setStatistics($it);
    }

    /**
     * Метод для получения лучшего результата
     * 
     * Сравнивает новые данные с рекордом из БД, 
     * при необходимости обновляет или не обновляет рекорд и возвращает число рекордной скорости вызывающему коду
     * В случае, если входные данные null, вернёт данные из БД
     * 
     * @return float
     */
    public function bestStatistics()
    {
        //Получение общей строки лучших результатов всех пользователей
        $userTextModel   = $this->getGeneralStatistics();
        $statistics_best = $userTextModel->getStatisticsBest();

        
        if(!$statistics_best && !isset($this->time, $this->speed)){

            //Если свойство "statistics_best" модели текста пустое и нет в реквесте времени и скорости, значит нет статистики - возвращаем 0
            return 0;

        } else if(!$statistics_best && isset($this->time, $this->speed)){

            //Если свойство "statistics_best" модели текста пустое, но в реквесте есть время и скорость, значит записываем новую строку статистики
            $it = '{' . $this->userId . ',' . $this->time . '-' . $this->speed . ',' . $this->userId . '}';
            
            $userTextModel->setStatisticsBest($it);
            
            return $this->speed;

        }else{

            //Если свойство "statistics_best" модели текста есть, в реквесте есть время и скорость
            $open_str_best       = '{' . $this->userId;
            $start_position_best = strripos($statistics_best, $open_str_best);
            
            $end_str_best        = ',' . $this->userId . '}';
            $end_position_best   = strripos($statistics_best, $end_str_best);
            
            //проверка наличия строки статистики пользователя по наличию позиции конца строки ',userId}'
            if(!$end_position_best){

                //Если пользовательской cтроки нет ({id,data,id})и есть данные для записи, просто записываем в конец общей строки
                $it = $statistics_best . '{' . $this->userId . ',' . $this->time . '-' . $this->speed . ',' . $this->userId . '}';

                $userTextModel->setStatisticsBest($it);
                
                return $this->speed;

            }else{

                //Есть пользовательская строка и есть данные для записи

                //Длина строки пользователя и сама строка
                $length_stat_str_best  = $end_position_best - $start_position_best;
                $user_stat_string_best = substr($statistics_best, $start_position_best, $length_stat_str_best);
                $arr_best              = explode('-', $user_stat_string_best);
                
                //Если новый результат больше того, что в базе, просто обновляем его
                if($arr_best[1] < $this->speed){
                    $len_stat_best     = strlen($statistics_best);
                    $first_string_best = substr($statistics_best, 0, $start_position_best);
                    $last_string_best  = substr($statistics_best, $end_position_best, $len_stat_best);
                    $it                = $first_string_best . '{' . $this->userId . ',' . $this->time . '-' . $this->speed . $last_string_best;

                    $userTextModel->setStatisticsBest($it);

                    return $this->speed;
                }

                //Если результат из БД не меньше нового, возвращаем его
                return $arr_best[1];

            } 
        }
    }

    /**
     * Обновление строки статистики в базе
     * 
     * Сначала передать в set методы модели данные! Например $userTextModel->setStatistics($it);
     * Ничего особенного, просто получается объект ObjectWatcher и запускается выполнение очереди работы с БД
     */
    public function doUpdate()
    {
        $message    = null;
        $objWatcher = ObjectWatcher::getInstance();
        $message    = $objWatcher->performOperations();

        return $message;
    }
}
