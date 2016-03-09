//
//  GoodsFooterView.m
//  20-团购列表2
//
//  Created by 赵洪禹 on 16/3/10.
//  Copyright © 2016年 赵洪禹. All rights reserved.
//

#import "GoodsFooterView.h"

@implementation GoodsFooterView

- (IBAction)loadBtnClick:(id)sender {
    // 1.隐藏加载按钮
    self.loadMore.hidden = YES;
    
    // 2.显示"正在加载"
    self.loadView.hidden = NO;
    
    // 3.显示更多的数据
    // GCD
    dispatch_after(dispatch_time(DISPATCH_TIME_NOW, (int64_t)(1.0 * NSEC_PER_SEC)), dispatch_get_main_queue(), ^{ // 1.0s后执行block里面的代码
        // 通知代理
        if ([self.delegate respondsToSelector:@selector(goodsFooterViewDidClickedLoadBtn:)]) {
            [self.delegate goodsFooterViewDidClickedLoadBtn:self];
        }
        
        // 4.显示加载按钮
        self.loadMore.hidden = NO;
        
        // 5.隐藏"正在加载"
        self.loadView.hidden = YES;
    });
}

+(instancetype)goodsFooterView{
    return [[[NSBundle mainBundle] loadNibNamed:@"GoodsFooterView" owner:nil options:nil] lastObject];
}

@end
