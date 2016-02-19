//
//  RowView.h
//  08-联系人管理-xib
//
//  Created by 赵洪禹 on 16/2/19.
//  Copyright © 2016年 赵洪禹. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface RowView : UIView

+ (RowView *)rowViewWithIcon:(NSString *)icon concatName:(NSString *)concatName;

@property (weak, nonatomic) IBOutlet UIButton *icon;
@property (weak, nonatomic) IBOutlet UILabel *concatName;
@property (weak, nonatomic) IBOutlet UIButton *deleteBtn;

@end
