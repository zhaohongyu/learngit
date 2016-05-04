//
//  ContactController.m
//  21-通讯录
//
//  Created by 赵洪禹 on 16/3/18.
//  Copyright © 2016年 赵洪禹. All rights reserved.
//

#import "ContactController.h"
#import <AFNetworking.h>
#import "Contact.h"
#import "MJContactCell.h"
#import "AddController.h"
#import "EditController.h"

@interface ContactController () <AddControllerDelegate,EditControllerDelegate>

- (IBAction)logoutClick:(id)sender;

@property (nonatomic,strong) NSMutableArray *contacts;
@property (nonatomic,strong) NSString *storeFileName;
@property (nonatomic,strong) NSMutableArray *arrayMFromFile;

@end

@implementation ContactController


-(NSString *)storeFileName{
    if(nil == _storeFileName){
        NSString *path = NSSearchPathForDirectoriesInDomains(NSDocumentDirectory, NSUserDomainMask, YES).firstObject;
        
        NSString *fileName = [path stringByAppendingPathComponent:@"contact.plist"];
        
        NSLog(@"%@",fileName);
        
        _storeFileName =  fileName;
    }
    return _storeFileName;
}

-(NSMutableArray *)arrayMFromFile{
    if(nil == _arrayMFromFile){
        // 先读取，没有再创建
        NSMutableArray *arrayM = [NSMutableArray arrayWithContentsOfFile:self.storeFileName];
        if(nil == arrayM){
            arrayM = [NSMutableArray array];
        }
        _arrayMFromFile = arrayM;
    }
    return _arrayMFromFile;
}

-(NSArray *)contacts{
    if(nil == _contacts){
        NSMutableArray *arrContacts = self.arrayMFromFile;
        NSMutableArray *arr = [NSMutableArray array];
        for (NSDictionary *dict in arrContacts) {
            Contact *contact = [Contact contactWithDict:dict];
            [arr addObject:contact];
        }
        _contacts = arr;
    }
    return _contacts ;
}

- (void)viewDidLoad {
    [super viewDidLoad];
}

#pragma mark - Table view data source

- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section {
    return self.contacts.count;
}

-(UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath{
    // 1.创建cell
    MJContactCell *cell = [MJContactCell cellWithTableView:tableView];
    // 2.设置cell的数据
    cell.contact = self.contacts[indexPath.row];
    return cell;
}

#pragma mark - 退出登录

- (IBAction)logoutClick:(id)sender {
    UIAlertController* alert = [UIAlertController alertControllerWithTitle:@"确定要注销吗？"
                                                                   message:nil
                                                            preferredStyle:UIAlertControllerStyleActionSheet];
    UIAlertAction* defaultAction = [UIAlertAction actionWithTitle:@"确定" style:UIAlertActionStyleDefault
                                                          handler:^(UIAlertAction * action) {
                                                              [self.navigationController popViewControllerAnimated:YES];
                                                          }];
    UIAlertAction* cancelAction = [UIAlertAction actionWithTitle:@"取消" style:UIAlertActionStyleDefault
                                                         handler:^(UIAlertAction * action) {}];
    
    [alert addAction:defaultAction];
    [alert addAction:cancelAction];
    [self presentViewController:alert animated:YES completion:nil];
}

/**
 *  执行跳转之前会调用
 *  在这个方法中,目标控制器的view还没有被创建
 */
- (void)prepareForSegue:(UIStoryboardSegue *)segue sender:(id)sender
{
    id vc = segue.destinationViewController;
    
    if ([vc isKindOfClass:[AddController class]]) { // 如果是跳转到添加联系人的控制器
        // 设置下一个控制器(添加联系人的控制器)的代理
        AddController *addVc = vc;
        addVc.delegate = self;
    } else if ([vc isKindOfClass:[EditController class]]) { // 如果是跳转到查看(编辑)联系人的控制器
        EditController *editVc = vc;
        // 取得选中的那行
        NSIndexPath *path = [self.tableView indexPathForSelectedRow];
        editVc.indexPath = path;
        editVc.contact = self.contacts[path.row];
        editVc.delegate = self;
    }
}

#pragma mark - 编辑联系人点击保存按钮代理方法

-(void)editController:(EditController *)editController didClickSaveBtn:(Contact *)contact indexPath:(NSIndexPath *)indexPath{
    
    NSMutableArray *arrayM = self.arrayMFromFile;
    
    // 存储
    NSMutableDictionary *dict =[NSMutableDictionary dictionary];
    dict[@"name"] = contact.name;
    dict[@"phone"] = contact.phone;
    
    [arrayM setObject:dict atIndexedSubscript:indexPath.row];
    [arrayM writeToFile:self.storeFileName atomically:YES];
    
    self.contacts = nil;
    
    [self.tableView reloadData];
}


#pragma mark - 添加联系人按钮点击代理方法

-(void)addController:(AddController *)addController didClickAddBtn:(Contact *)contact{
    
    // 先读取，没有再创建
    NSMutableArray *arrayM = self.arrayMFromFile;
    
    // 存储
    NSMutableDictionary *dict =[NSMutableDictionary dictionary];
    dict[@"name"] = contact.name;
    dict[@"phone"] = contact.phone;
    
    [arrayM addObject:dict];
    [arrayM writeToFile:self.storeFileName atomically:YES];
    
    self.contacts = nil;
    
    [self.tableView reloadData];
}

#warning TODO 删除

@end
