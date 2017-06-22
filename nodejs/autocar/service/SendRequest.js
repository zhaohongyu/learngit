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

// 当前时间能否进行进京证申请,根据返回页面来判断
exports.isCanRequest = function (response) {
    const data = response.data;

    if (exports.isContains(data, "如果您需要办理进京证")) {
        return false;
    }

    if (exports.isContains(data, "非服务时间暂时无法办理")) {
        return false;
    }

    if (exports.isContains(data, "由于办理电子进京证申请排队人数过多")) {
        return false;
    }
    return true;
};

// 判断是否包含某字符串
exports.isContains = function (str, substr) {
    return str.indexOf(substr) >= 0;
};

exports.sendWxNotice = async function (desp) {
    try {

        const url = 'http://sc.ftqq.com/SCU4078T15fb92b84446c8258ece24ab0be49f5f5836a1082920e.send';

        const response = await axios.get(url, {
            params: {
                "text": "进京证申请提醒",
                "desp": desp,
            }
        });
        return response.data;
    }
    catch (error) {

        if (error instanceof Error) {

            console.log('发送通知接口sendWxNotice出错了,报错信息是:');

            if (error.response) {
                console.log(error.response.data.responseText);
            }

            if (error.request) {
                console.log(error.request);
                console.log('服务器未响应');
            }

            console.log(error);

        }

    }
};

exports.sendSlackNotice = async function (desp) {
    try {

        const url = 'https://hooks.slack.com/services/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';

        const response = await axios({
            method : 'post',
            url    : url,
            headers: {
                "Content-Type": "application/json; charset=utf-8",
            },
            data   : {
                "username": "进京证申请提醒",
                "text"    : desp
            }
        });
        return response.data;
    }
    catch (error) {

        if (error instanceof Error) {

            console.log('发送通知接口sendSlackNotice出错了,报错信息是:');

            if (error.response) {
                console.log(error.response.data.responseText);
            }

            if (error.request) {
                console.log(error.request);
                console.log('服务器未响应');
            }

            console.log(error);

        }

    }
};
