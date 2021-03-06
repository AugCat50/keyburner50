"use strict"
//При перезагрузке проверить данные localStorage и вставить их на страницу, если имеются
function page_reload(){
    let WORK_AREA    = getWorkAreaSelector();

    //Выяснить тип запроса к странице (обновление, навигация)
    let perf_entries = performance.getEntriesByType("navigation");
    let nav_type     = perf_entries[0].type;

    //При перезагрузке страницы вставляем старые данные
    if (nav_type === 'reload') {
        let r_id    = localStorage.getItem("id");
        let r_name  = localStorage.getItem("name");
        let r_area  = localStorage.getItem("area");
        let r_ar_at = localStorage.getItem("area-attr");
        let r_text  = localStorage.getItem("text");
        
        //Если текст template не пуст, вставить textarea
        if(typeof(r_text) !== "undefined" && r_text !== null && r_text !== ""){
            $('.js_main-name').val(r_name);
            $('.js_main-theme-name').val(r_area);
            
            if(r_id && typeof(r_id) !== 'undefined' && r_id !== 'undefined'){
                $('.current-text-id').html("ID:<span class='js_current-text-id'>"+r_id+"</span>");
            }
            
            if(r_ar_at && typeof(r_ar_at) !== 'undefined'){
                $('.js_main-theme-name').attr('data', r_ar_at);
            }
            
            $(".js-main-textarea").replaceWith("<textarea class='textarea main__textarea blue-neon-box js-main-textarea js-textarea' placeholder='Добавьте ваш текст в это окно или выберите текст из списка'>"+r_text+"</textarea>");
        }

        //Если название текста не пустое, вставить его в input
        if(r_name !== null && r_name !== ''){
            $('.js_main-name').val(r_name);
        }

        //Если название темы не пустое, вставить его в input
        if(r_area !== null && r_area !== ''){
            $('.js_main-theme-name').val(r_area);
        }
        
        if(r_text != false && r_text !== null){
            WORK_AREA.removeAttr("disabled");
            WORK_AREA.attr("placeholder", "Готовы приступать? :) \nШаблон блокируется на время теста.");
        }else{
            WORK_AREA.attr("disabled", "true");
            WORK_AREA.attr("placeholder", "Сначала добавьте текст в верхнее поле");
            WORK_AREA.val("");
        }
    }
}
document.addEventListener("DOMContentLoaded", page_reload);