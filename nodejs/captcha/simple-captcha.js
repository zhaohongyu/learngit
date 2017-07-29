/**
 * Created by zhaohongyu on 2017/7/29.
 */

'use strict';
const http    = require('http');
const fs      = require('fs');
const captcha = require('trek-captcha');

http.createServer(async function (request, response) {

    if (request.url == '/captcha') {
        const {token, buffer} = await captcha({ size: 5, style: -1 });
        console.log('token ',token);
        console.log('buffer ', buffer);

        response.writeHead(200, { 'Content-Type': 'image/gif'});

        response.end(buffer);

    } else response.end('');
}).listen(8181);

console.log('Web server started.\n http://127.0.0.1:8181/captcha');


