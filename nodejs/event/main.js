/**
 * Created by zhaohongyu on 16/7/11.
 */

// 引入events模块
var events = require('events');

// 实例化 eventEmitter 对象
var eventEmitter = new events.EventEmitter();

var connectHandler = function () {
    console.log('连接成功');
    // 发射数据
    eventEmitter.emit('dataReceived');
};

// 绑定事件
eventEmitter.on('connection', connectHandler);

// 使用匿名函数绑定事件处理程序
eventEmitter.on('dataReceived', function () {
    console.log('数据接收成功');
});

// 发射连接
eventEmitter.emit('connection');

console.log('程序执行完毕');