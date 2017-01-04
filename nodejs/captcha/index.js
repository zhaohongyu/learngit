/**
 * Created by zhaohongyu on 2017/1/4.
 */

var http = require('http');

var ccap = require('ccap')({
    width   : 100,// set width,default is 256
    height  : 40,// set height,default is 60
    offset  : 23,// set text spacing,default is 40
    fontsize: 35,// set font size,default is 57
    generate: function () {
        return generateVerificationCode(4);
    }

});//Instantiated ccap class

http.createServer(function (request, response) {

    if (request.url == '/favicon.ico')return response.end('');//Intercept request favicon.ico

    var ary = ccap.get();

    var txt = ary[0];

    var buf = ary[1];

    response.end(buf);

    console.log(txt);

}).listen(8124);

function generateVerificationCode(len) {
    len        = len || 6;
    var chars  = '123456789abcdefghjklmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ';
    var maxPos = chars.length;
    var str    = '';
    for (var i = 0; i < len; i++) {
        str += chars.charAt(Math.floor(Math.random() * maxPos));
    }
    return str;
}

console.log('Server running at http://127.0.0.1:8124/');
