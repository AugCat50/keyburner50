"use strict"
function default_ready(){

    //Получение дефолтного текста, для списка ul на главной
    $(".js_ul_list.js_default-text-list").on('click', '.js_default-text-list__name', function(){
        localStorage.clear();
        let id = $(this).attr('data-id');
        
        ajaxQuery(id, "/default_text", ".js-main-textarea");
    });


    //Получение дефолтного текста
    /*
        Как обойти отсутствие ивента клик по <select><option></option></select> в браузерах на движке хрома. 
        Как это работает в нормальных браузерах - есть просто ивент клик и ты можешь получить любые атрибуты элемента option по которому ты кликнул. Доступ ко всем данным одним кликом, удобненько.
        
        Как это работает в хром подобных браузерах. Ивента клик по option нет, и соответственно получить доступ к нему напрямую ты никак не можешь. Но можно обойти это невъебаться хитрым образом. Есть ивент клик по самому <select>. Это значит, можно получить доступ к данным самого селекта. У выпадающего списка есть такой параметр val() - значение выбранного элемента, а именно - это текст внутри <option></option>. За это можно зацепиться, если этот же текст добавить в какой-то артибут самого тэга. Селектором атрибута, в который подставляешь текст из val() можно получить доступ к самому option, по которому кликнули, и извлечь уже из него данные. Но это ещё не всё, ведь когда ты открываешь список - это один клик, а когда кликаешь по пункту списка - это уже второй клик. А надо обрабатывать только второй. Поэтому по первому клику, надо добавлять к select флаг, например класс 'open' и получать val() только когда флаг есть. После выполнения кода флаг удаляется. И в итоге, вся эта схема работает как простой ивент клик в нормальных браузерах.
        
        change, :selected использовать нельзя, поскольку не будет обрабатываться клик по уже выбранному пункту списка.
    */
        $(".default-select").click(function(){
            let my_this = $(this);
            
            //Если флаг уже есть, то это клик по <option> внутри списка, а згачит можно получить val селекта
            if(my_this.hasClass('opened')){
                localStorage.clear();
                let name      = my_this.val();
                //Получаем элемент по селектору типа $("[attr = 'val']")
                let my_option = $('[name = "'+name+'"]');
                let id        = my_option.attr("data-id");
                let area      = "Default";
                
                ajaxQuery(id, "/default_text", ".js-main-textarea");
                //Пока дефолтные тексты без статистики
                //ajaxQuery_stat(id, 'user');
                
                //Заполняем данными поля "Имя", "Тема", атрибут data инпута "Тема", затираем ID
                $('.js_main-name').val(name);
                $('.js_main-theme-name').val(area);
                $('.js_main-theme-name').attr('data', area);
                $('.js_current-text-id-wrapper').html('');
                
                $('.js-work-textarea').removeAttr("disabled");
                $('.js-work-textarea').attr("placeholder", "Готовы приступать? :) \nШаблон блокируется на время теста.");
                
                //localStorage необходимо очистить, на случай если там сохранён ID пользовательского текста
                localStorage.setItem("name", name);
                localStorage.setItem("area", area);
                localStorage.setItem("area-attr", area);
                //Код выполнен, удаляем флаг
                my_option.removeClass('opened');
            }else{
                //Если флага нет, то это первый клик по списку, раскрывающий его. Добавляем флаг к <select>
                my_this.addClass('opened');
            }
        });
        
        
        //При клике по документу вне раскрытого <select> удаляем класс opened у выпадающих списков
        //Это обязательная часть кода для 'получения пользовательского текста по клику' и 'получения дефолтного текста по клику'
        $(document).click(function(e){
            let select = $('.select');
            
            if(!select.is(e.target)){
                select.removeClass('opened');
            }
        });
}
document.addEventListener("DOMContentLoaded", default_ready);