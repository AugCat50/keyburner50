"use strict"
//Кнопка "Редактировать текст"
function edit_button() {
    var WORK_AREA = getWorkAreaSelector();

    //Возврат возможности редактирования текста
    $(".js-main").on('click', ".js-replaceWith", function(){
        if(BLOCK_STATUS === true){
            $(".js-div-main-textarea").replaceWith("<textarea class='textarea main__textarea blue-neon-box js-main-textarea js-textarea' placeholder='Добавьте ваш текст в это окно или выберите текст из списка'>"+CLONE_TEXT+"</textarea>");
            WORK_AREA.attr("placeholder", "Сначала добавьте текст в верхнее поле");
            autosize( $('.js-textarea') );
            BLOCK_STATUS = false;
            WORK_AREA.val("");
            
            //Обнуление переменных.
            null_var();
        } else{
            alert("Поле для ввода уже разблокировано");
        }
    });
}
document.addEventListener("DOMContentLoaded", edit_button);