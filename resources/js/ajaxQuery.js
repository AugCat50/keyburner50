"use strict"
//Функция для получения текстов
function ajaxQuery (id, ajax_path, clss){
    var WORK_AREA = getWorkAreaSelector();
    //ajax_path = "get_user_text" если пользовательский или "get_default_text" если дефолтный
    $.ajax({
        url:    "http://176.36.150.88/keyburner50/ajax.php",
        method: "get",
        data: {
            id: id,
            ajax_path: ajax_path
        },
        success: function(msg){
            if(msg.indexOf('_error_') >= 0){
                $('.message').html(msg);
                $('.message').show();
            } else{
                $(clss).replaceWith("<textarea class='textarea main__textarea blue-neon-box js-main-textarea js-textarea' placeholder='Добавьте ваш текст в это окно иливыберите текст из списка'>"+msg+"</textarea>");
            
                WORK_AREA.val("");
                let result = text_replace();
                localStorage.setItem("text", result);
                
                //autosize.js - библиотека для создания эффекта автовысоты textarea по тексту
                autosize( $('.js-textarea') );
                //Обнуление переменных.
                null_var();
                
                let val = $(".js-main-textarea").val();
                if(val){
                    WORK_AREA.removeAttr("disabled");
                    WORK_AREA.attr("placeholder", "Готовы приступать? :) \nШаблон блокируется на время теста.");
                }else{
                    WORK_AREA.attr("disabled", "true");
                    WORK_AREA.attr("placeholder", "Сначала добавьте текст в верхнее поле");
                    WORK_AREA.val("");
                }
            }
        },
        error: function (data){
            $('.message').html(data);
            $('.message').show();
        }
    });
}