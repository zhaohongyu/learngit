/**
 * Created by zhaohongyu on 2016/12/29.
 */

var fs      = require('fs');
var Promise = require('bluebird');

// var readFileAsync = function (name) {
//     return new Promise(function (resolve, reject) {
//         fs.readFile(name, function (err, data) {
//             if (err) {
//                 reject(err);
//             } else {
//                 resolve(data);
//             }
//         });
//     })
// };

var readFileAsync = Promise.promisify(fs.readFile);

readFileAsync('1.txt')
    .then(function (data1) {
        console.log(data1.toString());
        return readFileAsync('2.txt');
    })
    .then(function (data2) {
        console.log(data2.toString());
    })
    .catch(function (err) {
        console.error(err)
    });
