//
//  ViewController.m
//  10-键盘操作
//
//  Created by 赵洪禹 on 16/2/20.
//  Copyright © 2016年 赵洪禹. All rights reserved.
//

#import "ViewController.h"
#import "MyKeyBoard.h"

@interface ViewController ()
{
    MyKeyBoard *mykeyBoard;
}
@end

@implementation ViewController

- (void)viewDidLoad {
    [super viewDidLoad];
    // Do any additional setup after loading the view, typically from a nib.
    
    mykeyBoard = [MyKeyBoard keyBoard];
    // 添加监听事件
    
    // 添加操作项到键盘上
    _name.inputAccessoryView = mykeyBoard;
    _mobile.inputAccessoryView = mykeyBoard;
    _email.inputAccessoryView = mykeyBoard;
}

- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

- (IBAction)exitKeyBoard:(UIButton *)sender {
    // NSLog(@"退出键盘");
    [self.view endEditing:YES];
}

- (void)exitKeyBoard4ToolBar:(UIBarButtonItem *)sender {
    // NSLog(@"退出键盘");
    [self.view endEditing:YES];
}

@end
