//
//  GoodsHeaderView.m
//  20-团购列表2
//
//  Created by 赵洪禹 on 16/3/10.
//  Copyright © 2016年 赵洪禹. All rights reserved.
//

#import "GoodsHeaderView.h"
#import "Banner.h"

@interface GoodsHeaderView()

@property (nonatomic, strong) NSArray *bannerData;

@end

@implementation GoodsHeaderView

-(NSArray *)bannerData{
    if (nil == _bannerData) {
        NSArray *bannerArr = [NSArray arrayWithContentsOfFile:[[NSBundle mainBundle] pathForResource:@"banner.plist" ofType:nil]];
        NSMutableArray *arr = [NSMutableArray array];
        for (NSDictionary *dict in bannerArr) {
            Banner *banner = [Banner bannerWithDict:dict];
            [arr addObject:banner];
        }
        _bannerData = arr;
    }
    return _bannerData;
}

+(instancetype)goodsHeaderView{
    return [[[NSBundle mainBundle] loadNibNamed:@"GoodsHeaderView" owner:nil options:nil] lastObject];
}

-(void)awakeFromNib{
    [self addBanner];
}

- (void)addBanner{
    CGFloat w = _banner.frame.size.width;
    CGFloat h = _banner.frame.size.height;
    NSInteger count = self.bannerData.count;
    
    // 设置可滚动的宽度和高度
    _banner.contentSize = CGSizeMake(w * count, 0);
    // 开启分页
    _banner.pagingEnabled = YES;
    
    UIImageView *imageView = nil;
    // TODO 存在性能问题
    for (int i = 0 ; i< count; i++) {
        Banner *banner = self.bannerData[i];
        imageView = [[UIImageView alloc] initWithImage:[UIImage imageNamed:banner.icon]];
        imageView.frame = CGRectMake( i * w, _banner.frame.origin.y, w, h);
        [_banner addSubview:imageView];
    }
    
    // 设置代理
    _banner.delegate = self;
    
    // 添加分页控件
    _pageControl.numberOfPages = count;
    // _pageControl.pageIndicatorTintColor = [UIColor redColor];
    // _pageControl.currentPageIndicatorTintColor = [UIColor blueColor];
    // 设置不能点击圆点滚动
    // _pageControl.enabled = NO;
    // 添加圆点点击事件
    [_pageControl addTarget:self action:@selector(pageTurn:) forControlEvents:UIControlEventValueChanged];
}

// 监听滚动
- (void)scrollViewDidScroll:(UIScrollView *)scrollView{
    _pageControl.currentPage = _banner.contentOffset.x / _banner.frame.size.width;
}

- (void)pageTurn:(UIPageControl *)pageControl
{
    _banner.contentOffset = CGPointMake(_banner.frame.size.width * pageControl.currentPage, _banner.contentOffset.y);
}

@end
