"use strict"
//Работа с пользовательской темой, удалить, изменить, копировать. Обработка на нажатия кнопок в меню "Ваши темы"
function user_theme(){
    //---------------------- Копирование темы в поле js_main-theme-name кликом по кнопке ----------------------
    $(".js_users-theme").on("click", ".js_copy-theme", function(){
        //Сначала вызывать ивент клик по "Новый текст", чтобы очистить все поля
        $('.js_clean-all').trigger('click');

        //Второй вариант: Сначала вызывать ивент клик по "Редактировать", чтобы сбросить ввод, но остаить текст и id.
        // $('.js-replaceWith').trigger('click');
        
        let area = $(this).attr('theme-name');
        $('.js_main-theme-name').val(area);
        localStorage.setItem("area", area);
    });


    //---------------------- Удаление темы кликом по кнопке ----------------------
    var id   = null;
    var area = null;
    var list = null;

    //Вызов диалогового окна удаления темы и заполнение буфферных переменных
    $(".js_users-theme").on("click", ".js_delete-theme", function(){
        $('.js_dialog_delete_theme').show();

        id   = $(this).attr('theme-id');
        area = $(this).attr('theme-name');
        list = $(this).closest('.js_user-text-list');
    });
    
    //Нажатие кнопки "Отмена"
    $('.js_dialog_delete_theme').on('click', '.js_dialog_delete_theme__hide', function(event){
        $('.js_dialog_delete_theme').hide();
    });

    //Нажатие кноки "Удалить"
    $('.js_dialog_delete_theme').on('click', '.js_dialog_delete_theme__ready', function(event){
        $('.js_dialog_delete_theme').hide();

        ajaxUser(id, 'DELETE', "/del_user_theme", area, false, false, ".message");

        list.remove();
        localStorage.clear();
    });


    //---------------------- Редактировать тему ----------------------
    $(".js_users-theme").on("click", ".js_edit-theme", function(){
        $('.js_dialog_rename_theme').show();

        id   = $(this).attr('theme-id');

        list = $(this).closest('.js_user-text-list');
    });
    
    //Нажатие кнопки "Отмена"
    $('.js_dialog_rename_theme').on('click', '.js_dialog_rename_theme__hide', function(event){
        $('.js_dialog_rename_theme').hide();
    });

    //Нажатие кнопки "Сохранить"
    $('.js_dialog_rename_theme').on('click', '.js_dialog_rename_theme__ready', function(event){
        $('.js_dialog_rename_theme').hide();

        let themeName = $('.js_rename-theme').val();

        //В меню "Ваши темы" в нажатом списке, дочерний h4 в котором дочерний span, заменить имя темы на новое
        list.children('h4').children('.js_theme-name').html(themeName);

        ajaxUser(id, 'PUT', "/edit_user_theme", themeName, false, false, ".message");
        $('.js_clean-all').click();
        localStorage.clear();
        // localStorage.setItem("area", themeName);
    });
}
document.addEventListener("DOMContentLoaded", user_theme);