//
//  RowView.m
//  08-联系人管理-xib
//
//  Created by 赵洪禹 on 16/2/19.
//  Copyright © 2016年 赵洪禹. All rights reserved.
//

#import "RowView.h"

@implementation RowView

/*
 // Only override drawRect: if you perform custom drawing.
 // An empty implementation adversely affects performance during animation.
 - (void)drawRect:(CGRect)rect {
 // Drawing code
 }
 */

+ (RowView *)rowViewWithIcon:(NSString *)icon concatName:(NSString *)concatName{
    
    // 加载xib
    RowView *rowView = [[NSBundle mainBundle]  loadNibNamed:@"RowView" owner:nil options:nil][0];
    
    [rowView.icon setImage:[UIImage imageNamed:icon] forState:UIControlStateNormal];
    rowView.concatName.text = concatName;
    
    return rowView;
}
@end
