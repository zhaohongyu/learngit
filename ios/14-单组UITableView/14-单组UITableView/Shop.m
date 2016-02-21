//
//  Shop.m
//  13-单租数据展示
//
//  Created by 赵洪禹 on 16/2/21.
//  Copyright © 2016年 赵洪禹. All rights reserved.
//

#import "Shop.h"

@implementation Shop

+ (id)shopWithIcon:(NSString *)icon title:(NSString *)title detail:(NSString *)detail{
    Shop *shop = [[Shop alloc] init];
    shop.icon = icon;
    shop.title = title;
    shop.detail = detail;
    return shop;
}

@end
