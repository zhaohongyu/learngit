//
//  ViewController.m
//  21-通讯录
//
//  Created by 赵洪禹 on 16/3/18.
//  Copyright © 2016年 赵洪禹. All rights reserved.
//

#import "LoginController.h"
#import "ContactController.h"
#import "MBProgressHUD+HY.h"

@interface LoginController ()
@property (weak, nonatomic) IBOutlet UITextField *usernameField;
@property (weak, nonatomic) IBOutlet UITextField *pwdField;
@property (weak, nonatomic) IBOutlet UISwitch *rmbSwitch;
@property (weak, nonatomic) IBOutlet UISwitch *autologinSwitch;
@property (weak, nonatomic) IBOutlet UIButton *submitBtn;
- (IBAction)rmbChange:(id)sender;
- (IBAction)autologinChange:(id)sender;
- (IBAction)submit:(id)sender;
@end

@implementation LoginController

- (void)viewDidLoad {
    [super viewDidLoad];
    
    // 添加文本事件监听
    [[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(textChange) name:UITextFieldTextDidChangeNotification object:self.usernameField];
    [[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(textChange) name:UITextFieldTextDidChangeNotification object:self.pwdField];
    
}

-(void)dealloc{
    // 移除通知事件监听
    [[NSNotificationCenter defaultCenter] removeObserver:self];
}

- (void)textChange {
    // 根据文本框内容决定按钮是否能点击
    self.submitBtn.enabled = self.usernameField.text.length && self.pwdField.text.length;
}

- (IBAction)rmbChange:(id)sender {
    // 点击取消记住密码的时候取消自动登录
    if (!self.rmbSwitch.isOn){
        self.autologinSwitch.on = self.rmbSwitch.isOn;
    }
}

- (IBAction)autologinChange:(id)sender {
    // 点击自动登录的时候自动勾选记住密码
    if (!self.autologinSwitch.isOn) return;
    self.rmbSwitch.on = self.autologinSwitch.isOn;
}

- (IBAction)submit:(id)sender {
    if (![self.usernameField.text isEqualToString:@"zhaohongyu"]) {
        // 用户名错误
        [MBProgressHUD showError:@"用户名错误"];
        return;
    }
    if (![self.pwdField.text isEqualToString:@"123456"]) {
        // 密码错误
        [MBProgressHUD showError:@"密码错误"];
        return;
    }
    
    [MBProgressHUD showMessage:@"正在帮你登录中...." toView:self.view];
    
    dispatch_after(dispatch_time(DISPATCH_TIME_NOW, (int64_t)(1.0 * NSEC_PER_SEC)), dispatch_get_main_queue(), ^{
        // 移除遮盖
        [MBProgressHUD hideHUDForView:self.view];
        
        // 跳转 -- 执行login2contact这个segue
        [self performSegueWithIdentifier:@"login2contact" sender:nil];
    });
}
@end
