/**
 * Created by zhaohongyu on 2017/3/25.
 */

var archiver = require('archiver');
var fs       = require('fs');

// 被打包文件
var files   = [
    './files/cqe.jpg',
    './files/cqe2.png'
];
var zipPath = './files/cqe.zip';

// 创建一最终打包文件的输出流
var output = fs.createWriteStream(zipPath);

// 生成archiver对象，打包类型为zip
var zipArchiver = archiver('zip');

//将打包对象与输出流关联
zipArchiver.pipe(output);

for (var i = 0; i < files.length; i++) {
    console.log(files[i]);
    // 将被打包文件的流添加进archiver对象中
    zipArchiver.append(fs.createReadStream(files[i]), {'name': files[i]});
}

// 打包
zipArchiver.finalize();