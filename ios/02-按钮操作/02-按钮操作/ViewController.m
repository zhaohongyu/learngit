//
//  ViewController.m
//  02-按钮操作
//
//  Created by 赵洪禹 on 16/2/16.
//  Copyright © 2016年 赵洪禹. All rights reserved.
//

#import "ViewController.h"

#define delta 10

@interface ViewController ()

@end

@implementation ViewController

- (void)viewDidLoad {
    [super viewDidLoad];
    // Do any additional setup after loading the view, typically from a nib.
}

- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

- (void)btnClickWithBlock:(void (^)())block{
    // 设置动画
    [UIView beginAnimations:nil context:nil];
    
    // 设置动画持续时间
    [UIView setAnimationDuration:0.5];
    
    block();
    
    [UIView commitAnimations];
    
}

- (IBAction)move:(UIButton *)sender {
    
    [self btnClickWithBlock:^{
        CGRect tempRect = _image.frame;
        
        switch (sender.tag) {
            case 1:
                // 上
                tempRect.origin.y -= delta;
                break;
            case 2:
                // 左
                tempRect.origin.x -= delta;
                break;
            case 3:
                // 下
                tempRect.origin.y += delta;
                break;
            case 4:
                // 右
                tempRect.origin.x += delta;
                break;
        }
        
        _image.frame = tempRect;
    }];
    
}
- (IBAction)scale:(UIButton *)sender {
    
    [self btnClickWithBlock:^{
        CGAffineTransform atf =_image.transform;
        
        switch (sender.tag) {
            case 5:
                // 放大
                _image.transform = CGAffineTransformScale(atf, 1.2, 1.2);
                break;
            case 6:
                // 缩小
                _image.transform = CGAffineTransformScale(atf, 0.9, 0.9);
                break;
        }
    }];
    
}

- (IBAction)rotate:(UIButton *)sender {
    
    [self btnClickWithBlock:^{
        CGAffineTransform atf =_image.transform;
        
        switch (sender.tag) {
            case 7:
                // 左旋转
                _image.transform = CGAffineTransformRotate(atf, -M_PI_4);
                break;
            case 8:
                // 右旋转
                _image.transform = CGAffineTransformRotate(atf, M_PI_4);
                break;
        }
    }];
    
}

- (IBAction)reset:(UIButton *)sender {
    
    [self btnClickWithBlock:^{
        _image.transform = CGAffineTransformIdentity;
    }];
    
}
@end
