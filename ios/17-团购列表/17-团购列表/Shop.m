//
//  Shop.m
//  17-团购列表
//
//  Created by 赵洪禹 on 16/3/1.
//  Copyright © 2016年 赵洪禹. All rights reserved.
//

#import "Shop.h"

@implementation Shop

-(id)initWithDict:(NSDictionary *)dict{
    if (self = [super init]) {
        self.name = dict[@"name"];
        self.icon = dict[@"icon"];
        self.desc = dict[@"desc"];
    }
    return self;
}

+(id)shopWithDict:(NSDictionary *)dict{
    return [[self alloc] initWithDict:dict];
}

@end
