//
//  PSImageView.m
//  PhotoShow
//
//  Created by 赵洪禹 on 16/7/31.
//  Copyright © 2016年 shenjian. All rights reserved.
//

#import "PSImageView.h"

@interface PSImageView()

@end


@implementation PSImageView


-(void)setImage:(UIImage *)image{
    [super setImage:[self clipImage:image toSize:self.frame.size]];
}


- (UIImage *)clipImage:(UIImage *)image toSize:(CGSize)size {
    UIGraphicsBeginImageContextWithOptions(size, YES, [UIScreen mainScreen].scale);
    
    CGSize imgSize = image.size;
    CGFloat x = MAX(size.width / imgSize.width, size.height / imgSize.height);
    CGSize resultSize = CGSizeMake(x * imgSize.width, x * imgSize.height);
    
    [image drawInRect:CGRectMake(0, 0, resultSize.width, resultSize.height)];
    
    UIImage *finalImage = UIGraphicsGetImageFromCurrentImageContext();
    UIGraphicsEndImageContext();
    return finalImage;
}

@end
