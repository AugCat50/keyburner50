"use strict"
function user(){
   
    //--------------------- Добавить или изменить текст ---------------------
    $(".js-main").on("click", ".js_add-text", function(){
        let id    = $('.js_current-text-id').html();
        let name  = $(".js_main-name").val();
        let theme = $(".js_main-theme-name").val();
        let text  = $(".js-main-textarea").val();
        
        function q(){
            setTimeout(function(){
                $(".message").hide();
            }, 10000);
        }
        
        if($(".js_main-theme-name").attr('data') === "Default"){
            $(".message").html("Нельзя добавлять, менять, удалять стандартные тексты.<br> Тема 'Default' зарезервирована.").show();
            q();
            return;
        }else if(name == false){
            $(".message").html("Заполните имя!").show();
            q();
            return;
        }else if(theme == false){
            $(".message").html("Заполните тему!").show();
            q();
            return;
        }else if(theme === 'Default'){
            $(".message").html("Тема 'Default' зарезервирована!").show();
            q();
            return;
        }else if(text == false){
            $(".message").html("Заполните текст!").show();
            q();
            return;
        }
        
        let end_text = text_replace();
        if(id != undefined){
            //Есть id - это редактирование, сохранить изменения
            ajaxUser(id, 'PUT', "/edit_user_text", name, theme, end_text, ".message");
        }else{
            //Нет id - сохранить новый текст
            ajaxUser(null, 'POST', "/add_user_text", name, theme, end_text, ".message");
        }
    });
    
    //--------------------- Удалить текст ---------------------
    //Показать диалоговое окно "удалить текст"
    $(".js-main").on("click", ".js-del", function(){
        $('.dialog_delete').show();
    });
    
    //Скрыть диалоговое окно "удалить текст"
    $('.body').on('click', '.js_dialog_delete__hide', function(event){
        $('.dialog_delete').hide();
    })
    
    //Запустить удаление текста
    $('.body').on('click', '.js_dialog_delete__ready', function(event){
        let id         = $('.js_current-text-id').html();
        let theme      = $(".js_main-theme-name");
        //В случае, если текст дефолтный, но пользователь изменил val в input темы, берём значение для проверки из атрибута
        let theme_attr = theme.attr('data');
        
        if(id){
            ajaxUser(id, 'DELETE', "/del_user_text", null, null, null, ".message");

            //Очистить поля и localStorage
            $('.js_main-name').val("");
            theme.val("");
            $('.current-text-id').html("");
            $(".js-main-textarea").val("");
            localStorage.clear();
        }else if(!id && theme_attr === "Default"){
            $(".js_dialog_delete-message").html("Нельзя удалять стандартные тексты!").show();
        }else{
            $(".js_dialog_delete-message").html("Не удалось получить ID текста. Обратитесь к администратору").show();
        }
        $('.dialog_delete').hide();
    })
    
    

    //При вводе названия текста, сохранять его в localStorage
    $('.js_main-name').on('input', function() {
        let name = $('.js_main-name').val();
        localStorage.setItem("name", name);
    });

    //При вооде названия темы, сохранять его в localStorage
    $('.js_main-theme-name').on('input', function() {
       let area = $('.js_main-theme-name').val();
       localStorage.setItem("area", area);
       let r_area  = localStorage.getItem("area");
    });
}
document.addEventListener("DOMContentLoaded", user);