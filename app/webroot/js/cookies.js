function createCookie(name,value,days) {
    if (days) {
        var date = new Date();
        date.setTime(date.getTime()+(days*24*60*60*1000));
        var expires = "; expires="+date.toGMTString();
    }
    else var expires = "";
    document.cookie = name+"="+value+expires+"; path=/";
}

function readCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}

function eraseCookie(name) {
    createCookie(name,"",-1);
}


// 'f_' for cookies related to fliters
function createCookiesFromFilter(filter){
    
    for (var key in filter) {
        if (filter.hasOwnProperty(key)) {
            createCookie("f_"+key, filter[key]);
        }
    }
    
}

function createFilterFromCookies(){
    
    var filter = {};
    
    $.each(document.cookie.split(/; */), function()  {
        var splitCookie = this.split('=');
        // name is splitCookie[0], value is splitCookie[1]
        if(splitCookie[0].substring(0,2) === "f_"){
            filter[splitCookie[0].substring(2,splitCookie[0].length)] = splitCookie[1];
        }
    });
    
    return filter;
}