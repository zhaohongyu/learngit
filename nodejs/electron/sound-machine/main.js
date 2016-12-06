const electron = require('electron');
// 控制应用生命周期的模块
const {app} = electron;
// 创建本地浏览器窗口的模块
const {BrowserWindow} = electron;
// 全局快捷键
const globalShortcut = electron.globalShortcut;
// 设置信息
const configuration = require('./configuration');

// 指向窗口对象的一个全局引用，如果没有这个引用，那么当该javascript对象被垃圾回收的
// 时候该窗口将会自动关闭
let win;

function createWindow() {

    // 尝试读取「shortcutKeys」键对应的值，如果读取不到，就设置一个初始值
    if (!configuration.readSettings('shortcutKeys')) {
        configuration.saveSettings('shortcutKeys', ['ctrl', 'shift']);
    }

    // 创建一个新的浏览器窗口
    win = new BrowserWindow({width: 368, height: 700, frame: false, resizable: false});

    // 并且装载应用的index.html页面
    win.loadURL(`file://${__dirname}/app/index.html`);

    // 打开开发工具页面
    win.webContents.openDevTools();

    // 注册全局快捷键
    setGlobalShortcuts();

}

// 当Electron完成初始化并且已经创建了浏览器窗口，则该方法将会被调用。
// 有些API只能在该事件发生后才能被使用。
app.on('ready', createWindow);

// 监听渲染进程关闭按钮点击事件
const ipcMain = electron.ipcMain;
ipcMain.on('close-main-window', function () {
    // 对于OS X系统，应用和相应的菜单栏会一直激活直到用户通过Cmd + Q显式退出
    if (process.platform !== 'darwin') {
        app.quit();
    }
});

// 监听渲染进程设置按钮点击事件
let settingsWindow = null;
ipcMain.on('open-settings-window', function () {

    if (settingsWindow) {
        return;
    }

    settingsWindow = new BrowserWindow({
        frame: false,
        height: 200,
        resizable: false,
        width: 200
    });

    settingsWindow.loadURL(`file://${__dirname}/app/settings.html`);

    settingsWindow.on('closed', function () {
        settingsWindow = null;
    });

});

// 监听设置页面发送来的关闭窗口消息
ipcMain.on('close-settings-window', function () {
    if (settingsWindow) {
        settingsWindow.close();
    }
});

// 设置全局快捷键
function setGlobalShortcuts() {
    globalShortcut.unregisterAll();

    var shortcutKeysSetting = configuration.readSettings('shortcutKeys');
    var shortcutPrefix = shortcutKeysSetting.length === 0 ? '' : shortcutKeysSetting.join('+') + '+';

    globalShortcut.register(shortcutPrefix + '1', function () {
        mainWindow.webContents.send('global-shortcut', 0);
    });
    globalShortcut.register(shortcutPrefix + '2', function () {
        mainWindow.webContents.send('global-shortcut', 1);
    });
}

// 主进程监听全局快捷键设置
ipcMain.on('set-global-shortcuts', function () {
    setGlobalShortcuts();
});