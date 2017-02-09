var http   = require('http');
var QRCode = require('qrcode');

http.createServer(function (request, response) {

    if (request.url == '/favicon.ico')return response.end('');//Intercept request favicon.ico

    var text = 'Hello World';

    // 图片base64字符串
    // QRCode.toDataURL(text, function (err, url) {
    //     if (err) console.log('error: ' + err);
    //     console.log(url);
    //     response.end("<!DOCTYPE html/><html><head><title>node-qrcode</title></head><body><img src='" + url + "'/></body></html>");
    // });

    // SVG output!
    QRCode.drawSvg(text, function (err, svgString) {
        if (err) console.log('error: ' + err);
        console.log(svgString);
        response.end("<!DOCTYPE html/><html><head><title>node-qrcode</title></head><body> " + svgString + "</body></html>");
    });

    // Returns mime image/png data url for the 2d barcode.
    // QRCode.save(__dirname + '/qrcodeimg/barcode.png', text, function (err, written) {
    //     if (err) console.log('error: ' + err);
    //     console.log(written);
    //     response.end("保存完毕");
    // });

    // QRCode.drawText(text, [optional options],cb);
    // QRCode.drawText(text, function (err, result) {
    //     if (err) console.log('error: ' + err);
    //     console.log(result);
    //     response.end(result);
    // });

    // Returns an ascii representation of the qrcode using unicode characters and ansi control codes for background control.
    // QRCode.drawBitArray(text, function (err, bits, width) {
    //     if (err) console.log('error: ' + err);
    //     console.log(typeof bits);
    //     console.log(bits.toString());
    //     response.end('生成二进制');
    // });

}).listen(8124);

console.log('Server running at http://127.0.0.1:8124/');
