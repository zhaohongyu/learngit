//
//  ViewController.m
//  07-Tom猫
//
//  Created by 赵洪禹 on 16/2/18.
//  Copyright © 2016年 赵洪禹. All rights reserved.
//

#import "ViewController.h"

@interface ViewController ()
{
    NSDictionary *_imageDatas;
    BOOL _isRun;
}
@end

@implementation ViewController

- (void)viewDidLoad {
    [super viewDidLoad];
    // Do any additional setup after loading the view, typically from a nib.
    [self myinit];
}

- (void)myinit{
    NSBundle *bun = [NSBundle mainBundle];
    NSString *path = [bun pathForResource:@"tom" ofType:@"plist"];
    _imageDatas = [NSDictionary dictionaryWithContentsOfFile:path];
}

- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

#pragma mark - 使用图像序列帧动画

- (IBAction)btnClick:(UIButton *)sender {
    
    // 动画正在执行的时候不进行操作
    if (_image.isAnimating) {
        return;
    }
    
    // NSDate *mydate = [[NSDate alloc] init];
    // double startDate = [mydate timeIntervalSince1970];
    // NSLog(@"动画执行开始了，开始时间是%.f",startDate);
    
    // 取出当前button的title
    NSString *title = [sender titleForState:UIControlStateNormal];
    
    // 创建可变数组用来存储UIImage
    NSMutableArray *ma = [NSMutableArray array];
    
    for (int i=0; i<[_imageDatas[title] intValue]; i++) {
        NSString *imageName = [NSString stringWithFormat:@"%@_%02d.jpg",title,i];
        
        // 造成了内存泄露
        // [ma addObject:[UIImage imageNamed:imageName]];
        NSString *imagePath = [[NSBundle mainBundle] pathForResource:imageName ofType:nil];
        [ma addObject:[UIImage imageWithContentsOfFile:imagePath]];
        
    }
    
    // 将动画序列数组添加到UIImageView中
    [_image setAnimationImages:ma];
    [_image setAnimationRepeatCount:1];
    [_image setAnimationDuration:0.1*[_imageDatas[title] intValue]];
    [_image startAnimating];
    
    
    // 使用延时方式清理内存，动画放完1秒后清除内存
    CGFloat delay = _image.animationDuration + 1.0;
    [self performSelector:@selector(clearCache) withObject:nil afterDelay:delay];
}

- (void)clearCache
{
    // _image.animationImages = nil;
    [_image setAnimationImages:nil];
    
    // double endDate = [[[NSDate alloc] init] timeIntervalSince1970];
    // NSLog(@"动画执行结束了，结束时间是%.f",endDate);
}

#pragma mark - 使用关键帧动画

- (IBAction)mybtnClick2:(UIButton *)sender {
    
    // 动画正在执行的时候不进行操作
    if (_isRun) {
        return;
    }
    
    // 取出当前button的title
    NSString *title = [sender titleForState:UIControlStateNormal];
    
    // 创建CAKeyframeAnimation
    CAKeyframeAnimation *animation = [CAKeyframeAnimation animationWithKeyPath:@"contents"];
    animation.duration = [_imageDatas[title] intValue]*0.1;
    animation.delegate = self;
    
    // 存放图片的数组
    NSMutableArray *ma = [NSMutableArray array];
    for(int i = 0;i < [_imageDatas[title] intValue] ;i++) {
        NSString *imageName = [NSString stringWithFormat:@"%@_%02d.jpg",title,i];
        NSString *imagePath = [[NSBundle mainBundle] pathForResource:imageName ofType:nil];
        UIImage *img = [UIImage imageWithContentsOfFile:imagePath];
        CGImageRef cgimg = img.CGImage;
        [ma addObject:(__bridge UIImage *)cgimg];
    }
    
    animation.values = ma;
    
    [_image.layer addAnimation:animation forKey:nil];
    
}

- (void)animationDidStop:(CAAnimation *)theAnimation finished:(BOOL)flag {
    // 动画已经执行完成
    _isRun = NO;
}

- (void)animationDidStart:(CAAnimation *)anim{
    // 动画已经开始执行
    _isRun = YES;
}

@end
