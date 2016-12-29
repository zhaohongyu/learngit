/**
 * Created by zhaohongyu on 2016/12/29.
 */

var async = require('async');

async.series({
    one: function (callback) {
        callback(null, 1);
    },
    two: function (callback) {
        callback(null, 2);
    }
}, function (err, results) {
    console.log(results);
});

async.waterfall([
    function (callback) {
        callback(null, 'one', 'two');
    },
    function (arg1, arg2, callback) {
        // arg1 now equals 'one' and arg2 now equals 'two'
        callback(null, 'three');
    },
    function (arg1, callback) {
        // arg1 now equals 'three'
        callback(null, 'done');
    }
], function (err, result) {
    // result now equals 'done'
    console.log(result);
});

async.parallel([
        function (callback) {
            callback(null, 'one');
        },
        function (callback) {
            callback(null, 'two');
        }
    ],
    function (err, results) {
        console.log(results);
    });