//
//  UIColor+hexColor.h
//  XFRguest
//
//  Created by 沈健 on 15/6/10.
//  Copyright (c) 2015年 shenjian. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface UIColor (hexColor)
+ (UIColor *)colorWithHexString:(NSString *)color;

//从十六进制字符串获取颜色，
//color:支持@“#123456”、 @“0X123456”、 @“123456”三种格式
+ (UIColor *)colorWithHexString:(NSString *)color alpha:(CGFloat)alpha;
@end
