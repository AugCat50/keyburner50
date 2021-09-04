<?php
/**
 * Делегирующий класс для работы со статистикой, группирующий операции
 */
namespace app\Workers;

use app\Requests\Request;

class StatisticsWorker
{
    private $imageBuilder = null;

    public function __construct()
    {
        $this->imageBuilder = new ImageBuilderWorker();
    }

    /**
     * Получить и вернуть данные статистики
     * 
     * Получить лучший результат 
     * Получить общую статистику
     * Сгенерировать изображение (Упаковка данных в строку происходит в createImage)
     * 
     * @param  app\Requests\Request
     * @return string
     */
    public function getStatistics(Request $request)
    {
        $dataWorker = new StatisticsDataWorker($request);

        $statistics_best = $dataWorker->bestStatistics();
        $statistics      = $dataWorker->getUserStatistics();

        $data = $this->createImage($statistics, $statistics_best);
        return $data;
    }

    /**
     * Обновить и вернуть данные статистики.
     * 
     * В случае наличия $time, $speed, данные сначала будут обновлены
     * и только затем подготовлены на возврат
     * 
     * @return string
     */
    public function main(Request $request)
    {
        // $id    = $request->getProperty('id');
        $time  = $request->getProperty('time');
        $speed = $request->getProperty('speed');

        $dataWorker = new StatisticsDataWorker($request);

        //Если есть время и скорость, записываем в БД новую статистику
        if(isset($time, $speed)){
            //Добавить новую статистику
            $result          = $dataWorker->addNewStatistics();

            //Обновить и получить лучшую статистику
            $statistics_best = $dataWorker->bestStatistics();

            //Запустить запись
            $message         = $dataWorker->doUpdate();

            //чтение уже обновлённой статистики
            $statistics      = $dataWorker->getUserStatistics();
        }else{
            //чтение
            $statistics_best = $dataWorker->bestStatistics();
            $statistics      = $dataWorker->getUserStatistics();
        }
        
        $data = $this->createImage($statistics, $statistics_best);

        //В $message может прийти сообщение из ObjectWather, например с ошибкй. Можно придумать вывод или отлов
        return $data;
    }

    /**
     * Получить изображение из данных статистики.
     * 
     * @see ImageBuilderWorker::createImage()
     * 
     * @return string
     */
    public function createImage($statistics, $statistics_best)
    {
        $data   = $this->imageBuilder->createImage($statistics, $statistics_best);

        return $data;
    }
}
