//
//  Shop.h
//  15-删除UITableView数据
//
//  Created by 赵洪禹 on 16/2/22.
//  Copyright © 2016年 赵洪禹. All rights reserved.
//

#import <Foundation/Foundation.h>

@interface Shop : NSObject

@property (nonatomic,copy) NSString *icon;
@property (nonatomic,copy) NSString *name;
@property (nonatomic,copy) NSString *desc;

- (id)initWithDict:(NSDictionary *)dict;
+ (id)shopWithDict:(NSDictionary *)dict;

@end
