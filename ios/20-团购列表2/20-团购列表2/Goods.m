//
//  Goods.m
//  20-团购列表2
//
//  Created by 赵洪禹 on 16/3/9.
//  Copyright © 2016年 赵洪禹. All rights reserved.
//

#import "Goods.h"

@implementation Goods

-(instancetype)initWithDict:(NSDictionary *)dict{
    if (self = [super init]) {
        [self setValuesForKeysWithDictionary:dict];
    }
    return self;
}
+(instancetype)goodsWithDict:(NSDictionary *)dict{
    return [[self alloc] initWithDict:dict];
}

@end
