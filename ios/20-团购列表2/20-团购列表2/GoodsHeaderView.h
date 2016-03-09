//
//  GoodsHeaderView.h
//  20-团购列表2
//
//  Created by 赵洪禹 on 16/3/10.
//  Copyright © 2016年 赵洪禹. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface GoodsHeaderView : UIView <UIScrollViewDelegate>

@property (weak, nonatomic) IBOutlet UIScrollView *banner;
@property (weak, nonatomic) IBOutlet UIPageControl *pageControl;

+(instancetype)goodsHeaderView;

@end
