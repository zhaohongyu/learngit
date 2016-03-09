//
//  GoodsFooterView.h
//  20-团购列表2
//
//  Created by 赵洪禹 on 16/3/10.
//  Copyright © 2016年 赵洪禹. All rights reserved.
//

#import <UIKit/UIKit.h>

/**
 1.协议名称:  类名 + Delegate
 2.代理方法普遍都是@optional
 3.方法名称以类名开头+对应名字
 4.把自己当做参数传进去
 */
@class GoodsFooterView;

@protocol GoodsFooterViewDelegate <NSObject>

@optional
-(void)goodsFooterViewDidClickedLoadBtn:(GoodsFooterView *) goodsFooterView;
@end


@interface GoodsFooterView : UIView

@property (nonatomic, weak) id<GoodsFooterViewDelegate> delegate;
@property (weak, nonatomic) IBOutlet UIButton *loadMore;
@property (weak, nonatomic) IBOutlet UIView *loadView;
- (IBAction)loadBtnClick:(id)sender;
+(instancetype)goodsFooterView;
@end
