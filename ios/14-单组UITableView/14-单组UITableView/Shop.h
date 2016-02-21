//
//  Shop.h
//  13-单租数据展示
//
//  Created by 赵洪禹 on 16/2/21.
//  Copyright © 2016年 赵洪禹. All rights reserved.
//

#import <Foundation/Foundation.h>

@interface Shop : NSObject

+ (id)shopWithIcon:(NSString *)icon title:(NSString *)title detail:(NSString *)detail;

@property (nonatomic,copy) NSString *icon;
@property (nonatomic,copy) NSString *title;
@property (nonatomic,copy) NSString *detail;

@end
