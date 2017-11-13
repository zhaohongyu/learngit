/**
 * Created by zhaohongyu on 2017/11/7.
 *
 * Given nums = [2, 7, 11, 15], target = 9,
 * Because nums[0] + nums[1] = 2 + 7 = 9,
 * return [0, 1].
 *
 */

/**
 * @param {number[]} nums
 * @param {number} target
 * @return {number[]}
 */
const twoSum = function (nums, target) {

    let result   = [];
    const length = nums.length;
    for (let i = 0; i < length; i++) {

        console.log('i, nums[%s] = %s', i, nums[i]);

        for (let j = i + 1; j < length; j++) {

            let temp = nums[j];
            console.log('j, nums[%s] = %s', j, temp);
            if (nums[j] === (target - nums[i])) {
                result.push(i);
                result.push(j);
                return result;
            }
        }

    }

    return result;

};

const nums   = [1, 15, 11, 2, 7];
const target = 9;
const result = twoSum(nums, target);
console.log('结果是:', result);