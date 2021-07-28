"use strict"
function default_ready(){
    $(".js_ul_list.js_default-text-list").on('click', '.js_default-text-list__name', function(){
        localStorage.clear();
        let id = $(this).attr('data-id');
        
        ajaxQuery(id, "get_default_text", ".js-main-textarea");
    });
}
document.addEventListener("DOMContentLoaded", default_ready);