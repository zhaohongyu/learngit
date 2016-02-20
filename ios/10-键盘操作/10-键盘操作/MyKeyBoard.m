//
//  MyKeyBoard.m
//  10-键盘操作
//
//  Created by 赵洪禹 on 16/2/20.
//  Copyright © 2016年 赵洪禹. All rights reserved.
//

#import "MyKeyBoard.h"

@implementation MyKeyBoard

/*
// Only override drawRect: if you perform custom drawing.
// An empty implementation adversely affects performance during animation.
- (void)drawRect:(CGRect)rect {
    // Drawing code
}
*/

+ (MyKeyBoard *)keyBoard{
    MyKeyBoard *mykeyBoard = [[NSBundle mainBundle] loadNibNamed:@"mykeyboard" owner:nil options:nil][0];
    return mykeyBoard;
}
@end
