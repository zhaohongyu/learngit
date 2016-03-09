//
//  Goods.h
//  20-团购列表2
//
//  Created by 赵洪禹 on 16/3/9.
//  Copyright © 2016年 赵洪禹. All rights reserved.
//

#import <Foundation/Foundation.h>

@interface Goods : NSObject

@property (nonatomic, copy) NSString *icon;
@property (nonatomic, copy) NSString *title;
@property (nonatomic, copy) NSString *price;
@property (nonatomic, copy) NSString *buyCount;

-(instancetype)initWithDict:(NSDictionary *)dict;
+(instancetype)goodsWithDict:(NSDictionary *)dict;

@end
