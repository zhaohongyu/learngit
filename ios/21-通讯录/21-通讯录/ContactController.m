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

@interface ContactController ()

- (IBAction)logoutClick:(id)sender;

@property (nonatomic,strong) NSArray *contacts;

@end

@implementation ContactController


-(NSString *)getStoreFileName{
    NSString *path = NSSearchPathForDirectoriesInDomains(NSDocumentDirectory, NSUserDomainMask, YES).firstObject;
    
    NSString *fileName = [path stringByAppendingPathComponent:@"contact.plist"];
    
    return fileName;
}


-(NSArray *)contacts{
    // 从文件中读取对象
    NSString *fileName = [self getStoreFileName];
    
    NSMutableArray *arrContacts = [NSMutableArray  arrayWithContentsOfFile:fileName];
    NSMutableArray *arr = [NSMutableArray array];
    for (NSDictionary *dict in arrContacts) {
        Contact *contact = [Contact contactWithDict:dict];
        [arr addObject:contact];
    }
    
    return _contacts = arr;
}

- (void)viewDidLoad {
    [super viewDidLoad];
}

-(void)viewWillAppear:(BOOL)animated{
    [self.tableView reloadData];
}

#pragma mark - Table view data source

- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section {
    return self.contacts.count;
}

-(UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath{
    
    static NSString *ID = @"contact";
    UITableViewCell *cell = [tableView dequeueReusableCellWithIdentifier:ID];
    
    if(nil == cell){
        cell = [[UITableViewCell alloc] initWithStyle:UITableViewCellStyleSubtitle reuseIdentifier:ID];
        Contact *contact = self.contacts[indexPath.row];
        
        cell.textLabel.text = contact.name;
        cell.detailTextLabel.text = contact.phone;
    }
    
    return  cell;
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
@end
