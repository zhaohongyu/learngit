/**
 * Created by zhaohongyu on 2017/1/5.
 */

var http       = require('http');
var svgCaptcha = require('svg-captcha');

http.createServer(function (request, response) {
    if (request.url == '/captcha') {

        var captcha = svgCaptcha.create();
        console.log(captcha);
        response.writeHead(200, {
            'Content-Type': 'image/svg+xml'
        });
        response.end(captcha.data);

    } else response.end('');
}).listen(8181);

console.log('Web server started.\n http://127.0.0.1:8181/captcha');