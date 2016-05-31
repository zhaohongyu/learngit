//
//  NSString+Dictionary.h
//  XFRguest
//
//  Created by 沈健 on 15/6/18.
//  Copyright (c) 2015年 shenjian. All rights reserved.
//

#import <Foundation/Foundation.h>

@interface NSString (Dictionary)

//通过关键字查询
+(NSString *)urlSearchWith:(NSString *)url Search:(NSString *)search;

// 通过字典转为URL字符串
+(NSString *)urlStringWith:(NSString *)str Dictionary:(NSMutableDictionary *)dict;

// 通过字典转URLENCODE码
+(NSString *)urlJsonStringWith:(NSString *)str Dictionary:(NSMutableDictionary *)dict;
/**
 *  url中添加limit条件
 *
 *  @param str      原始url串
 *  @param limitNum limit的条件
 *
 *  @return 返回url
 */

+(NSString *)urlLimitStringWiht:(NSString *)str limitNum:(NSInteger)limitNum;
/**
 *  url中添加skip条件
 *
 *  @param str  原始url串
 *  @param skip skip index
 *
 *  @return 返回url
 */
+(NSString *)urlSkipStringWiht:(NSString *)str skip:(NSInteger)skip;
/**
 *  url中添加order条件
 *
 *  @param str   原始url串
 *  @param order 条件
 *
 *  @return 返回url
 */
+(NSString *)urlOrderStringWiht:(NSString *)str order:(NSString *)order;


@end
