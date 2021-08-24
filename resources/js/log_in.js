"use strict"
//Аутентификация
function log_in(){

    function ajax_query_log_in(login, password, identifier){
        let name = "";
        let mail = "";
        
        //по флагу определить пользователь ввёл почту или логин
        if(identifier){
            name = login;
        }else{
            mail = login;
        }
        
        $.ajax({
            url: "ajax.php/log_in",
            method: "get",
            data:{
                id: -1,
                name: name,
                mail: mail,
                password: password
            },
            success: function(data){
                if(data){
                    //В случае не успеха, выводим фидбэк
                    $(".dialog__message").html(data);
                } else {
                    //В случае успеха - редирект
                    localStorage.clear();
                    document.location.href = 'index.php/user';
                }
            },
            error: function(data){
                alert("ajax error:"+data);
            }
        });
    }
    
    $(".js_log-in-ready").click(function(event){
        event.preventDefault();
        let login     = $(".js_log-in-name").val();
        let password = $(".js_log-in-password").val();
        
        if(!login && !password){
            $(".dialog__message").html("<p>Поля должны быть заполнены!</p>");
        }else if(!login){
            $(".dialog__message").html("<p>Заполните логин!</p>");
        }else if(!password){
            $(".dialog__message").html("<p>Заполните парль!</p>");
        }else{
            
            //Проверка, ввёл пользователь почту или логин
            if(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(login)){
                //почта
                ajax_query_log_in(login, password, false);
            }else{
                //логин
                ajax_query_log_in(login, password, true);
            }
            
        }
    });

    //Показать диалоговое окно аутентификации
    $('.js_main-header-menu').on('click', '.js_authorization__show', function() {
        
        //Если сессия есть, при нажатии кнопки "Вход", пользователя сразу перебросит на /user
        let sessId = getCookie('PHPSESSID');
        if(typeof(sessId) != "undefined" && sessId !== null && sessId !== ""){
            document.location.href="http://94.244.191.245/keyburner50/index.php/user";
        }

        $(".authorization").show();
    });

    //Скрыть диалоговое окно аутентификации
    $('.authorization').on('click', '.js_authorization__hide', function(event) {
        event.preventDefault();
        $(".authorization").hide();
    });

    //Выйти из аккаунта / уничтожить сессию
    $(".main-header-menu").on('click', '.js_desroy', function(){
        ajaxUser(null, 'DELETE', '/log_out', null, null, null, null);
    });
}
document.addEventListener("DOMContentLoaded", log_in);