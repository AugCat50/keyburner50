"use strict"
//Удаление начальных, конечных пробелов, излишних пробельных символов в тексте
function text_replace(){
    let v = $(".js-main-textarea").val();

    if(!v){
        return null;
    }

    let val = v.trim();
        
    let qwe = val.replace(/\n+\s+/g, "\n");
    qwe = qwe.replace(/[ \f\r\t\v\u00A0\u1680\u180e\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u2028\u2029\u2028\u2029\u202f\u205f\u3000][ \f\r\t\v\u00A0\u1680\u180e\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u2028\u2029\u2028\u2029\u202f\u205f\u3000]+/g, " ");
    qwe = qwe.replace(/[ \n\f\r\t\v\u00A0\u1680\u180e\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u2028\u2029\u2028\u2029\u202f\u205f\u3000][ \n]+/g, "\n");
    qwe = qwe.replace(/[\n\u21B5]+/g, "\u21B5\n");
        
    if(qwe!==val){
        $(".js-main-textarea").val(qwe);
    }else{
        $(".js-main-textarea").val(val);
    }
        
    return qwe;
}