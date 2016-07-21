//
//  NSDate+shj.h
//  PhotoShow
//
//  Created by 沈健 on 16/7/3.
//  Copyright © 2016年 shenjian. All rights reserved.
//

#import <Foundation/Foundation.h>

@interface NSDate (shj)

+ (NSDate *)dateFromTimesp:(double)timesp;

+ (NSString *)strFromDate:(NSDate *)date;

+ (NSDate *)dateFromStr:(NSString *)str;
@end
