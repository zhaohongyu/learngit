/**
 * Created by zhaohongyu on 2016/12/2.
 */


var http = require('http');
var url = require('url');

function startServer(route) {

    function onRequest(request, response) {
        var pathname = url.parse(request.url).pathname;
        console.log("Request for " + pathname + " received.");

        route(pathname);

        response.writeHead(200, {"Content-Type": "text/plain"});
        response.write("Hello World");
        response.end();
    }

    var port = 8080;
    http.createServer(onRequest).listen(port);
    console.log('服务器已启动...访问地址:http://127.0.0.1:' + port);

}

module.exports.startServer = startServer;