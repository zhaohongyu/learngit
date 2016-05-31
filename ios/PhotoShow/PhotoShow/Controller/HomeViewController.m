//
//  HomeViewController.m
//  PhotoShow
//
//  Created by 沈健 on 16/5/25.
//  Copyright © 2016年 shenjian. All rights reserved.
//

#import "HomeViewController.h"

@interface HomeViewController ()

@end

@implementation HomeViewController

- (void)viewDidLoad {
    [super viewDidLoad];
    self.automaticallyAdjustsScrollViewInsets = NO;
    UIScrollView *scrollView = [[UIScrollView alloc]init];
    scrollView.frame = CGRectMake(0, 100, self.view.frame.size.width, 50);
    scrollView.contentSize = CGSizeMake(600, 0);
    scrollView.backgroundColor = [UIColor greenColor];
    scrollView.bounces = NO;
    [self.view addSubview:scrollView];
    
    CGFloat btnW = 90;
    CGFloat btnY = 0;
    CGFloat btnH = 40;
    CGFloat btnX = 90 * 1;

    UIButton *btn = [UIButton buttonWithType:UIButtonTypeCustom];
    btn.frame = CGRectMake(btnX, btnY, btnW, btnH);
    [btn setTitle:@"meinv~~~" forState:UIControlStateNormal];
    [btn setTitleColor:[UIColor blueColor] forState:UIControlStateNormal];
    btn.titleLabel.font = [UIFont systemFontOfSize:13];

    [scrollView addSubview:btn];
}

- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

@end
