/**
 * Created by zhaohongyu on 2017/11/10.
 */


let bubbleSort = function (nums) {


    let len = nums.length;

    for (let i = 0; i < len; i++) {

        for (let j = 0; j < len - i; j++) {

            if (nums[j] > nums[j + 1]) {

                let temp    = nums[j];
                nums[j]     = nums[j + 1];
                nums[j + 1] = temp;
            }

        }

    }

    return nums;

};


const nums   = [1, -1, -2, 4, 2, 6, 3, 2, 7, 6, 10];
const result = bubbleSort(nums);
console.log('结果是:', result);