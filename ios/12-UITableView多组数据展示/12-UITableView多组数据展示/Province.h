//
//  Province.h
//  12-UITableView多组数据展示
//
//  Created by 赵洪禹 on 16/2/21.
//  Copyright © 2016年 赵洪禹. All rights reserved.
//

#import <Foundation/Foundation.h>

@interface Province : NSObject

+ (id)provinceWithHeader:(NSString *)header footer:(NSString *)footer citys:(NSArray *)citys;

@property (nonatomic,copy) NSString *header;
@property (nonatomic,copy) NSString *footer;
@property (nonatomic,strong) NSArray *citys;

@end
