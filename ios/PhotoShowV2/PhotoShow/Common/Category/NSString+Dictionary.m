//
//  NSString+Dictionary.m
//  XFRguest
//
//  Created by 沈健 on 15/6/18.
//  Copyright (c) 2015年 shenjian. All rights reserved.
//

#import "NSString+Dictionary.h"

@implementation NSString (Dictionary)

+(NSString *)urlSearchWith:(NSString *)url Search:(NSString *)search
{
//    NSString *str =[NSString stringWithFormat:@"{\"tags\":{\"$in\":[\"%@\"]}}",search];
    NSString *str = [url stringByAppendingFormat:@"{\"tags\":{\"$in\":[\"%@\"]}}",search];
    str = [str stringByAddingPercentEscapesUsingEncoding:NSUTF8StringEncoding];
    return str;
}

// 通过字典转为URL字符串
+(NSString *)urlStringWith:(NSString *)str Dictionary:(NSMutableDictionary *)dict
{
    id key, value;
    
    for (NSUInteger i = 0; i < dict.allKeys.count; i++)
    {
        key = [dict.allKeys objectAtIndex: i];
        
        value = [dict objectForKey: key];
        
        str = [str stringByAppendingFormat:@"%@=%@", key, value];
        
        if (i != (dict.allKeys.count -1)) {
            str = [str stringByAppendingString:@"&"];
        }
    }
    return str;
}

+(NSString *)urlJsonStringWith:(NSString *)str Dictionary:(NSMutableDictionary *)dict
{
    id key, value;
    
    for (NSUInteger i = 0; i < dict.allKeys.count; i++)
    {
        key = [dict.allKeys objectAtIndex: i];
        
        value = [dict objectForKey: key];
        
        if (i == 0) {
            str = [str stringByAppendingString:@"{"];
        }
        if ([value isKindOfClass:[NSNumber class]])
        {
            str = [str stringByAppendingFormat:@"\"%@\":%@", key, value];
        }else
        {
            str = [str stringByAppendingFormat:@"\"%@\":\"%@\"", key, value];
        }
        if (i != (dict.allKeys.count -1)) {
            str = [str stringByAppendingString:@","];
        }
        
        if (i == dict.allKeys.count - 1) {
            str = [str stringByAppendingString:@"}"];
        }
    }
    str = [str stringByAddingPercentEscapesUsingEncoding:NSUTF8StringEncoding];
    return str;
}
/**
 *  url中添加limit条件
 *
 *  @param str      原始url串
 *  @param limitNum limit的条件
 *
 *  @return 返回url
 */
+(NSString *)urlLimitStringWiht:(NSString *)str limitNum:(NSInteger)limitNum
{
    NSString *limitStr = [NSString stringWithFormat:@"&limit=%ld",(long)limitNum];
    str = [str stringByAppendingString:limitStr];
    return str;
}
/**
 *  url中添加skip条件
 *
 *  @param str  原始url串
 *  @param skip skip index
 *
 *  @return 返回url
 */
+(NSString *)urlSkipStringWiht:(NSString *)str skip:(NSInteger)skip
{
    NSString *limitStr = [NSString stringWithFormat:@"&skip=%ld",(long)skip];
    str = [str stringByAppendingString:limitStr];
    return str;
}
/**
 *  url中添加order条件
 *
 *  @param str   原始url串
 *  @param order 条件
 *
 *  @return 返回url
 */
+(NSString *)urlOrderStringWiht:(NSString *)str order:(NSString *)order
{
    NSString *limitStr = [NSString stringWithFormat:@"&&order=%@",order];
    str = [str stringByAppendingString:limitStr];
    return str;
}

@end
