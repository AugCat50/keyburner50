"use strict"
function ajaxQuery (id, its_text, clss){
    var WORK_AREA = getWorkAreaSelector();
    //its_text = "get_user_text" если пользовательский или "get_default_text" если дефолтный
    $.ajax({
        url:    "ajax.php",
        method: "get",
        data: {
            id: id,
            its_text: its_text
        },
        success: function(msg){
            //                $(clss).html(msg);
            $(clss).replaceWith("<textarea class='textarea main__textarea blue-neon-box js-main-textarea js-textarea' placeholder='Добавьте ваш текст в это окно или выберите текст из списка'>"+msg+"</textarea>");
            // $('.test').html(msg);

            WORK_AREA.val("");
            let result = text_replace();
            localStorage.setItem("text", result);
            
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
    });
}