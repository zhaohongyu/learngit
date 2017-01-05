/**
 * Created by zhaohongyu on 2017/1/5.
 */

var express = require('express');
var app     = express();
app.use('/static', express.static('public'));
app.get('/', function (req, res) {
    res.send('Hello World!');
});

app.listen(3001, function () {
    console.log('Server running at http://127.0.0.1:3001/');
});