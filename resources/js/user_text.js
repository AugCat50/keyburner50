"use strict"
function user_text(){
    //Получение пользовательского текста и текста из поиска. По аналогии с дефолтным, см выше
    //Search не ищет в дефолтных текстах. Но эту возможность можно добавить, поэтому я решил не убирать проверки.
    $(".js_users-theme, .js_serch-result").on("click", ".js_select", function(){
        let select = $(this);
        
        //Если флаг уже есть, то это клик по <option> внутри списка, а значит можно получить val селекта
        if(select.hasClass('opened')){
            localStorage.clear();
            let name  = select.val();
            let theme = select.attr('data-area');
  
            //Удаление лишнего при клике на Search
            //В option поиска данные имеюд вид textName -- themeName для сихранения уникальности имени и наглядности
            //Разделитель и имя темы необходимо удалить для дальнейшей работы с именем текста
            let index = name.indexOf(" --");
            if(index != -1){
                name = name.slice(0, index);
            }

            //Получаем элемент по составному селектору имени темы и имени текста
            let my_option = $('[data-area = "'+theme+'"][name = "'+name+'"]');
            let id        = my_option.attr("data-id");
            let area      = my_option.attr("data-area");
            
            $('.js-main-textarea').attr("placeholder", "Текст загружается, ожидайте...");
            $('.js-main-textarea').val('');
            
            ajaxQuery(id, "/user_text", ".js-main-textarea");

            ajaxQuery_stat(id, 'get');
            
            //Удалить атрибут data='Default', защищающий стандартнные тексты от изменения
            $('.js_main-theme-name').removeAttr('data');
            
            //Заполняем данными поля "Имя", "Тема", атрибут data инпута "Тема", затираем ID
            $('.js-work-textarea').removeAttr("disabled");
            $('.js-work-textarea').attr("placeholder", "Готовы приступать? :) \nШаблон блокируется на время теста.");
            $('.js_main-name').val(name);
            $('.js_main-theme-name').val(area);
            
            
            //Если в поиске дефолтный текст, добавляем атрибут маркер data='Default' инпуту "Тема" и не выводим ID
            if(area !== "Default"){
                $('.js_current-text-id-wrapper').html("ID:<span class='js_current-text-id'>"+id+"</span>");
            }else{
                $('.js_current-text-id-wrapper').html("");
                $('.js_main-theme-name').attr('data', 'Default');
            }
            
            //Если в поиске дефолтный текст, сохраняем маркер area-attr='Default' и не сохраняем ID
            if(area !== "Default"){
                localStorage.setItem("id", id);
            }else{
                localStorage.setItem("area-attr", area);
            }
            
            localStorage.setItem("name", name);
            localStorage.setItem("area", area);
        }else{
            //Если флага нет, то это первый клик по списку, раскрывающий его. Добавляем флаг к <select>
            select.addClass('opened');
        }
        
        //При потере фокуса на любом select, у него удаляется класс opened
        $('.opened').blur(function() {
            $('.opened').removeClass('opened');
        });
    });
}
document.addEventListener("DOMContentLoaded", user_text);