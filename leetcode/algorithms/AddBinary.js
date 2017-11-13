/**
 * Created by zhaohongyu on 2017/11/13.
 */


/**
 * @param {string} a
 * @param {string} b
 * @return {string}
 */
let addBinary = function (a, b) {

    let aArr = a.split('');
    let bArr = b.split('');

    let aLen = a.length, bLen = b.length;

    if (aLen === 0) return b;
    if (bLen === 0) return a;

    let sb    = [];
    let carry = 0;
    for (let ia = aLen - 1, ib = bLen - 1; ia >= 0 || ib >= 0; ia--, ib--) {

        let aNum = (ia < 0) ? 0 : parseInt(aArr[ia]);
        let bNum = (ib < 0) ? 0 : parseInt(bArr[ib]);

        let tmp = aNum + bNum + carry;
        let num = tmp % 2;
        carry   = parseInt(tmp / 2);
        sb.push(num);
    }

    if (carry === 1) {
        sb.push(1);
    }

    return sb.reverse().join('');
};

const a      = "100001";
const b      = "1";
const result = addBinary(a, b);
console.log('结果是:', result);
