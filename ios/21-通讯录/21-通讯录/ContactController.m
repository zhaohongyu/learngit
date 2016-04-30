//
//  ContactController.m
//  21-通讯录
//
//  Created by 赵洪禹 on 16/3/18.
//  Copyright © 2016年 赵洪禹. All rights reserved.
//

#import "ContactController.h"
#import <AFNetworking.h>
#import "Test.h"

@interface ContactController ()

- (IBAction)logoutClick:(id)sender;

@end

@implementation ContactController

- (void)viewDidLoad {
    [super viewDidLoad];
    
    // [self upload];
    // [self testAF];
    
}

-(void) testAF{
    NSString *strUrl = @"http://zhaohongyu.ngrok.natapp.cn/json/test.php";
    
    NSMutableDictionary *mDict = [NSMutableDictionary dictionary];
    mDict[@"key1"] = @"key_val1";
    mDict[@"key2"] = @"key_val2";
    
    // 模拟发送网络请求
    
    // Get
    AFHTTPSessionManager *manager = [AFHTTPSessionManager manager];
    
    [manager
     GET:strUrl
     parameters:mDict
     progress:nil
     success:^(NSURLSessionTask *task, id responseObject) {
         Test *test = [Test testWithDict:responseObject];
         NSLog(@"response: %@", test);
     }
     failure:^(NSURLSessionTask *operation, NSError *error) {
         NSLog(@"Error: %@", error);
     }
     ];
    
    //Post
    
    [manager
     POST:strUrl
     parameters:mDict
     progress:^(NSProgress * _Nonnull uploadProgress) {
         NSLog(@" %@....%@", @"处理中。。。。",uploadProgress);
     }
     success:^(NSURLSessionDataTask * _Nonnull task, id  _Nullable responseObject) {
         Test *test = [Test testWithDict:responseObject];
         NSLog(@"%@",test);
     }
     failure:^(NSURLSessionDataTask * _Nullable task, NSError * _Nonnull error) {
         NSLog(@"Error: %@", error);
     }
     ];
    
}

- (void)upload{
    
    NSString *strUrl = @"http://zhaohongyu.ngrok.natapp.cn/json/test.php";
    
    AFHTTPSessionManager *mgr = [AFHTTPSessionManager manager];
    [mgr
     POST:strUrl
     parameters:nil
     constructingBodyWithBlock:^(id<AFMultipartFormData> formData) {
        
        // 这是最简单的版本，只要设置请求的URL、给出文件路径和name，便可将文件上传到服务器，后面有代码介绍其它方式
        [formData appendPartWithFileURL:[NSURL fileURLWithPath:@"/Users/zhaohongyu/Documents/测试文件/apple.jpg"] name:@"file" error:nil];
        
    } success:^(NSURLSessionDataTask *task, id responseObject) {
        
        // 文件上传成功来到这段代码，注意responseObject的实际类型，AFN默认解析过
        NSLog(@"------%@", responseObject);
        
    } failure:^(NSURLSessionDataTask *task, NSError *error) {
        
        NSLog(@"failure");
    }];
}


#pragma mark - Table view data source

- (NSInteger)numberOfSectionsInTableView:(UITableView *)tableView {
#warning Incomplete implementation, return the number of sections
    return 0;
}

- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section {
#warning Incomplete implementation, return the number of rows
    return 0;
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
