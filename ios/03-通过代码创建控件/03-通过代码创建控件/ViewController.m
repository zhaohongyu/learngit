//
//  ViewController.m
//  03-通过代码创建控件
//
//  Created by 赵洪禹 on 16/2/16.
//  Copyright © 2016年 赵洪禹. All rights reserved.
//

#import "ViewController.h"
#import "MyAlertController.h"

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
    
    // NSBundle *bun = [NSBundle mainBundle];
    // NSString *mypath = [bun pathForResource:@"google" ofType:@"png"];
    // NSLog(@"%@",mypath);
    
    // NSLog(@"%@",button);
    
    MyAlertController *myAlertController = [MyAlertController alertControllerWithTitle:@"提示" message:@"这里的山路十八弯" preferredStyle:UIAlertControllerStyleAlert];
    
    __weak typeof(myAlertController) weakAlert = myAlertController;
    __weak typeof(self) weakViewController = self;
    
    // typeof(myAlertController) weakAlert = myAlertController;
    // typeof(self) weakViewController = self;
    
    // 添加文本框
    [myAlertController addTextFieldWithConfigurationHandler:^(UITextField * _Nonnull textField) {
        
        textField.text = @"测试文本1111";
        textField.clearButtonMode = UITextFieldViewModeAlways;
        
        // 添加观察
        [[NSNotificationCenter defaultCenter] addObserver:weakViewController selector:@selector(changeText:) name:UITextFieldTextDidChangeNotification object:textField];
        
    }];
    
    [myAlertController addAction:[UIAlertAction actionWithTitle:@"取消" style:UIAlertActionStyleCancel handler:^(UIAlertAction * _Nonnull action) {
        NSLog(@"你点击了取消按钮");
        
        // 移除通知
        [[NSNotificationCenter defaultCenter] removeObserver:weakViewController name:UITextFieldTextDidChangeNotification object:weakAlert.textFields.firstObject];
    }]];
    
    [myAlertController addAction:[UIAlertAction actionWithTitle:@"确定" style:UIAlertActionStyleDefault handler:^(UIAlertAction * _Nonnull action) {
        NSLog(@"你点击了确定按钮");
        UITextField *textField = weakAlert.textFields.firstObject;
        NSLog(@"文本框内容是----%@",textField.text);
        // 移除通知
        [[NSNotificationCenter defaultCenter] removeObserver:weakViewController name:UITextFieldTextDidChangeNotification object:weakAlert.textFields.firstObject];
    }]];
    
    [self presentViewController:myAlertController animated:YES completion:nil];
    
}

- (void)changeText:(NSNotification *)notification{
    UITextField *textField = notification.object;
    
    NSLog(@"%@",textField.text);
}

@end
