<?php
namespace app\Workers;

use app\Requests\Request;

class StatisticsWorker
{
    public function getStatistics(Request $request)
    {
        $dataWorker = new StatisticsDataWorker($request);

        $statistics_best = $dataWorker->bestStatistics();
        $statistics      = $dataWorker->getUserStatistics();

        $data = $this->createImage($statistics, $statistics_best);
        return $data;
    }

    public function main(Request $request)
    {
        $id    = $request->getProperty('id');
        $time  = $request->getProperty('time');
        $speed = $request->getProperty('speed');

        $dataWorker = new StatisticsDataWorker($request);

        if(isset($time, $speed)){
            //запись
            $result          = $dataWorker->addNewStatistics();
            $statistics_best = $dataWorker->bestStatistics();
            $statistics      = $dataWorker->getUserStatistics();
            //чтение
            // $statistics      = statistics($pdo, $_POST['id'], false, false);
            // $statistics_best = statistics_best($pdo, $_POST['id'], false, false);
        }else{
            //чтение
            // $statistics      = statistics($pdo, $_POST['id'], false, false);
            // $statistics_best = statistics_best($pdo, $_POST['id'], false, false);
            $statistics_best = $dataWorker->bestStatistics();
            $statistics      = $dataWorker->getUserStatistics();
        }
        
        $data = $this->createImage($statistics, $statistics_best);
        return $data;
    }

