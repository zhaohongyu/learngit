//
//  Banner.h
//  20-团购列表2
//
//  Created by 赵洪禹 on 16/3/10.
//  Copyright © 2016年 赵洪禹. All rights reserved.
//

#import <Foundation/Foundation.h>

@interface Banner : NSObject
@property (nonatomic, copy) NSString *icon;
@property (nonatomic, copy) NSString *title;

-(instancetype)initWithDict:(NSDictionary *)dict;
+(instancetype)bannerWithDict:(NSDictionary *)dict;
@end
