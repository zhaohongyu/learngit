/**
 * Created by zhaohongyu on 2017/11/10.
 */

/**
 * @param {number} n
 * @return {string}
 */
let countAndSay = function (n) {

    let curr = "1";
    let prev = '';
    let count;
    let say;
    for (let i = 1; i < n; i++) {
        prev  = curr;
        curr  = '';
        count = 1;
        say   = prev.charAt(0);

        for (let j = 1, len = prev.length; j < len; j++) {
            if (prev.charAt(j) !== say) {
                curr  = `${curr}${count}${say}`;
                count = 1;
                say   = prev.charAt(j);
            } else {
                count++;
            }
        }
        curr = `${curr}${count}${say}`;
    }
    return curr.toString();
};

const n      = 5;
const result = countAndSay(n);
console.log('结果是:', result);