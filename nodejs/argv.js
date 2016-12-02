/**
 * Created by zhaohongyu on 2016/12/2.
 */


process.stdin.resume();
process.stdin.on('data', function (data) {
    var data_len = (data.toString()).length;
    if (data_len == 1)return;
    process.stdout.write('read from console: ' + data.toString());
});

console.log(process.argv);