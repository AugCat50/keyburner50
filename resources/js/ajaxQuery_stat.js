"use strict"
function ajaxQuery_stat (id, method, time = 0, speed = 0){
    let stat_time = null
    if(time){
        let $month_number = time.getMonth() + 1;
            stat_time     = time.getDate() +"."+ $month_number +"."+ time.getFullYear();
    }

    let ajax_path     = '/statistics';
    
    $.ajax({
        url:    "http://94.244.191.245/keyburner50/ajax.php",
        method: method,
        data:{
            id:        id,
            ajax_path: ajax_path,
            time:      stat_time,
            speed:     speed
        },
        success: function (data){
            let arr = data.split('---');
            
            $('.js_stat-best').html(arr[0]);
            $('.js-graph-image').html(arr[1]);

            if(arr[2] == 1){
                $('.js_graph-button').removeClass('pink-neon-box');
                $('.js_graph-button').addClass('green-neon-box');
            } else{
                $('.js_graph-button').removeClass('green-neon-box');
                $('.js_graph-button').addClass('pink-neon-box');
            }
        },
        error: function (data){
            $('.message').html(data);
            $('.message').show();
        }
    });
}