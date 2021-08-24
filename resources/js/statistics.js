"use strict"
//Показать/скрыть окно статистики
function statistics(){
    $('.graph').on('click', '.js_graph-button', function(){
        $('.graph__dialog').show();
    });
    
    $('.graph').on('click', '.js-graph__close', function(){
        $('.graph__dialog').hide();
    });
}
document.addEventListener("DOMContentLoaded", statistics);