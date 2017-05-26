'use strict';

const Promise          = require('bluebird');
const _                = require('lodash');
const Constance        = require('./enties/Constance');
const CarInfo          = require('./enties/CarInfo');
const SendRequest      = require('./service/SendRequest');
const CarInfoClass     = CarInfo.CarInfo;
const CarapplyarrClass = CarInfo.Carapplyarr;
const DateUtil         = require('./utils/DateUtil');
const delay            = 60 * 5;// 秒

function Cron() {}

Cron.prototype.canApplyV2 = Promise.coroutine(function*(val) {
    try {
        console.log('当前时间是:', val);

        let logMsg = '可以进行进京证申请了,时间是:' + DateUtil.currentDateTime();
        console.log(logMsg);
        yield SendRequest.sendSlackNotice(logMsg);

    }
    catch (error) {
        console.log('canApply 出错了,报错信息是:', error.message);
        return this.canApplyV2(DateUtil.currentDateTime());
    }
});

Cron.prototype.canApply = Promise.coroutine(function*(val) {
    try {
        console.log('当前时间是:', val);
        yield Promise.delay(delay * 1000);

        let carapplyData   = {
            "applyid"        : "013201705182104374760241",
            "carid"          : "22857",
            "cartype"        : "02",
            "engineno"       : "C64813",
            "enterbjend"     : "2017-05-25",
            "enterbjstart"   : "2017-05-19",
            "existpaper"     : "",
            "licenseno"      : "冀B332F1",
            "loadpapermethod": "",
            "remark"         : "d5e0666bc377f063bf1b8618cd1bcafe",
            "status"         : "1",
            "syscode"        : "",
            "syscodedesc"    : "",
            "userid"         : "27b37efca29347cd9c1a476ab4818a37"
        };
        let newCarapplyarr = new CarapplyarrClass(carapplyData);

        let carInfoData = {
            "carid"      : "332004",
            "userid"     : "209E053891214D80ABD3ECBBBE69D8F6",
            "licenseno"  : "冀B332F1",
            "applyflag"  : "1",
            "carapplyarr": [newCarapplyarr]
        };
        let newCar      = new CarInfoClass(carInfoData);

        const addcartypeRes = yield SendRequest.addcartype(newCar);

        if (!SendRequest.isCanRequest(addcartypeRes)) {
            return this.canApply(DateUtil.currentDateTime());
        }

        let logMsg = '可以进行进京证申请了,时间是:' + DateUtil.currentDateTime();
        console.log(logMsg);
        yield SendRequest.sendWxNotice(logMsg);

    }
    catch (error) {
        console.log('canApply 出错了,报错信息是:', error.message);
        return this.canApply(DateUtil.currentDateTime());
    }
});

Cron.prototype.myApply = Promise.coroutine(function*(val) {
    try {
        yield Promise.delay(delay * 1000);

        const entercarlist = yield SendRequest.entercarlist();

        if (_.isEmpty(entercarlist)) {
            return this.myApply(DateUtil.currentDateTime());
        }

        if (entercarlist.rescode !== "200") {
            console.log('获取车辆列表失败了,错误信息是:', JSON.stringify(entercarlist));
            return this.myApply(DateUtil.currentDateTime());
        }

        const datalist = entercarlist.datalist;
        for (let myCarInfo of datalist) {

            let carapplyData   = myCarInfo.carapplyarr[0];
            let newCarapplyarr = new CarapplyarrClass(carapplyData);

            let newMyCarInfoData         = _.pick(myCarInfo, ['carid', 'userid', 'licenseno ', 'applyflag']);
            newMyCarInfoData.carapplyarr = [newCarapplyarr];
            let newCar                   = new CarInfoClass(newMyCarInfoData);

            if (newCarapplyarr.isPass()) {
                console.log(newCarapplyarr.licenseno + '申请通过...');
                continue;
            }

            if (!newCar.canRequest()) {
                console.log(newCarapplyarr.licenseno + '当前状态不能不能申请操作...');
                continue;
            }

            // todo 申请操作
            const addcartype = yield SendRequest.addcartype(newCar);

        }

        return this.myApply(DateUtil.currentDateTime());

    }
    catch (error) {
        console.log('myApply 出错了,报错信息是:', error.message);
        return this.myApply(DateUtil.currentDateTime());
    }
});

const cron = new Cron();
// cron.myApply(0);
// cron.canApply(0);
cron.canApplyV2(0);
