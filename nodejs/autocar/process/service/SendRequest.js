/**
 * Created by zhaohongyu on 2017/5/25.
 */

const axios     = require('axios');
const DateUtil  = require('../utils/DateUtil');
const Constance = require('../enties/Constance');

exports.addcartype = async function (CarInfo) {
    try {
        const url          = Constance.URL.host + '/enterbj/platform/enterbj/addcartype';
        const carapplyInfo = CarInfo.carapplyarr[0];
        return await axios({
            method : 'post',
            url    : url,
            headers: Constance.HEADERS,
            data   : {
                "userid"    : CarInfo.userid,
                "applyid"   : carapplyInfo.applyid,
                "gpslon"    : Constance.LOCATION.lngy,
                "gpslat"    : Constance.LOCATION.latx,
                "imei"      : "",
                "imsi"      : "",
                "licenseno" : carapplyInfo.licenseno,
                "hiddentime": DateUtil.currentDateTime(),
                "appsource" : Constance.USER.appsource,
                "carid"     : carapplyInfo.carid,
            },
        });
    }
    catch (error) {

        if (error instanceof Error) {

            console.log('请求提交车辆申请接口addcartype出错了,报错信息是:');

            if (error.response) {
                console.log(error.response.data.responseText);
            }

            if (error.request) {
                console.log(error.request);
                console.log('服务器未响应');
            }

            console.log(error);

        }

        return {};

    }
};

exports.entercarlist = async function () {
    try {

        const url = Constance.URL.host + '/enterbj/platform/enterbj/entercarlist';

        Constance.HEADERS['X-Requested-With'] = 'XMLHttpRequest';

        const response = await axios({
            method : 'post',
            url    : url,
            headers: Constance.HEADERS,
            data   : {
                "userid"   : Constance.USER.userid,
                "appkey"   : Constance.USER.appkey,
                "deviceid" : Constance.USER.deviceid,
                "timestamp": Constance.USER.timestamp,
                "token"    : Constance.USER.token,
                "appsource": Constance.USER.appsource,
            },
        });
        return response.data;
    }
    catch (error) {

        if (error instanceof Error) {

            console.log('请求车辆列表接口entercarlist出错了,报错信息是:');

            if (error.response) {
                console.log(error.response.data.responseText);
            }

            if (error.request) {
                console.log(error.request);
                console.log('服务器未响应');
            }

            console.log(error);

        }

        return {};

    }
};
