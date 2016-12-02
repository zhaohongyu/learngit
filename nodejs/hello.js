'use strict';

// hello.js
function Hello() {
    var name;
    this.setName = function (thyName) {
        name = thyName;
    };
    this.sayHello = function () {
        console.log('Hello ' + name);
    };
};
module.exports = Hello;

module.exports.world = function () {
    console.log('Hello World');
}