//
//  AddController.m
//  21-通讯录
//
//  Created by 赵洪禹 on 16/4/30.
//  Copyright © 2016年 赵洪禹. All rights reserved.
//

#import "AddController.h"

@interface AddController()

@property (weak, nonatomic) IBOutlet UIButton *addBtn;
@property (weak, nonatomic) IBOutlet UITextField *nameField;
@property (weak, nonatomic) IBOutlet UITextField *phoneField;
- (IBAction)clickAddBtn;

@end;

@implementation AddController

- (IBAction)clickAddBtn{
    
    // 存储
    NSMutableDictionary *dict =[NSMutableDictionary dictionary];
    
    dict[@"name"] = self.nameField.text;
    dict[@"phone"] = self.phoneField.text;
    
    NSString *path = NSSearchPathForDirectoriesInDomains(NSDocumentDirectory, NSUserDomainMask, YES).firstObject;
    
    NSString *fileName = [path stringByAppendingPathComponent:@"contact.plist"];
    
    
    
    // 先读取，没有再创建
    NSMutableArray *array = [NSMutableArray arrayWithContentsOfFile:fileName];
    if(nil == array){
        array = [NSMutableArray array];
    }
    
    [array addObject:dict];
    
    [array writeToFile:fileName atomically:YES];
    
    // 关闭当前控制器
    [self.navigationController popViewControllerAnimated:YES];
    
}
@end
