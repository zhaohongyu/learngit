/**
 * Created by zhaohongyu on 2017/1/6.
 */

var express      = require('express'),
    cookieParser = require('cookie-parser'),
    i18n         = require('i18n'),
    app          = module.exports = express(),
    secret     = '123456',
    cookieName = 'locale';

i18n.configure({
    locales      : ['en-US', 'zh-CN'],  // setup some locales - other locales default to en_US silently
    cookie       : cookieName,
    defaultLocale: 'zh-CN',
    directory    : __dirname + '/locale',  // i18n 翻译文件目录
    updateFiles  : false,
    indent       : "\t",
    extension    : '.json'
});

// you will need to use cookieParser to expose cookies to req.cookies
app.use(cookieParser(secret));

// i18n init parses req for language headers, cookies, etc.
app.use(i18n.init);

// 添加setLocale中间件，注意必须在session之后
app.use(setLocale);

// 定义setLocale中间件
function setLocale(req, res, next) {
    var locale;
    // 当req进入i18n中间件的时候，已经通过sessionId信息获取了用户数据
    // 获取用户数据中的locale数据
    if (req.user) {
        locale = req.user.locale;
    }
    // 获取cookie中的locale数据
    else if (req.signedCookies[cookieName]) {
        locale = req.signedCookies[cookieName];
    }
    // 获取浏览器第一个偏好语言，这个函数是express提供的
    else if (req.acceptsLanguages()) {
        locale = req.acceptsLanguages();
    }
    // 没有语言偏好的时候网站使用的语言为中文
    else {
        locale = 'zh_CN';
    }
    // 如果cookie中保存的语言偏好与此处使用的语言偏好不同，更新cookie中的语言偏好设置
    if (req.signedCookies[cookieName] !== locale) {
        res.cookie(cookieName, locale, {maxAge: (60 * 60 * 24 * 365) * 1000, signed: true, httpOnly: true});
    }
    // 设置i18n对这个请求所使用的语言
    req.setLocale(locale);
    next();
}

// serving homepage
app.get('/', function (req, res) {
    var greeting = res.__('Welcome');
    res.send(greeting);
});

// starting server
if (!module.parent) {
    app.listen(3001);
    console.log('Server running at http://127.0.0.1:3001/');
}

