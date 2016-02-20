//
//  ViewController.m
//  09-图片浏览器-UIScrollView
//
//  Created by 赵洪禹 on 16/2/20.
//  Copyright © 2016年 赵洪禹. All rights reserved.
//

#import "ViewController.h"

#define kImgCount 8

@interface ViewController () <UIScrollViewDelegate>
{
    UIScrollView *myscrollView;
    UIPageControl *pageControl;
}
@end

@implementation ViewController

- (void)viewDidLoad {
    [super viewDidLoad];
    // Do any additional setup after loading the view, typically from a nib.
    
    // 初始化UIScrollView
    myscrollView = [[UIScrollView alloc] initWithFrame:self.view.frame];
    // 设置可滚动的宽度和高度
    myscrollView.contentSize = CGSizeMake(self.view.frame.size.width * kImgCount, 0);
    // 开启分页
    myscrollView.pagingEnabled = YES;
    [self.view addSubview:myscrollView];
    
    NSString *imageName = nil;
    UIImageView *imageView = nil;
    CGFloat w = self.view.frame.size.width;
    CGFloat h = self.view.frame.size.height;
    // TODO 存在性能问题
    for (int i = 0 ; i< kImgCount; i++) {
        imageName = [NSString stringWithFormat:@"0%d.jpg",i+1];
        imageView = [[UIImageView alloc] initWithImage:[UIImage imageNamed:imageName]];
        imageView.frame = CGRectMake( i * w, self.view.frame.origin.y, w, h);
        [myscrollView addSubview:imageView];
    }
    
    // 设置代理
    myscrollView.delegate = self;
    
    // 添加分页控件
    pageControl = [[UIPageControl alloc] init];
    // 设置尺寸
    pageControl.center = CGPointMake(w / 2, h - 10 );
    pageControl.bounds = CGRectMake(0, 0, 150, 200);
    pageControl.numberOfPages = kImgCount;
    pageControl.pageIndicatorTintColor = [UIColor redColor];
    pageControl.currentPageIndicatorTintColor = [UIColor blueColor];
    pageControl.enabled = NO;
    [self.view addSubview:pageControl];
    
    
}

// 监听滚动
- (void)scrollViewDidScroll:(UIScrollView *)scrollView{
    pageControl.currentPage = myscrollView.contentOffset.x / myscrollView.frame.size.width;
}

- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

@end
