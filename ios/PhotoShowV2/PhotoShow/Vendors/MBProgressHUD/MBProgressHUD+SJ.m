//
//  MBProgressHUD+SJ.m
//  PhotoShow
//
//  Created by 沈健 on 16/5/28.
//  Copyright © 2016年 shenjian. All rights reserved.
//

#import "MBProgressHUD+SJ.h"

@implementation MBProgressHUD (SJ)
+ (void)showHUDtoView:(UIView *)view{
    if (view == nil) view = [[UIApplication sharedApplication].windows lastObject];
    [MBProgressHUD showHUDAddedTo:view animated:YES];
}

+ (void)hideHUDForView:(UIView *)view
{
    if (view == nil) view = [[UIApplication sharedApplication].windows lastObject];
    [self hideHUDForView:view animated:YES];
}

+ (void)showHUD{
    [self showHUDtoView:nil];
}

+(void)hideHUD{
    [self hideHUDForView:nil];
}
@end
