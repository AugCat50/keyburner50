"use strict"
function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
    }
    return "";
}

function deleteCookie(name) {
var domain = location.hostname,
    path = '/'; // root path

document.cookie = [
    name, '=',
    '; expires=' + new Date(0).toUTCString(),
    '; path=' + path,
    '; domain=' + domain
].join('');
}

// deleteCookie('Clickme');

// deleteCookie('PHPSESSID');


// let sessId = getCookie('PHPSESSID');
// if(typeof(sessId) != "undefined" && sessId !== null ){
//     // document.location.href = 'http://94.244.191.245/keyburner50/index.php/user';
//     console.log('сессия есть');
//     console.log(sessId);
// }else{
//     console.log('сессии нет');
//     console.log(sessId);
// }