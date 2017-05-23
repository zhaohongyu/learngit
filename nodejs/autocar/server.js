var express   = require('express');
var path      = require('path');
var http      = require('http');
var app       = express();
var adminPort = 9006;// 后台端口

app.use(express.static(path.join(__dirname, 'dist')));

app.get('/hello', function (req, res, next) {
    return res.send('Hello World');
});

var server = http.createServer(app);
server.listen(adminPort);

console.log('autocar now running  at http://localhost:%d pid', adminPort, process.pid, new Date());

module.exports = app;
