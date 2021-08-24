"use strict"
//Обработка поля поиска. Поиск нажалием стрелки и Enter
function search(){
    //Поиск нажатием стрелки
    $('.search').on('click', '.js_search-button', function(){
        let result_box  = $('.js_serch-result');
        let search_word = $('.js_search-word').val();
        let search_attr = result_box.attr('search_attr');
        
        if(search_attr === search_word){
            //Если данное ключевое слово уже запрашивалось и результаты уже есть, просто показываем
            result_box.show();
        }else if(search_word != undefined && search_word != ""){
            ajaxUser(null, 'GET', "/search", null, null, search_word, ".js_serch-result");
        }
    });
    
    //Поиск нажатием Enter
    $('.js_search-word').keypress(function(event){
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if(keycode == '13'){
            let result_box  = $('.js_serch-result');
            let search_word = $('.js_search-word').val();
            let search_attr = result_box.attr('search_attr');
            
            if(search_attr === search_word){
                //Если данное ключевое слово уже запрашивалось и результаты уже есть, просто показываем
                result_box.show();
            }else if(search_word != undefined && search_word != ""){
                ajaxUser(null, 'GET', "/search", null, null, search_word, ".js_serch-result");
            }
        }
    });
    
    //Скрыть блок результатов поиска, нажатием по крестику
    $('.js_serch-result').on('click', '.js_saerch-close', function(){
        $('.js_serch-result').hide();
        $('.js_search-word').val("");
    });
}
document.addEventListener("DOMContentLoaded", search);