"use strict"
//Начальные значения для буффера должны быть ВНЕ функции, данные циклически обновляются
        
//Обновляются раз в слово
var old_work        = "";
var old_work_length = 0;
var startStr        = 0;

//Обновляются раз в символ
var oldVal    = "";
var oldLength = 0;

//template_length -- Длина шаблонного текста, определяется при блокировке шаблонного текста
//start_time      -- время начала, определяется при блокировке шаблонного текста
var start_time      = 0; 
var end_time        = 0;
var template_length = 0;
var wrong_length    = 0;
var errors          = 0;

function work_textarea(){
    var WORK_AREA = getWorkAreaSelector();

    //BLOK-2   Второй блок (work textarea)
    //Начальные значения для буффера должны быть ВНЕ функции, данные циклически обновляются
        
    //Обновляются раз в слово
    // let old_work        = "";
    // let old_work_length = 0;
    // let startStr        = 0;
    
    //Обновляются раз в символ
    // let oldVal    = "";
    // let oldLength = 0;
    
    //template_length -- Длина шаблонного текста, определяется при блокировке шаблонного текста
    //start_time      -- время начала, определяется при блокировке шаблонного текста
    // let start_time      = 0; 
    // let end_time        = 0;
    // let template_length = 0;
    // let wrong_length    = 0;
    // let errors          = 0;
    
    let change_work_textarea = document.querySelector('.js-work-textarea');

    if(! change_work_textarea){
        return null;
    }

    change_work_textarea.oninput = function(e){
        let word        = [];
        let work_text   = WORK_AREA.val();
        let work_length = work_text.length;
        let my_time, result_speed, penalty_speed, my_minute, my_second;
        
        //Блокировка текста work main_area, если это ещё не сделано
        //Прямое обращение к $(".js-main-textarea"), поскольку элемент может быть удалён и создан скриптом
        if(BLOCK_STATUS === false){
            CLONE_TEXT = $(".js-main-textarea").val();
            $(".js-main-textarea").replaceWith("<div class='textarea main__textarea blue-neon-box js-main-textarea js-div-main-textarea'><pre><div class='main-inner'></div><span class='js-main-span'>"+CLONE_TEXT+"</span></pre></div>");
            BLOCK_STATUS = true;
            
            //Длина шаблона и время старта для вычисления статистики
            template_length = CLONE_TEXT.length;
            start_time = new Date;
        }
        
        //Удаление первого пробела, когда рабочее поле ещё пустое
        if(work_text===" "){
            WORK_AREA.val("");
            return;
        }
        
        //Запрет ctrl+v
        if(work_length - oldLength > 1){
            WORK_AREA.val(oldVal);
            return;
        }
        
        //Запрет повторных " "
        if(work_text[work_length-2]===" " && work_text[work_length-1]===" "){
            WORK_AREA.val(oldVal);
            return;
        }
        
        //Пошагово перемещаемся по строке, меняя startStr на конец предыдущего слова
        if((work_length != old_work_length) && (work_text[work_length-1]===" " || work_text[work_length-1]==="\n")){
            //Работа с набираемым текстом
            word["start"] = startStr;
            word["end"]   = work_length - 1;
            word["word"]  = work_text.slice(word["start"], word["end"]);
            startStr      = work_length;
            
            if(work_text[work_length-1]==="\n"){
                word["word"] = word["word"]+"\n";
            }
            
            
            //Работа с шаблонным текстом
            let tempText    = "";
            let tempWord    = [];
            let tempResidue = "";
            
            tempText = $(".js-main-span").html();             
            tempText = tempText.replace(/\n+/g, "\n ");
            
            //Разбиваем строку на массив
            tempWord = tempText.split(/[ \f\r\t\v\u00A0\u1680\u180e\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u2028\u2029\u2028\u2029\u202f\u205f\u3000]/);
            
            //Остаток текста - длина прошлого слова + 1 пробел, длина всего текста tempText
            tempResidue = tempText.slice(tempWord[0].length+1);
            tempResidue = tempResidue.replace(/\n\s+/g, "\n");
            $(".js-main-span").html(tempResidue);
            
            //Удаление символа показывающего перенос пользователю
            tempWord[0] = tempWord[0].replace(/\u21B5/g, "");
            
            let z;
            let temp_word_l = tempWord[0].length - 1;
//            console.log(tempWord[0][temp_word_l]);
            
            if(word["word"] === tempWord[0]){
                
                //Добавить пробел если нет \n
                if(tempWord[0][temp_word_l] != "\n"){
                    z = tempWord[0]+" ";
                }else{
                    z = tempWord[0];
                }
                
                $(".main-inner").append(z);
            }else{
                wrong_length = wrong_length + tempWord[0].length;
                errors++;
                
                //Добавить пробел если нет \n
                if(tempWord[0][temp_word_l] != "\n"){
                    z = "<span class='blue'>"+tempWord[0]+"</span> ";
                }else{
                    z = "<span class='blue'>"+tempWord[0]+"</span>";
                    
                    //При ошибке в слове с переносом строки, каретка в рабочей зоне переносится на следующую строку
                    let add_enter_in_work = $(".js-work-textarea").val();
                    $(".js-work-textarea").val(add_enter_in_work+"\n");
                }
                
                $(".main-inner").append(z);
            }
            
            //Блок вычисления статистики когда текст закончился
            if(tempResidue===""){
                end_time = new Date;
                
                //Время в минутах
                my_time   = (end_time - start_time)/(1000*60);
                my_minute = Math.floor(my_time);
                my_second = Math.round( ((end_time - start_time)/1000) - my_minute*60 );
                if(my_minute < 10){
                   my_minute = "0"+my_minute;
                }
                if(my_second < 10){
                   my_second = "0"+my_second;
                }
                
                //Скорость набора в минуту
                result_speed  = (template_length - wrong_length)/my_time;
                penalty_speed = result_speed - template_length/my_time;
                result_speed  = result_speed.toFixed(3);
                
                $(".js-minute").html(("0"+my_minute).slice(-2));
                $(".js-second").html(my_second);
                $(".js-speed").html(result_speed);
                $(".js-errors").html(errors);
                $(".js-penalty").html(penalty_speed.toFixed(3));
                
                
                //Отправка-получение статистики
                let stat_id = $('.js_current-text-id').html();
                
                function ajax_statistics(id, time, speed){
                    let $month_number = time.getMonth() + 1;
                    let stat_time     = time.getDate() +"."+ $month_number +"."+ time.getFullYear();
                    let ajax_path     = '/statistics';
                    
                    $.ajax({
                        url:    "http://94.244.191.245/keyburner50/ajax.php",
                        method: "post",
                        data:{
                            id:        id,
                            ajax_path: ajax_path,
                            time:      stat_time,
                            speed:     speed
                        },
                        success: function (data){
                            $('.test').html(data);
                            $('.message').html(data);
                            $('.message').show();
                        },
                        error: function (data){
                            $('.message').html(data);
                            $('.message').show();
                        }
                    });
                }
                
                // if(stat_id && stat_id){
                if(stat_id != undefined && stat_id != ""){
                    let qwe = ajax_statistics(stat_id, end_time, result_speed);
                }
                //                console.log(my_time);
                //                console.log(my_minute+":"+my_second);
                //                console.log(errors);
                //                console.log(result_speed.toFixed(3));
                //                console.log(penalty_speed.toFixed(3));

                //Работа со статистикой, пока отключить
                // ajaxQuery_stat(id, 'user');
            }
            
            old_work        = WORK_AREA.val();
            old_work_length = old_work.length;
        }
        
        //Запрет удаления (Backspace) после каждого слова
        if(old_work_length!==0 && (work_length < old_work_length)){
            WORK_AREA.val(old_work);
            work_length++;
        }
           
        oldLength = work_length;
        oldVal    = work_text;
    }
//END BLOK-2 
}
document.addEventListener("DOMContentLoaded", work_textarea);