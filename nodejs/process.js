/**
 * Created by zhaohongyu on 2016/12/2.
 */

function doSomething(args, callback) {
    somethingComplicated(args);
    process.nextTick(callback);
}

doSomething(
    3, function onEnd() {
        compute();
    }
);


function compute() {
    for (var i = 0; i < 5; i++) {
        console.log('compute...' + i);
    }
}

function somethingComplicated(args) {
    args = args || 10;
    for (var i = 0; i < args; i++) {
        console.log('somethingComplicated...' + i);
    }
}