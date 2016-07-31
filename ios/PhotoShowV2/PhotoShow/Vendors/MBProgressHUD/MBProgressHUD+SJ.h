//
//  MBProgressHUD+SJ.h
//  PhotoShow
//
//  Created by 沈健 on 16/5/28.
//  Copyright © 2016年 shenjian. All rights reserved.
//

#import "MBProgressHUD.h"

@interface MBProgressHUD (SJ)
+ (void)showHUDtoView:(UIView *)view;
+ (void)hideHUDForView:(UIView *)view;
+ (void)showHUD;
+ (void)hideHUD;

+ (void)showSuccess:(NSString *)success toView:(UIView *)view;
+ (void)showError:(NSString *)error toView:(UIView *)view;

+ (MBProgressHUD *)showMessage:(NSString *)message toView:(UIView *)view;

+ (void)showSuccess:(NSString *)success;
+ (void)showError:(NSString *)error;

+ (MBProgressHUD *)showMessage:(NSString *)message;

@end
