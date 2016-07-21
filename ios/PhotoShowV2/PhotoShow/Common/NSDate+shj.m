//
//  NSDate+shj.m
//  PhotoShow
//
//  Created by 沈健 on 16/7/3.
//  Copyright © 2016年 shenjian. All rights reserved.
//

#import "NSDate+shj.h"

@implementation NSDate (shj)

+ (NSDate *)dateFromTimesp:(double)timesp{
    NSDate *date = [NSDate dateWithTimeIntervalSince1970:timesp];
    return date;
}

+ (NSString *)strFromDate:(NSDate *)date{
    NSDateFormatter *formatter = [[NSDateFormatter alloc] init];
    [formatter setDateStyle:NSDateFormatterMediumStyle];
    [formatter setTimeStyle:NSDateFormatterShortStyle];
    [formatter setDateFormat:@"yyyy-MM-dd"];
    return [formatter stringFromDate:date];
}

+ (NSDate *)dateFromStr:(NSString *)str{
    NSDateFormatter *formatter = [[NSDateFormatter alloc] init];
    [formatter setDateStyle:NSDateFormatterMediumStyle];
    [formatter setTimeStyle:NSDateFormatterShortStyle];
    [formatter setDateFormat:@"yyyy-MM-dd"];
    return [formatter dateFromString:str];
}
@end
