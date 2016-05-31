//
//  UIImage+XFR.m
//  XFRguest
//
//  Created by 沈健 on 15/6/15.
//  Copyright (c) 2015年 shenjian. All rights reserved.
//

#import "UIImage+XFR.h"

@implementation UIImage (XFR)
+ (UIImage *)resizedImageWithName:(NSString *)name
{
    return [self resizedImageWithName:name left:0.5 top:0.5];
}

+ (UIImage *)resizedImageWithName:(NSString *)name left:(CGFloat)left top:(CGFloat)top
{
    UIImage *image = [self imageNamed:name];
    return [image stretchableImageWithLeftCapWidth:image.size.width * left topCapHeight:image.size.height * top];
}
@end
