"use strict"
//Универсальные функции для работы с cookie

//Получить куки по имени
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

//Удалить куки по имени
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
//     console.log('сессия есть');
//     console.log(sessId);
// }else{
//     console.log('сессии нет');
//     console.log(sessId);
// }

// setcookie($name, $value, [
//     'expires' => time() + 86400,
//     'path' => '/',
//     'domain' => 'domain.com',
//     'secure' => true,
//     'httponly' => true,
//     'samesite' => 'None',
// ]);