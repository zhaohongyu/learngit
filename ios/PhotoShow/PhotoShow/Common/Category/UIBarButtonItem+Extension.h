//
//  UIBarButtonItem+Extension.h
//  XFRguest
//
//  Created by 沈健 on 15/6/14.
//  Copyright (c) 2015年 shenjian. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface UIBarButtonItem (Extension)
/**
 *  快速创建一个显示图片的item
 *
 *  @param action   监听方法
 */
+ (UIBarButtonItem *)itemWithIcon:(NSString *)icon highIcon:(NSString *)highIcon target:(id)target action:(SEL)action;
@end
