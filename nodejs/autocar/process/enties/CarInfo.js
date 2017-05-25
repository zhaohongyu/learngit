/**
 * Created by zhaohongyu on 2017/5/25.
 */

const DateUtil = require('../utils/DateUtil');

exports.CarInfo = class {

    constructor(data) {
        this.carid       = data.carid;
        this.userid      = data.userid;
        this.licenseno   = data.licenseno;
        this.applyflag   = data.applyflag;
        this.carapplyarr = data.carapplyarr;
    }

    // 能否进行申请
    canRequest() { return this.applyflag === "1"; }
};

exports.Carapplyarr = class {

    constructor(data) {
        this.applyid         = data.applyid;
        this.carid           = data.carid;
        this.cartype         = data.cartype;
        this.engineno        = data.engineno;
        this.enterbjend      = data.enterbjend;
        this.enterbjstart    = data.enterbjstart;
        this.existpaper      = data.existpaper;
        this.licenseno       = data.licenseno;
        this.loadpapermethod = data.loadpapermethod;
        this.remark          = data.remark;
        this.status          = data.status;// 1 审核通过  2: 审核中
        this.syscode         = data.syscode;
        this.syscodedesc     = data.syscodedesc;
        this.userid          = data.userid;
    }

    // 申请是否通过
    isPass() { return this.status === "1"; }

    valid() {
        let start = DateUtil.strToTimestamp(this.enterbjstart);
        let end   = DateUtil.strToTimestamp(this.enterbjend);
        return (this.isPass() )
            && (DateUtil.currentTimestamp() > start)
            && (DateUtil.currentTimestamp() < end);
    }
};

