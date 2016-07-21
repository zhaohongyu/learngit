//
//  HomeViewController.m
//  PhotoShow
//
//  Created by 沈健 on 16/7/3.
//  Copyright © 2016年 shenjian. All rights reserved.
//

#import "HomeViewController.h"
#import "PSSexyWomanListViewController.h"
#import "PSJapanBeautListViewController.h"
#import "PSSockLegListViewController.h"
#import "PSWomanPhotoListViewController.h"
#import "PSBeautPhotoListViewController.h"
#import "PSPureBeautyListViewController.h"
#import "PSSexyModelListViewController.h"

#define titleW 170
#define titlesNum 7
#define titleH 24

@interface HomeViewController ()<UIScrollViewDelegate>
@property (nonatomic, strong) UIView *navBarView;
@property (nonatomic, strong) UIScrollView *titlesScrollView;
@property (nonatomic, strong) UIScrollView *mainScrollView;
@property (nonatomic, strong) UIPageControl *pageControl;
@property (nonatomic, strong) NSArray *titlesArray;
@property (nonatomic, strong) NSMutableArray *labelsArray;
@property (nonatomic, assign) NSUInteger lastIndex;

@property (nonatomic, strong) PSSexyWomanListViewController *sexyWomanVC;
@property (nonatomic, strong) PSJapanBeautListViewController *japanBeautVC;
@property (nonatomic, strong) PSSockLegListViewController *sockLegVC;
@property (nonatomic, strong) PSWomanPhotoListViewController *womanPhotoVC;
@property (nonatomic, strong) PSBeautPhotoListViewController *beautPhotoVC;
@property (nonatomic, strong) PSPureBeautyListViewController *pureBeautVC;
@property (nonatomic, strong) PSSexyModelListViewController *sexyModelVC;

@end

@implementation HomeViewController

#pragma mark -- lazy
- (UIView *)navBarView{
    if (!_navBarView) {
        _navBarView = [[UIView alloc]initWithFrame:CGRectMake(self.view.width-titleW/2, 20, titleW, 44)];
    }
    return _navBarView;
}

- (UIScrollView *)titlesScrollView{
    if (!_titlesScrollView) {
        _titlesScrollView = [[UIScrollView alloc]initWithFrame:CGRectMake(0, 0, titleW, 24)];
        _titlesScrollView.delegate = self;
        _titlesScrollView.showsVerticalScrollIndicator = NO;
        _titlesScrollView.showsHorizontalScrollIndicator = NO;
        _titlesScrollView.contentSize = CGSizeMake(titleW * titlesNum, 0);
        _titlesScrollView.bounces = NO;
        _titlesScrollView.pagingEnabled = YES;
        _titlesScrollView.userInteractionEnabled = NO;
    }
    return _titlesScrollView;
}

- (UIScrollView *)mainScrollView{
    if (!_mainScrollView) {
        _mainScrollView = [[UIScrollView alloc]initWithFrame:CGRectMake(0, 64, self.view.width, self.view.height - 64)];
        _mainScrollView.delegate = self;
        _mainScrollView.showsVerticalScrollIndicator = NO;
        _mainScrollView.showsHorizontalScrollIndicator = NO;
        _mainScrollView.contentSize = CGSizeMake(self.view.width * titlesNum, 0);
        _mainScrollView.bounces = NO;
        _mainScrollView.pagingEnabled = YES;
    }
    return _mainScrollView;
}

- (UIPageControl *)pageControl{
    if (!_pageControl) {
        _pageControl = [[UIPageControl alloc]initWithFrame:CGRectMake(0, 24, titleW, 20)];
        _pageControl.numberOfPages = titlesNum;
        _pageControl.currentPage = 0;
        _pageControl.pageIndicatorTintColor = [UIColor lightGrayColor];
        _pageControl.currentPageIndicatorTintColor = [UIColor blackColor];
        _pageControl.userInteractionEnabled = NO;
    }
    return _pageControl;
}

- (NSArray *)titlesArray{
    if (!_titlesArray) {
        _titlesArray = [NSArray arrayWithObjects:@"性感美女", @"韩日美女", @"丝袜美腿", @"美女照片", @"美女写真", @"清纯美女", @"性感车模",nil];
    }
    return _titlesArray;
}

- (NSMutableArray *)labelsArray{
    if (!_labelsArray) {
        _labelsArray = [NSMutableArray arrayWithCapacity:titlesNum];
    }
    return _labelsArray;
}



- (PSSexyWomanListViewController *)sexyWomanVC{
    if (!_sexyWomanVC) {
        _sexyWomanVC = [[PSSexyWomanListViewController alloc]init];
        _sexyWomanVC.title = @"性感美女";
    }
    return _sexyWomanVC;
}

- (PSJapanBeautListViewController *)japanBeautVC{
    if (!_japanBeautVC) {
        _japanBeautVC = [[PSJapanBeautListViewController alloc]init];
        _japanBeautVC.title = @"日韩美女";
    }
    return _japanBeautVC;
}

- (PSSockLegListViewController *)sockLegVC{
    if (!_sockLegVC) {
        _sockLegVC = [[PSSockLegListViewController alloc]init];
        _sockLegVC.title = @"丝袜美腿";
    }
    return _sockLegVC;
}

