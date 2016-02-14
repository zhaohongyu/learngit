//
//  Student.m
//  构造方法
//
//  Created by 赵洪禹 on 16/1/29.
//  Copyright © 2016年 赵洪禹. All rights reserved.
//

#import "Student.h"

@implementation Student

- (id)init
{
    if(self =[super init]){
        self->_no = @"no99999";
    }
    return self;
}

@end
