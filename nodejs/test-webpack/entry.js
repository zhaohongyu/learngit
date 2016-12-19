/**
 * Created by zhaohongyu on 2016/12/19.
 */
// require("!style!css!./style.css") // 载入 style.css
// webpack entry.js bundle.js --module-bind 'css=style!css'
require("./style.css") // 载入 style.css
document.write('It works.')
document.write(require('./module.js'))