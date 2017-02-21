var http      = require('http');
var JsBarcode = require('jsbarcode');
var Canvas    = require("canvas");

http.createServer(function (request, response) {

    if (request.url == '/favicon.ico')return response.end('');//Intercept request favicon.ico

    var text   = 'DD201702176659251839-6238321155720220672';
    var canvas = new Canvas();
    JsBarcode(canvas, text, {width: 1, height: 50, displayValue: true});
    var dataUrl = canvas.toDataURL("image/png");
    console.log(dataUrl);
    response.end("<!DOCTYPE html/><html><head><title>node-qrcode</title></head><body><img src='" + dataUrl + "'/></body></html>");
}).listen(8124);

console.log('Server running at http://127.0.0.1:8124/');
