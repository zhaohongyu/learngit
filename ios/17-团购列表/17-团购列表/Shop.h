//
//  Shop.h
//  17-团购列表
//
//  Created by 赵洪禹 on 16/3/1.
//  Copyright © 2016年 赵洪禹. All rights reserved.
//

#import <Foundation/Foundation.h>

@interface Shop : NSObject

@property (nonatomic,copy) NSString *icon;
@property (nonatomic,copy) NSString *name;
@property (nonatomic,copy) NSString *desc;
@property (nonatomic , assign) BOOL isShow;

- (id)initWithDict:(NSDictionary *)dict;
+ (id)shopWithDict:(NSDictionary *)dict;

@end
