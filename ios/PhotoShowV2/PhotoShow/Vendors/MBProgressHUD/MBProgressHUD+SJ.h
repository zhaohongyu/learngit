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
@end
