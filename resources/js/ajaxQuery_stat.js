"use strict"
function ajaxQuery_stat (id, access){
    $.ajax({
        url: "ajax_statistics.php",
        method: "post",
        data: {
            id: id
        },
        success: function(data){
            let max, image;
            let index = data.indexOf("---");
            if(index){
                max   = data.slice(0, index);
                image = data.slice(index+3);
                $(".js_stat-best").html(max);
                
                //График есть - показываем кнопку, графика нет - скрываем кнопку
                if(image != '0'){
                    $(".graph__inner").html(image).show();
                    $(".graph").show();
                }else{
                    $(".graph").hide();
                }
            }
        },
        error: function(data){
                $(".message").html(data).show();
        }
    });
}