//
//  AddController.m
//  21-通讯录
//
//  Created by 赵洪禹 on 16/4/30.
//  Copyright © 2016年 赵洪禹. All rights reserved.
//

#import "AddController.h"
#import "Contact.h"

@interface AddController()

@property (weak, nonatomic) IBOutlet UIButton *addBtn;
@property (weak, nonatomic) IBOutlet UITextField *nameField;
@property (weak, nonatomic) IBOutlet UITextField *phoneField;
- (IBAction)clickAddBtn;

@end;

@implementation AddController

- (IBAction)clickAddBtn{
    
    // 关闭当前控制器
    [self.navigationController popViewControllerAnimated:YES];
    
    if([self.delegate respondsToSelector:@selector(addController:didClickAddBtn:)]){
        Contact *contact = [[Contact alloc] init];
        contact.name = self.nameField.text;
        contact.phone = self.phoneField.text;
        [self.delegate addController:self didClickAddBtn:contact];
    }
}


@end
