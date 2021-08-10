"use strict"

function new_text_button(){
    //BLOK-5    Кнопка 'новый текст', очищаем все поля
    $(".main-header-menu").on('click', '.js_clean-all', function(){
        let WORK_AREA = getWorkAreaSelector();
        
        $('.js_main-name').val("");
        $('.js_main-theme-name').val("");
        $('.js_main-theme-name').removeAttr('data');
        $('.current-text-id').html("");

        //Если уже был начат набор текста и main__textarea - <div>, меняем назад на <textarea>
        $(".js-div-main-textarea").replaceWith("<textarea class='textarea main__textarea blue-neon-box js-main-textarea js-textarea' placeholder='Добавьте ваш текст в это окно или выберите текст из списка'></textarea>");
        $(".js-main-textarea").val("");
        $('.message').html("");

        WORK_AREA.attr("disabled", "true");
        WORK_AREA.attr("placeholder", "Сначала добавьте текст в верхнее поле");
        WORK_AREA.val("");

        localStorage.clear();
        null_var();
    });
    //END BLOK-5
}
document.addEventListener("DOMContentLoaded", new_text_button);