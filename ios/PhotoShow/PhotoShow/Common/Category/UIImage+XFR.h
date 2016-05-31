//
//  UIImage+XFR.h
//  XFRguest
//
//  Created by 沈健 on 15/6/15.
//  Copyright (c) 2015年 shenjian. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface UIImage (XFR)
+ (UIImage *)resizedImageWithName:(NSString *)name;
+ (UIImage *)resizedImageWithName:(NSString *)name left:(CGFloat)left top:(CGFloat)top;
@end
