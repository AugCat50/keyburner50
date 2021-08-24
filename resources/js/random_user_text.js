"use strict"
//Получить и вывести случайный пользовательский текст и его статистику
function random_user_text() {
    var BUFFER_1 = -1;

    $(".main-header-menu").on("click", ".js_get-random-text", function(){
        let list_length = $(".js_users-theme").find('.js_user-text-name').length;
        
        function getRandomInt(min, max) {
          return Math.floor(Math.random() * (max - min)) + min;
        }
        
        //Чтобы не повторялись числа
        let number = getRandomInt(0, list_length);
        
        while(BUFFER_1 === number){
            number = getRandomInt(0, list_length);
        }
        BUFFER_1 = number;
        
        let find_elem = $('.js_user-text-name:eq('+number+')');
        let elem_id   = find_elem.attr('data-id');
        let elem_name = find_elem.attr('name');
        let elem_area = find_elem.attr('data-area');
        
        $('.js_main-name').val(elem_name);
        $('.js_main-theme-name').val(elem_area);
        $('.js_main-theme-name').removeAttr('data');
        $('.js_current-text-id-wrapper').html("ID:<span class='js_current-text-id'>"+elem_id+"</span>");
        
        //localStorage необходимо очистить, на случай если там сохранён ID пользовательского текста
        localStorage.clear();
        localStorage.setItem("name", elem_name);
        localStorage.setItem("area", elem_area);
        localStorage.setItem("id", elem_id);
        
        ajaxQuery(elem_id, "/user_text", ".js-main-textarea");
        ajaxQuery_stat(elem_id, 'get');
    });
}
document.addEventListener("DOMContentLoaded", random_user_text);