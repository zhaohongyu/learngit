//
//  MyKeyBoard.h
//  10-键盘操作
//
//  Created by 赵洪禹 on 16/2/20.
//  Copyright © 2016年 赵洪禹. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface MyKeyBoard : UIToolbar

+ (MyKeyBoard *)keyBoard;

@property (weak, nonatomic) IBOutlet UIBarButtonItem *upItem;
@property (weak, nonatomic) IBOutlet UIBarButtonItem *downItem;
@property (weak, nonatomic) IBOutlet UIBarButtonItem *closeKeyBoard;

@end
