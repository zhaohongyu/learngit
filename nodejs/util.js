/**
 * Created by zhaohongyu on 2016/12/2.
 */

var util = require('util');

function Base() {
    this.name = 'base';
    this.base = 1991;
    this.sayHello = function () {
        console.log('Hello ' + this.name);
    };
}

Base.prototype.showName = function () {
    console.log(this.name);
};

function Sub() {
    this.name = 'sub';
}

util.inherits(Sub, Base);
var objBase = new Base();
objBase.showName();
objBase.sayHello();
console.log(objBase);
var objSub = new Sub();
objSub.showName();
//objSub.sayHello();
console.log(objSub);


var util = require('util');
function Person() {
    this.name = 'byvoid';
    this.toString = function () {
        return this.name;
    };
}
var obj = new Person();
console.log(util.inspect(obj));
console.log(util.inspect(obj, true));


var util = require('util');

util.isArray([])
// true
util.isArray(new Array)
// true
util.isArray({})
// false


var util = require('util');

util.isRegExp(/some regexp/)
// true
util.isRegExp(new RegExp('another regexp'))
// true
util.isRegExp({})
// false


var util = require('util');

util.isDate(new Date())
// true
util.isDate(Date())
// false (without 'new' returns a String)
util.isDate({})
// false

var util = require('util');

util.isError(new Error())
// true
util.isError(new TypeError())
// true
util.isError({name: 'Error', message: 'an error occurred'})
// false