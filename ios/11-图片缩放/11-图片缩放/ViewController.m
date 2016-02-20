//
//  ViewController.m
//  11-图片缩放
//
//  Created by 赵洪禹 on 16/2/20.
//  Copyright © 2016年 赵洪禹. All rights reserved.
//

#import "ViewController.h"

@interface ViewController () <UIScrollViewDelegate>
{
    UIImageView *imageView;
}
@end

@implementation ViewController

- (void)viewDidLoad {
    [super viewDidLoad];
    // Do any additional setup after loading the view, typically from a nib.
    
    // 添加UIScrollView
    UIScrollView *scrollView = [[UIScrollView alloc] initWithFrame:self.view.frame];
    
    imageView = [[UIImageView alloc] initWithImage:[UIImage imageNamed:@"big.jpg"]];
    
    // 设置滚动
    scrollView.contentSize = imageView.frame.size;
    
    // 设置代理
    scrollView.delegate = self;
    // 设置缩放最大值最小值
    scrollView.maximumZoomScale = 2.0;
    scrollView.minimumZoomScale = 0.5;
    
    [scrollView addSubview:imageView];
    [self.view addSubview:scrollView];
}

- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

- (nullable UIView *)viewForZoomingInScrollView:(UIScrollView *)scrollView{
    return imageView;
}


@end
