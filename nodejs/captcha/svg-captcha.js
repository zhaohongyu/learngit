/**
 * Created by zhaohongyu on 2017/1/5.
 */

var http       = require('http');
var svgCaptcha = require('svg-captcha');
var fs         = require('fs');

http.createServer(function (request, response) {
    if (request.url == '/captcha') {

        var captcha = svgCaptcha.create();
        console.log(captcha);
        response.writeHead(200, {
            'Content-Type': 'image/svg+xml',
            'token'       : Math.random()
        });
        response.end(captcha.data);

    } else if (request.url == '/sav-captcha.html') {
        var html = fs.readFileSync("./sav-captcha.html", "utf-8");
        response.end(html);
    } else {
        response.end('Hello World');
    }

}).listen(8181);

console.log('Web server started.\n http://127.0.0.1:8181/captcha');