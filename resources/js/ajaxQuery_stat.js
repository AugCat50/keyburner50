"use strict"
//Функция для запроса графика статистики и лучшего результата
// Возможно  стоит сделать проверку метода.
function ajaxQuery_stat (id, method, time = 0, speed = 0){
    let stat_time = null
    if(time){
        let $month_number = time.getMonth() + 1;
            stat_time     = time.getDate() +"."+ $month_number +"."+ time.getFullYear();
    }
    
    $.ajax({
        url:    "http://94.244.191.245/keyburner50/ajax.php",
        method: method,
        data:{
            id:        id,
            ajax_path: '/statistics',
            time:      stat_time,
            speed:     speed
        },
        success: function (data){
            // $('.test').html(data);

            //Прихоят данные такой структуры: speed---img---1
            if(!(data.indexOf('_error_') >= 0)){
                let arr = data.split('---');
                
                //speed - число лучшей скорости
                $('.js_stat-best').html(arr[0]);

                //img картинка график
                $('.js-graph-image').html(arr[1]);
    
                if(arr[2] == 1){
                    //Если третий сегмент равен 1, меняем цвет иконки графика на зелёный
                    $('.js_graph-button').removeClass('pink-neon-box');
                    $('.js_graph-button').addClass('green-neon-box');
                } else{
                    //Если третий сегмент не равен 1, меняем цвет иконки графика на красный
                    $('.js_graph-button').removeClass('green-neon-box');
                    $('.js_graph-button').addClass('pink-neon-box');
                }
            }
        },
        error: function (data){
            $('.message').html(data);
            $('.message').show();
        }
    });
}