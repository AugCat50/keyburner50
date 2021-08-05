"use strict"
function random_default_text() {
    //Случайный дефолтный текст
    var BUFFER = -1;
    $(".js_default-text-list, .main-header-menu").on("click", ".js_get-random-text-default", function(){
        let WORK_AREA    = getWorkAreaSelector();
        let list_length = $(".js_default-text-list").find('.js_default-text-list__name').length;

        function getRandomInt(min, max) {
          return Math.floor(Math.random() * (max - min)) + min;
        }

        //Чтобы не повторялись числа
        let number = getRandomInt(0, list_length);
        while(BUFFER === number){
            number = getRandomInt(0, list_length);
        }
        BUFFER = number;

        let find_elem = $('.js_default-text-list__name:eq('+number+')');
        let elem_id   = find_elem.attr('data-id');
        let elem_name = find_elem.attr('name');
        let elem_area = 'Default';

        $('.js_main-name').val(elem_name);
        $('.js_main-theme-name').val(elem_area);
        $('.js_main-theme-name').attr('data', elem_area);
        $('.js_current-text-id-wrapper').html('');

        //localStorage необходимо очистить, на случай если там сохранён ID пользовательского текста
        localStorage.clear();
        localStorage.setItem("name", elem_name);
        localStorage.setItem("area", elem_area);
        localStorage.setItem("area-attr", elem_area);

        ajaxQuery(elem_id, "/default_text", ".js-main-textarea");
    });
}
document.addEventListener("DOMContentLoaded", random_default_text);