- (PSWomanPhotoListViewController *)womanPhotoVC{
    if (!_womanPhotoVC) {
        _womanPhotoVC = [[PSWomanPhotoListViewController alloc]init];
        _womanPhotoVC.title = @"美女照片";
    }
    return _womanPhotoVC;
}

- (PSBeautPhotoListViewController *)beautPhotoVC{
    if (!_beautPhotoVC) {
        _beautPhotoVC = [[PSBeautPhotoListViewController alloc]init];
        _beautPhotoVC.title = @"美女写真";
    }
    return _beautPhotoVC;
}

- (PSPureBeautyListViewController *)pureBeautVC{
    if (!_pureBeautVC) {
        _pureBeautVC = [[PSPureBeautyListViewController alloc]init];
        _pureBeautVC.title = @"清纯美女";
    }
    return _pureBeautVC;
}

- (PSSexyModelListViewController *)sexyModelVC{
    if (!_sexyModelVC) {
        _sexyModelVC = [[PSSexyModelListViewController alloc]init];
        _sexyModelVC.title = @"性感车模";
    }
    return _sexyModelVC;
}

#pragma mark -- lifecycle
- (void)viewDidLoad {
    [super viewDidLoad];
    self.automaticallyAdjustsScrollViewInsets = NO;
    self.navigationItem.titleView = self.navBarView;
    
    [self.navBarView addSubview:self.titlesScrollView];
    [self.navBarView addSubview:self.pageControl];
    [self.view addSubview:self.mainScrollView];
    
    [self addTitles];
    
    [self addChildViewController:self.sexyWomanVC];
    [self addChildViewController:self.japanBeautVC];
    [self addChildViewController:self.sockLegVC];
    [self addChildViewController:self.womanPhotoVC];
    [self addChildViewController:self.beautPhotoVC];
    [self addChildViewController:self.pureBeautVC];
    [self addChildViewController:self.sexyModelVC];
    
    self.womanPhotoVC.view.frame = self.mainScrollView.bounds;
    [self.mainScrollView addSubview:self.sexyWomanVC.view];
}

- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
}

- (void)addTitles{
    [self.titlesArray enumerateObjectsUsingBlock:^(id  _Nonnull obj, NSUInteger idx, BOOL * _Nonnull stop) {
        CGFloat labelX = titleW * idx;
        UILabel *label = [[UILabel alloc]init];
        label.frame = CGRectMake(labelX, 0, titleW, titleH);
        label.font = [UIFont systemFontOfSize:13];
        label.backgroundColor = [UIColor clearColor];
        label.textColor = [UIColor blackColor];
        label.textAlignment = NSTextAlignmentCenter;
        label.text = obj;
        label.alpha = 0;
        [self.titlesScrollView addSubview:label];
        if (self.titlesScrollView.subviews.count == 1) {
            label.alpha = 1;
        }
        [self.labelsArray addObject:label];
    }];
}

- (void)labelAnimationWithCurrentIndex:(NSUInteger)currentIndex offsetX:(CGFloat)offsetx{
    UILabel *currentLabel = [self.labelsArray objectAtIndex:currentIndex];
    CGFloat currentAlpha;
    
    if (currentIndex == self.lastIndex) {
        CGFloat temp1 = fabs(offsetx - self.view.width * self.lastIndex) / (self.view.width * 0.5);
        currentAlpha = 1 - temp1;
    }else{
        CGFloat temp1 = (fabs(offsetx - self.view.width * self.lastIndex) - self.view.width*0.5)/ (self.view.width * 0.5);
        currentAlpha = temp1;
    }
    if (currentAlpha > 1) {
        currentAlpha = 1;
    }
    currentLabel.alpha = currentAlpha;
}

#pragma mark -- UIScrollViewDelegate
- (void)scrollViewDidScroll:(UIScrollView *)scrollView{
    CGFloat littleX = titleW / self.view.width;
    
    self.titlesScrollView.delegate = nil;
    self.titlesScrollView.contentOffset = CGPointMake(scrollView.contentOffset.x *littleX, scrollView.contentOffset.y);
    self.titlesScrollView.delegate = self;
    
    NSUInteger currentIndex = (int)(scrollView.contentOffset.x + self.view.width*0.5)/self.view.width;
    [self labelAnimationWithCurrentIndex:currentIndex offsetX:scrollView.contentOffset.x];
    self.pageControl.currentPage = currentIndex;
}

- (void)scrollViewDidEndDecelerating:(UIScrollView *)scrollView{
    CGFloat offsetX = scrollView.contentOffset.x;
    self.lastIndex = (int)(offsetX + self.view.width*0.5)/self.view.width;
    
    CGFloat subWidth = self.mainScrollView.frame.size.width;
    CGFloat subHeight = self.mainScrollView.frame.size.height;
    UIViewController * VC =  self.childViewControllers[self.lastIndex];
    VC.view.frame = CGRectMake(subWidth * self.lastIndex, 0, subWidth, subHeight);
    [self.mainScrollView addSubview:VC.view];
    NSLog(@"VC: %@ --- VC.title: %@",VC,VC.title );
    NSLog(@"self.mainScrollView.subview --- %d",self.mainScrollView.subviews.count);
}

@end
