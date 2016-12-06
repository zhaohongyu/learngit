/**
 * Created by zhaohongyu on 2016/12/5.
 */
'use strict';

var soundButtons = document.querySelectorAll('.button-sound');

for (var i = 0; i < soundButtons.length; i++) {
    var soundButton = soundButtons[i];
    var soundName = soundButton.attributes['data-sound'].value;

    prepareButton(soundButton, soundName);
}

function prepareButton(buttonEl, soundName) {
    // buttonEl.querySelector('span').style.backgroundImage = 'url("img/icons/' + soundName + '.png")';

    var audio = new Audio(__dirname + '/wav/' + soundName + '.wav');
    buttonEl.addEventListener('click', function () {
        audio.currentTime = 0;
        audio.play();
    });
}

// 通过ipc渲染进程发送窗口关闭消息给主进程
const ipcRenderer = require('electron').ipcRenderer;
var closeEl = document.querySelector('.close');
closeEl.addEventListener('click', function () {
    ipcRenderer.send('close-main-window');
});

// 渲染进程监听全局快捷键点击事件
ipcRenderer.on('global-shortcut', function (evt, message) {
    var event = new MouseEvent('click');
    soundButtons[message].dispatchEvent(event);
});

// 渲染进程发送设置按钮点击事件给主进程
var settingsEl = document.querySelector('.settings');
settingsEl.addEventListener('click', function () {
    ipcRenderer.send('open-settings-window');
});

// 创建一个菜单，并把它绑定到托盘图标
var remote = require('electron').remote;
var Tray = remote.Tray;
var Menu = remote.Menu;
var path = require('path');

var trayIcon = null;

if (process.platform === 'darwin') {
    trayIcon = new Tray(path.join(__dirname, 'img/tray-iconTemplate.png'));
}
else {
    trayIcon = new Tray(path.join(__dirname, 'img/tray-icon-alt.png'));
}

var trayMenuTemplate = [
    {
        label: 'Sound machine',
        enabled: false
    },
    {
        label: 'Settings',
        click: function () {
            ipcRenderer.send('open-settings-window');
        }
    },
    {
        label: 'Quit',
        click: function () {
            ipcRenderer.send('close-main-window');
        }
    }
];
var trayMenu = Menu.buildFromTemplate(trayMenuTemplate);
trayIcon.setContextMenu(trayMenu);






