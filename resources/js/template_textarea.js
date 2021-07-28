"use strict"
function template_textarea(){
    var WORK_AREA = getWorkAreaSelector();

    //BLOK-1 В основном работа с первым блоком (Template textarea)
        //Обработка текста при начальной загрузке, если он есть
        let load_val = $(".js-main-textarea").val();
        WORK_AREA.val("");

        // if(load_val && load_val ){ более элегантно. Надо затестить
        if(load_val != false && load_val !== null){
            text_replace();
            WORK_AREA.removeAttr("disabled");
            WORK_AREA.attr("placeholder", "Готовы приступать? :) \nШаблон блокируется на время теста.");
        }

        //Template textarea содержит текст - разблокируется рабочее поле work textarea. Пуст - блокируется
        $('.js-main').on('input' , '.js-main-textarea', function(){
            let val  = $(".js-main-textarea").val();
            let id   = $(".js_current-text-id").html();
            let name = $(".js_main-name").val();
            let area = $(".js_main-theme-name").val();
            localStorage.setItem("id", id);
            localStorage.setItem("name", name);
            localStorage.setItem("area", area);
            localStorage.setItem("text", val);
            
            if(val){
                WORK_AREA.removeAttr("disabled");
                WORK_AREA.attr("placeholder", "Готовы приступать? :) \nШаблон блокируется на время теста.");
            }else{
                WORK_AREA.attr("disabled", "true");
                WORK_AREA.attr("placeholder", "Сначала добавьте текст в верхнее поле");
                WORK_AREA.val("");
            }
        });
        
        //Вызов функции удаления пробельных символов
        $(".js_section-template").on("focusout", ".js-main-textarea", function(){
            let r_t = text_replace();
            localStorage.setItem("text", r_t);
        });
    //END BLOK-1
}
document.addEventListener("DOMContentLoaded", template_textarea);