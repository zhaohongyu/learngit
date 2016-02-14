//
//  Person.m
//  构造方法
//
//  Created by 赵洪禹 on 16/1/29.
//  Copyright © 2016年 赵洪禹. All rights reserved.
//

#import "Person.h"

@implementation Person

- (id) init
{
    if(self = [super init]){
        _age = 66;
    }
    return self;
}

@end
