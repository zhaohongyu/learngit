//
//  ViewController.m
//  03-通过代码创建控件
//
//  Created by 赵洪禹 on 16/2/16.
//  Copyright © 2016年 赵洪禹. All rights reserved.
//

#import "ViewController.h"


@interface ViewController ()

@end

@implementation ViewController

- (void)viewDidLoad {
    [super viewDidLoad];
    // Do any additional setup after loading the view, typically from a nib.
    
    // 添加按钮并监听点击事件
    [self addBtn1];
    // 添加文本框
    [self addText1];
    
}

- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

- (void)addBtn1{
    
    // 创建UIButton
    UIButton *btn = [[UIButton alloc] init];
    // 设置btn属性
    
    // 设置普通状态下的按钮背景图片和文字
    [btn setBackgroundImage:[UIImage imageNamed:@"google.png"] forState:UIControlStateNormal];
    [btn setTitle:@"干啥呢" forState:UIControlStateNormal];
    [btn setTitleColor:[UIColor redColor] forState:UIControlStateNormal];
    
    // 设置高亮状态下的按钮背景图片和文字
    [btn setBackgroundImage:[UIImage imageNamed:@"apple.jpg"] forState:UIControlStateHighlighted];
    [btn setTitle:@"呆着呢" forState:UIControlStateHighlighted];
    [btn setTitleColor:[UIColor blueColor] forState:UIControlStateHighlighted];
    
    btn.frame = CGRectMake(0, 0, 300, 300);
    
    // 添加监听事件
    [btn addTarget:self action:@selector(btnClick:) forControlEvents:UIControlEventTouchUpInside];
    
    // 添加到UIView中
    [self.view addSubview:btn];
    
}

- (void)addText1{
    
    UITextField *field = [[UITextField alloc] init];
    // 设置field属性
    field.frame = CGRectMake(100, 100, 100, 50);
    // 注意：设置center属性会覆盖frame.origin中的值
    field.center = CGPointMake(self.view.frame.size.width*0.5, self.view.frame.size.height*0.5);
    field.placeholder = @"请输入";
    field.backgroundColor = [UIColor whiteColor];
    
    [self.view addSubview:field];
    
}

- (void)btnClick:(UIButton *)button{
    
    NSBundle *bun = [NSBundle mainBundle];
    NSString *mypath = [bun pathForResource:@"google" ofType:@"png"];
    NSLog(@"%@",mypath);
    
    NSLog(@"%@",button);
}

@end
