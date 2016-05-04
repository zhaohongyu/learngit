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
- (IBAction)submit;
@end

@implementation LoginController

- (void)viewDidLoad {
    [super viewDidLoad];
    
    // 添加文本事件监听
    [[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(textChange) name:UITextFieldTextDidChangeNotification object:self.usernameField];
    [[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(textChange) name:UITextFieldTextDidChangeNotification object:self.pwdField];
    
    [self myinit];
    
}

-(void)myinit{
    // 读取用户的按钮状态,如果选择了自动登录，记住密码等记得回显
    
    NSUserDefaults *userDefaults = [NSUserDefaults standardUserDefaults];
    self.rmbSwitch.on = [userDefaults boolForKey:@"rmbSwitch"];
    self.autologinSwitch.on =[userDefaults boolForKey:@"autologinSwitch"];
    
    if(self.autologinSwitch.isOn && self.rmbSwitch.isOn){
        [self submit];
    }
}

-(void)dealloc{
    // 移除通知事件监听
    [[NSNotificationCenter defaultCenter] removeObserver:self];
}

- (void)textChange {
    // 根据文本框内容决定按钮是否能点击
    self.submitBtn.enabled = self.usernameField.text.length && self.pwdField.text.length;
}

#pragma mark - switch按钮change事件

- (IBAction)rmbChange:(id)sender {
    
    // 记住密码,存储
    [self storeState4rmbSwitch];
    
    // 点击取消记住密码的时候取消自动登录
    if (!self.rmbSwitch.isOn){
        self.autologinSwitch.on = self.rmbSwitch.isOn;
        [self storeState4autologinSwitch];
    }
    
}

- (IBAction)autologinChange:(id)sender {
    
    [self storeState4autologinSwitch];
    
    // 点击自动登录的时候自动勾选记住密码
    if (!self.autologinSwitch.isOn) return;
    self.rmbSwitch.on = self.autologinSwitch.isOn;
    [self storeState4rmbSwitch];
}

#pragma mark - 提交登录

- (IBAction)submit {
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

/**
 *  执行segue后,跳转之前会调用这个方法
 *  一般在这里给下一个控制器传递数据
 */
- (void)prepareForSegue:(UIStoryboardSegue *)segue sender:(id)sender
{
    // 1.取得目标控制器(联系人列表控制器)
    UIViewController *contactVc = segue.destinationViewController;
    
    // 2.设置标题
    contactVc.title = [NSString stringWithFormat:@"%@的联系人列表", self.usernameField.text];
    // contactVc.title 等价于 contactVc.navigationItem.title
    // contactVc.navigationItem.title = [NSString stringWithFormat:@"%@的联系人列表", self.accountField.text];
}

#pragma mark - 存储按钮状态

-(void)storeState4rmbSwitch{
    
    //    NSString *path = [[NSBundle mainBundle] bundlePath];
    //    NSLog(@"%@", path);
    //
    //    path = NSSearchPathForDirectoriesInDomains(NSDocumentDirectory, NSUserDomainMask, YES).firstObject;
    //    NSLog(@"%@", path);
    //
    //    path = NSSearchPathForDirectoriesInDomains(NSCachesDirectory, NSUserDomainMask, YES).firstObject;
    //    NSLog(@"%@", path);
    //
    //    path = NSTemporaryDirectory();
    //    NSLog(@"%@", path);
    
    NSUserDefaults *userDefults = [NSUserDefaults standardUserDefaults];
    [userDefults setBool:self.rmbSwitch.isOn forKey:@"rmbSwitch"];
    [userDefults synchronize];
}

-(void)storeState4autologinSwitch{
    NSUserDefaults *userDefults = [NSUserDefaults standardUserDefaults];
    [userDefults setBool:self.autologinSwitch.isOn forKey:@"autologinSwitch"];
    [userDefults synchronize];
}

@end
