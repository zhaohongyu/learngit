'use strict';

// 引入hello模块
var greet = require('./hello');

var s = 'Michael';

greet(s);// Hello,Michael

//阻塞
//var fs = require("fs");
//
//var data = fs.readFileSync('input.txt');
//
//console.log(data.toString());
//console.log("Program Ended");

//非阻塞
var fs = require("fs");

fs.readFile('input.txt', function (err, data) {
    if (err) return console.error(err);
    console.log(data.toString());
});

console.log("Program Ended");