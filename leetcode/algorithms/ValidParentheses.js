/**
 * Created by zhaohongyu on 2017/11/8.
 * Valid Parentheses
 */

/**
 * @param {string} left
 * @return {boolean}
 */
let isLeft = function (left) {
    return (left === '(' || left === '{' || left === '[');
};

/**
 * @param {string} left
 * @param {string} right
 * @return {boolean}
 */
let isClose = function (left, right) {
    if (left === '(') {
        return right === ')';
    }
    if (left === '[') {
        return right === ']';
    }
    if (left === '{') {
        return right === '}';
    }
    return false;
};

/**
 * @param {string} s
 * @return {boolean}
 */
let isValid = function (s) {
    let temp = s.split('');
    let len  = temp.length;
    if ((len === 0) || (len % 2 !== 0)) {
        return false;
    }

    let arr = [];
    for (let i = 0; i < len; i++) {
        if (isLeft(temp[i])) {
            arr.push(temp[i]);
        } else {
            if (arr.length === 0) {
                return false;
            }

            if (!isClose(arr.pop(), temp[i])) {
                return false;
            }
        }
    }

    return arr.length === 0;
};

const s      = "([])";
const result = isValid(s);
console.log('结果是:', result);