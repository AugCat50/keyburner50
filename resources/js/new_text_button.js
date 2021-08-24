"use strict"
//Обработка кнопки 'Новый текст', очищаем все поля и локальное хранилище
function new_text_button(){
    
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

        //Очищаем локальное хранилище и все данные переменных отслежзивания статистики ввода
        localStorage.clear();
        null_var();

        //Небольшая настройка кнопки и диалога графика
        $('.js-graph-image').html('<p class="bright-blue-neon">Статистика для нового текста пуста. Сохраните текст и начинайте тренировки, чтобы заполнить статистику!</p>');
        $('.js_graph-button').removeClass('green-neon-box');
        $('.js_graph-button').addClass('pink-neon-box');
    });
    
}
document.addEventListener("DOMContentLoaded", new_text_button);