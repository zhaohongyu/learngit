//
//  EditController.m
//  21-通讯录
//
//  Created by 赵洪禹 on 16/5/4.
//  Copyright © 2016年 赵洪禹. All rights reserved.
//

#import "EditController.h"
#import "Contact.h"

@interface EditController ()

@property (weak, nonatomic) IBOutlet UITextField *nameField;
@property (weak, nonatomic) IBOutlet UIButton *saveBtn;
@property (weak, nonatomic) IBOutlet UITextField *phoneField;
- (IBAction)edit:(UIBarButtonItem *)item;
- (IBAction)save;


@end

@implementation EditController
- (void)viewDidLoad
{
    [super viewDidLoad];
    
    // 设置数据
    self.nameField.text = self.contact.name;
    self.phoneField.text = self.contact.phone;
    
    // 监听通知
    [[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(textChange) name:UITextFieldTextDidChangeNotification object:self.nameField];
    [[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(textChange) name:UITextFieldTextDidChangeNotification object:self.phoneField];
}

- (void)dealloc
{
    [[NSNotificationCenter defaultCenter] removeObserver:self];
}

/**
 *  文本框的文字发生改变的时候调用
 */
- (void)textChange
{
    self.saveBtn.enabled = (self.nameField.text.length && self.phoneField.text.length);
}

- (IBAction)edit:(UIBarButtonItem *)item {
    if (self.nameField.enabled) { // 点击的是"取消"
        self.nameField.enabled = NO;
        self.phoneField.enabled = NO;
        [self.view endEditing:YES];
        self.saveBtn.hidden = YES;
        
        item.title = @"编辑";
        
        // 还原回原来的数据
        self.nameField.text = self.contact.name;
        self.phoneField.text = self.contact.phone;
    } else { // 点击的是"编辑"
        self.nameField.enabled = YES;
        self.phoneField.enabled = YES;
        [self.phoneField becomeFirstResponder];
        self.saveBtn.hidden = NO;
        
        item.title = @"取消";
    }
}

/**
 *  保存
 */
- (IBAction)save {
    // 1.关闭页面
    [self.navigationController popViewControllerAnimated:YES];
    
    // 2.通知代理
    if ([self.delegate respondsToSelector:@selector(editController:didClickSaveBtn:indexPath:)]) {
        // 更新模型数据
        self.contact.name = self.nameField.text;
        self.contact.phone = self.phoneField.text;
        [self.delegate editController:self didClickSaveBtn:self.contact indexPath:self.indexPath];
    }
}
@end