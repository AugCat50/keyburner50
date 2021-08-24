//Добавление (add), удаление (del), редактирование текста (edit). Для добавления id = false
//Обслуживание поисковых запросов и выхода из аккаунта
function ajaxUser(id, method, operation, name, theme, text, clss){
    $.ajax({
        url:    "http://94.244.191.245/keyburner50/ajax.php",
        method: 'post',
        data: {
            id:        id,
            method:    method,
            ajax_path: operation,
            name:      name,
            theme:     theme,
            text:      text
        },
        success: function (data){
                
            if(!(data.indexOf('_error_') >= 0)){
                // $('.test').html(data);

                if(operation === "/log_out") {

                    //Выход из аккаунта, уничтожить сессию
                    deleteCookie('PHPSESSID');
                    document.location.href = 'http://94.244.191.245/keyburner50/index.php';

                }else if(operation === "/del_user_theme" || operation === "/edit_user_theme"){

                    //Удалить пользовательскую тему
                    $(clss).html(data);
                    $(clss).show();

                } else if(operation === '/search'){
                    
                    //Поиск по пользовательским текстам
                    $(clss).html(data).show();
                    $(clss).attr('search_attr', text);
                        
                    //Количество текстов найдено
                    //Теперь в SearchTextListView.php
                    // $(clss).each(function () {
                    //     let q = $(this).find('.select__option').length;
                    //     $(this).children(".user-text-list__head").append(" ["+q+"]");
                    // });

                } else if(operation){

                    //Сохранение нового текста или обновление уже существующего
                    
                    //В ответ приходит строка ответа и html отрисовки нового меню. (id, <span>Текст ответа</span>, html нового меню) 
                    //Разделяем ответ и код и отрисовываем в их местах
                    let index, answer, html;
                    if(operation === '/add_user_text'){
                        //При добавлении текста надо вернуть id из модели
                        let in_id, id;
                        in_id  = data.indexOf("<span>");
                        index  = data.indexOf("</span>") + 7;
                        id     = data.slice(0, in_id);
                        answer = data.slice(in_id, index);
                        html   = data.slice(index);
                        
                        //Если id нет, не отображаем блок. Это может произойти в случае, когда текст с таким именем уже есть
                        if(id != '') {
                            $('.js_current-text-id-wrapper').html("ID:<span class='js_current-text-id'>"+id+"</span>");
                        }                      
                        localStorage.setItem("id", id);
                    }else{
                        //При изменении текста id уже есть
                        index  = data.indexOf("</span>") + 7;
                        answer = data.slice(0, index);
                        html   = data.slice(index);
                    }
                    
                    localStorage.setItem("name", name);
                    localStorage.setItem("area", theme);
                    localStorage.setItem("text", text);
                    
                    $(clss).html(answer).show();
                    $('.users-theme').html(html);
                        
                    //Количество текстов в категории (теперь в view)
                    // $(".user-text-list").each(function () {
                    //     let q = $(this).find('.select__option').length;
                    //     $(this).children(".user-text-list__head").append(" ["+q+"]");
                    // });

                }
                    
                //Скрывать все окна ответов с задержкой, кроме результатов поиска
                if(clss !== '.js_serch-result'){
                    setTimeout(function(){
                            $(clss).hide();
                    }, 5000); 
                }
   
            }else{
                //В случае наличия строки '_error_', за которой следут сообщение об ошибке
                $('.message').html(data);
                $('.message').show();
            }
        },
        error: function (data){
            //Вывод ajax ошибки
            $(clss).html(data).show();
        }
    });
}