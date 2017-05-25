'use strict';

const Promise          = require('bluebird');
const _                = require('lodash');
const Constance        = require('./enties/Constance');
const CarInfo          = require('./enties/CarInfo');
const SendRequest      = require('./service/SendRequest');
const CarInfoClass     = CarInfo.CarInfo;
const CarapplyarrClass = CarInfo.Carapplyarr;

(async function () {

    const entercarlist = await SendRequest.entercarlist();

    if (_.isEmpty(entercarlist)) { return; }

    if (entercarlist.rescode !== "200") {
        console.log('获取车辆列表失败了,错误信息是:', JSON.stringify(entercarlist));
        return;
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
        const addcartype = await SendRequest.addcartype(newCar);

    }

})();
