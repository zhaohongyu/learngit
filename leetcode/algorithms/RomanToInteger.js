/**
 * Created by zhaohongyu on 2017/11/8.
 */


/**
 * @param {string} s
 * @return {number}
 */
const romanToInt = function (s) {

    const roman = {'M': 1000, 'D': 500, 'C': 100, 'L': 50, 'X': 10, 'V': 5, 'I': 1};
    let z       = 0;
    let arr     = s.split('');
    let len     = arr.length;

    for (let i = 0; i < len - 1; i++) {

        let first  = arr[i];
        let second = arr[i + 1];

        if (roman[first] < roman[second]) {
            z -= roman[first];
        } else {
            z += roman[first];
        }
    }

    return z + roman[arr[len - 1]];
};

const s      = 'MDCCCXCIX';
const result = romanToInt(s);
console.log('结果是:', result);
