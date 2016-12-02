/**
 * Created by zhaohongyu on 2016/12/2.
 */


var server = require('./server');
var router = require("./router");

server.startServer(router.route);