//
//  Province.m
//  12-UITableView多组数据展示
//
//  Created by 赵洪禹 on 16/2/21.
//  Copyright © 2016年 赵洪禹. All rights reserved.
//

#import "Province.h"

@implementation Province

+ (id)provinceWithHeader:(NSString *)header footer:(NSString *)footer citys:(NSArray *)citys{
    Province *province = [[Province alloc] init];
    province.header = header;
    province.footer = footer;
    province.citys = citys;
    return province;
}

@end
