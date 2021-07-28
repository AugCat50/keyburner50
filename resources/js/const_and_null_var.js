var BLOCK_STATUS = false;
var CLONE_TEXT   = "";

function getWorkAreaSelector() {
    return $('.js-work-textarea');
}

//Обнуление переменных. Вообще, не факт, что так можно делать. Но и копипастом заниматься не хочется.
function null_var(){
    BLOCK_STATUS    = false;
    startStr        = 0;
    old_work        = "";
    old_work_length = 0;
    oldVal          = "";
    oldLength       = 0;
    errors          = 0;
    start_time      = 0;
    end_time        = 0;
    template_length = 0;
    wrong_length    = 0;
    errors          = 0;
}