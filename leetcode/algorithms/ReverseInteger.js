/**
 * Created by zhaohongyu on 2017/11/8.
 * Assume we are dealing with an environment which could only hold integers within the 32-bit signed integer range.
 * For the purpose of this problem, assume that your function returns 0 when the reversed integer overflows.
 * Input: 120
 * Output: 21
 */

let reverse = function (x) {
    let revertedNumber = 0;
    while (x !== 0) {
        revertedNumber = (revertedNumber * 10) + (x % 10);
        x              = parseInt(x / 10);
        console.log(`x = ${x},revertedNumber = ${revertedNumber}`);
    }

    const limit = Math.pow(2, 31);
    if ((revertedNumber > limit) || (revertedNumber < -limit)) {
        revertedNumber = 0;
    }

    return revertedNumber;
};

// let reverse = function (x) {
//     let temp   = (x < 0) ? Math.abs(x) : x;
//     temp       = temp + '';
//     let result = temp.split('').reverse().join('');
//     result     = (x < 0) ? -Number(result) : Number(result);
//
//     const limit = Math.pow(2, 31);
//     if ((result > limit) || (result < -limit)) {
//         result = 0;
//     }
//
//     return result;
//
// };


const x      = -123;
const result = reverse(x);
console.log('结果是:', result);