    public function createImage($statistics, $statistics_best)
    {
        if(! function_exists('gd_info')) throw new \Exception('Включите графическую библиотеку GD на вашем php (php.ini добавить строку extension=php_gd.dll)');
        
        $stat_first_expl = explode(',', $statistics);
        
        $count = count($stat_first_expl);
        //Код генерации изображения, выполняется только если в массиве статистики больше одного значения
        if($count>1){
            for($i=0; $i<$count; $i++){
                $my_data[$i] = explode('-', $stat_first_expl[$i]);
                //Все элементы массива с скоростью 0 пропускаем
                if($my_data[$i][1] != 0){
                    $x_data[]           = $my_data[$i][0];
                    $y_data[]           = $my_data[$i][1];
                    $for_sort_y_data[]  = $my_data[$i][1];
                }
            }
        
            //Занчения Y, сначала сортируем по возрастанию, затем удаляем повторяющиеся значения
            //Масиссив $y_data_sorted начинается с индекса 1
            sort($for_sort_y_data);
            $count = count($for_sort_y_data);
            for($i=0, $j=0, $k=0; $i<$count; $i++){
                if($j<$for_sort_y_data[$i]){
                    $k++;
                    $y_data_sorted[$k] = $for_sort_y_data[$i];
                    $j                 = $for_sort_y_data[$i];
                }else{
                    $y_data_sorted[$k] = $for_sort_y_data[$i];
                }
            }
            
            
            // Задаем изменяемые значения #######################################
            
            // Размер изображения
            $W=1000;
            $H=600;
            if(count($y_data)<10){
                $W=500;
                $H=300;
            }
        
            // Псевдо-глубина графика
            $DX=10;
            $DY=8;
        
            // Отступы
            $MB=70; // Нижний
            $ML=55; // Левый 
            $M=5;   // Верхний и правый отступы. Они меньше, так как там нет текста
        
            //Крайние точки изображения. (0,0) - верхний левый угол
            // A0----B0
            //  |    |
            // D0----C0
            $A0['x'] = 0;
            $A0['y'] = 0;
        
            $B0['x'] = $W;
            $B0['y'] = 0;
        
            $C0['x'] = $W;
            $C0['y'] = $H;
        
            $D0['x'] = 0;
            $D0['y'] = $H;
        
            //Крайние точки графика
            // A0---------B0
            // |DX-B----C  |
            // |  /|    |  |
            // | A |    |  |
            // | | G----D  |
            // | |/|DY /   |
            // | F----E    |
            // D0---------C0
            $A['x'] = $A0['x'] + $ML;
            $A['y'] = $A0['y'] + $DY + $M;
        
            $B['x'] = $A0['x'] + $ML + $DX;
            $B['y'] = $A0['y'] + $M;
        
            $C['x'] = $B0['x'] - $M;
            $C['y'] = $B0['y'] + $M;
        
            $D['x'] = $B0['x'] - $M;
            $D['y'] = $H - ($MB + $DY);
        
            $E['x'] = $C0['x'] - ($M + $DX);
            $E['y'] = $D0['y'] - $MB;
        
            $F['x'] = $D0['x'] + $ML;
            $F['y'] = $D0['y'] - $MB;
        
            $G['x'] = $A0['x'] + $ML + $DX;
            $G['y'] = $H - ($MB + $DY);
        
            //Доступная для графика ширина и высота
        //    $GW = $E['x'] - $F['x'];
        //    $GH = $F['y'] - $A['y'];

            // Ширина одного символа
            $LW=imageFontWidth(2);
        
            // Количество подписей и горизонтальных линий сетки по оси Y.
            $county = count($y_data_sorted);
            $val_interval = $y_data_sorted[$county] - $y_data_sorted[1];
        
            //Длина доступной оси Y в пикселях минус отступ сверху 10 пикселей
            $AF_length = $F['y'] - $A['y'] - 15;
            //Елиниц на пиксель
            $AF_pixel  = $val_interval/$AF_length;
        
            //Длина доступной оси X в пикселях
            $FE_length = $E['x'] - $F['x'];
            //Интервал вывода пунктов
            $FEi = $FE_length/($count+0.5);
        
        
        
            // Работа с изображением ############################################
        
            // Создадим изображение
            $im=imagecreate($W,$H);
        
            // Задаем основные цвета 
            // Цвет фона (белый)
            $bg[0]=imageColorAllocate($im,255,255,255);
        
            // Цвет задней грани графика (светло-серый)
            $bg[1]=imageColorAllocate($im,231,231,231);
        
            // Цвет левой грани графика (серый)
            $bg[2]=imageColorAllocate($im,212,212,212);
        
            // Цвет сетки (серый, темнее)
            $c=imageColorAllocate($im,184,184,184);
        
            // Цвет текста (темно-серый)
            $text=imageColorAllocate($im,136,136,136);
        
            // Цвета для столбиков
            $bar[0][0]=imageColorAllocate($im,222,214,0);
            $bar[0][1]=imageColorAllocate($im,181,187,65);
            $bar[0][2]=imageColorAllocate($im,161,155,0);
        
            //задняя грань графика
            imageRectangle($im, $B['x'], $B['y'], $D['x'], $D['y'], $c);
            imageFill($im, $B['x']+1, $B['y']+1, $bg[1]);
            //Левая грань графика
            $points = array($A['x'],$A['y'],$B['x'],$B['y'],$G['x'],$G['y'],$F['x'],$F['y']);
            imageFilledPolygon($im, $points, 4, $bg[2]);
            imagePolygon($im, $points, 4, $c);
            //Нижняя грань графика
            $points = array($G['x'],$G['y'],$D['x'],$D['y'],$E['x'],$E['y'],$F['x'],$F['y']);
            imagePolygon($im, $points, 4, $c);
        
        
            //Горизонтальные линии, чёрточки, подписи
            for($i=1; $i<=$county; $i++){
                if($i==1){
                    //первая горизонтальная линия, "ноль"
                    imageLine($im, $F['x'], $F['y']-10, $F['x']-5, $F['y']-10, $c);
                    imageLine($im, $F['x'], $F['y']-10, $G['x'],   $G['y']-10, $c);
                    imageLine($im, $G['x'], $G['y']-10, $D['x'],   $D['y']-10, $c);
                    imageString($im, 2, $F['x']-47, $F['y']-16, $y_data_sorted[1], $text);
                }else{
                    //Все горизонтальн выше нуля
                    $AF_next = (($y_data_sorted[$i] - $y_data_sorted[1])/$AF_pixel) + 10;
                    imageLine($im, $F['x'], $F['y']-$AF_next, $F['x']-5, $F['y']-$AF_next, $c);
                    imageLine($im, $F['x'], $F['y']-$AF_next, $G['x'],   $G['y']-$AF_next, $c);
                    imageLine($im, $G['x'], $G['y']-$AF_next, $D['x'],   $D['y']-$AF_next, $c);
                    imageString($im, 2, $F['x']-47, $F['y']-$AF_next-8, $y_data_sorted[$i], $text);
                }
            }
        
            //Вертикальные линии, чёрточки и подписи
            for($i=1; $i<=$count; $i++){
                imageLine($im, $F['x']+$i*$FEi-$FEi*0.25, $F['y'], $F['x']+$i*$FEi-$FEi*0.25, $F['y']+5, $c);
                imageLine($im, $F['x']+$i*$FEi-$FEi*0.25, $F['y'], $G['x']+$i*$FEi-$FEi*0.25, $G['y'], $c);
                imageLine($im, $G['x']+$i*$FEi-$FEi*0.25, $G['y'], $B['x']+$i*$FEi-$FEi*0.25, $B['y'], $c);
                imageStringup($im, 2, $F['x']+$i*$FEi-$FEi*0.25-6, $F['y']+62, $x_data[$i-1], $text);
            }
        
            //Сами столбики
            //Сдвиг на четверть влево по оси х
            $j = $FEi*0.25;
            //сдвиг на половину вправо или влево от черты с подписью по оси Х
            $k = $FEi*0.5;
            //Отступ справа или слава от столбика по оси Х
            $t = 3;
            for($i=1; $i<=$count; $i++){
        
                if($y_data[$i-1] == $y_data_sorted[1]){
                    //Для минимального значения скорости, куб высотой до 10
                    imageRectangle($im, $F['x']+$i*$FEi-$k-$j+$t, $F['y'], $F['x']+$i*$FEi+$k-$j-$t, $F['y']-9, $bar[0][1]);
                    imagefilltoborder($im,  $F['x']+$i*$FEi-$k-$j+$t+1, $F['y']-1, $bar[0][1], $bar[0][1]);
        
                    //Верхняя грань
                    $points = array(
                        $F['x']+$i*$FEi-$k-$j+$t     , $F['y']-9,
                        $F['x']+$i*$FEi-$k-$j+$t+$DX , $F['y']-9-$DY,
                        $F['x']+$i*$FEi+$k-$j-$t+$DX , $F['y']-9-$DY,
                        $F['x']+$i*$FEi+$k-$j-$t     , $F['y']-9
                    );
                    imageFilledPolygon($im, $points, 4, $bar[0][0]);
        
                    //Правая грань
                    $points = array(
                        $F['x']+$i*$FEi+$k-$j-$t     , $F['y'],
                        $F['x']+$i*$FEi+$k-$j-$t     , $F['y']-9,
                        $F['x']+$i*$FEi+$k-$j-$t+$DX , $F['y']-9-$DY,
                        $F['x']+$i*$FEi+$k-$j-$t+$DX , $F['y']-$DY
                    );
                    imageFilledPolygon($im, $points, 4, $bar[0][2]);
                }else{
                    $AF_next = ($y_data[$i-1] - $y_data_sorted[1])/$AF_pixel;
                    //Фронтальный прямоугольник и заливка
                    imageRectangle($im, $F['x']+$i*$FEi-$k-$j+$t, $F['y'], $F['x']+$i*$FEi+$k-$j-$t, $F['y']-$AF_next-9, $bar[0][1]);
                    imagefilltoborder($im,  $F['x']+$i*$FEi-$k-$j+$t+1, $F['y']-1, $bar[0][1], $bar[0][1]);
        
                    //Верхняя грань
                    $points = array(
                        $F['x']+$i*$FEi-$k-$j+$t     , $F['y']-$AF_next-9,
                        $F['x']+$i*$FEi-$k-$j+$t+$DX , $F['y']-$AF_next-9-$DY,
                        $F['x']+$i*$FEi+$k-$j-$t+$DX , $F['y']-$AF_next-9-$DY,
                        $F['x']+$i*$FEi+$k-$j-$t     , $F['y']-$AF_next-9
                    );
                    imageFilledPolygon($im, $points, 4, $bar[0][0]);
        
                    //Правая грань
                    $points = array(
                        $F['x']+$i*$FEi+$k-$j-$t     , $F['y'],
                        $F['x']+$i*$FEi+$k-$j-$t     , $F['y']-$AF_next-9,
                        $F['x']+$i*$FEi+$k-$j-$t+$DX , $F['y']-$AF_next-9-$DY,
                        $F['x']+$i*$FEi+$k-$j-$t+$DX , $F['y']-$DY
                    );
                    imageFilledPolygon($im, $points, 4, $bar[0][2]);
                }
            }
            //запускаем буферизацию выходного потока
            ob_start();
        
            header("Content-Type: image/png");
        
            // Генерация изображения
            ImagePNG($im);
        
            $contents = ob_get_contents();
            ob_end_clean();
        
            $base64 = base64_encode($contents);
            $data = "<img src='data:image/png;base64,".$base64."' />";
            $data = $statistics_best. '---'. $data. '---1';
        
            //echo $data;
        
            imagedestroy($im);
        }else{
        //    $data = $statistics_best.'---'.'<p class="blue-neon">Статистики ещё нет</p>';
            $data = $statistics_best.'---'.'<p class="bright-blue-neon">Статистика ещё пуста! Тренируйтесь, чтобы её заполнить!</p>'. '---0';
        }
        //echo $data;
        return $data;
    }
}
