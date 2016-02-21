//
//  ViewController.m
//  14-单组数据展示
//
//  Created by 赵洪禹 on 16/2/21.
//  Copyright © 2016年 赵洪禹. All rights reserved.
//

#import "ViewController.h"
#import "Shop.h"

@interface ViewController () <UITableViewDataSource,UITableViewDelegate>
{
    NSMutableArray *allShops;
    UIAlertAction *secureTextAlertAction;
}
@end

@implementation ViewController

- (void)viewDidLoad {
    [super viewDidLoad];
    
    UITableView *tableView = [[UITableView alloc] initWithFrame:self.view.bounds style:UITableViewStylePlain];
    tableView.dataSource = self;
    tableView.delegate = self;
    [self.view addSubview:tableView];
    
    // 初始化数据
    allShops = [NSMutableArray array];
    for (int i = 0; i<100; i++) {
        Shop *shop = [Shop shopWithIcon:[NSString stringWithFormat:@"00%d.png",(i % 9)+1]
                                  title:[NSString stringWithFormat:@"随机商品标题-%d",i]
                                 detail:[NSString stringWithFormat:@"随机商品详情-%d",i]
                      ];
        [allShops addObject:shop];
    }
    
    // TODO 存在性能问题
    
}


#pragma mark - 实现数据源协议

- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section{
    return allShops.count;
}

- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath{
    
    UITableViewCell *cell = [tableView dequeueReusableCellWithIdentifier:@"cell1"];
    if(nil == cell){
        cell = [[UITableViewCell alloc] initWithStyle:UITableViewCellStyleSubtitle reuseIdentifier:@"cell1"];
    }
    
    Shop *shop = allShops[indexPath.row];
    
    cell.textLabel.text = shop.title;
    cell.detailTextLabel.text = shop.detail;
    cell.imageView.image = [UIImage imageNamed:shop.icon];
    return cell;
}

#pragma mark - 实例函数

#pragma mark cell监听 代理方法
- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath{
    Shop *shop = allShops[indexPath.row];
    [self myalert:shop.title indexPath:indexPath tableView:tableView];
}

#pragma mark 弹出框
- (void)myalert:(NSString *)mytext indexPath:(NSIndexPath *)indexPath tableView:(UITableView *)tableView{
    NSString *title = [NSString stringWithFormat:@"修改商品-%@",mytext];
    
    UIAlertController *alertController = [UIAlertController alertControllerWithTitle:title message:nil preferredStyle:UIAlertControllerStyleAlert];
    
    __weak typeof(self) myviewController = self;
    __weak typeof(alertController) weakAlert = alertController;
    
    [alertController addTextFieldWithConfigurationHandler:^(UITextField * _Nonnull textField) {
        textField.clearButtonMode = UITextFieldViewModeAlways;
        textField.text = mytext;
        
        [[NSNotificationCenter defaultCenter] addObserver:myviewController selector:@selector(handleTextFieldTextDidChangeNotification:) name:UITextFieldTextDidChangeNotification object:textField];
    }];
    
    UIAlertAction *cancelAction = [UIAlertAction actionWithTitle:@"取消" style:UIAlertActionStyleCancel handler:^(UIAlertAction * _Nonnull action) {
        [[NSNotificationCenter defaultCenter] removeObserver:myviewController name:UITextFieldTextDidChangeNotification object:weakAlert.textFields.firstObject];
    }];
    
    UIAlertAction *defult = [UIAlertAction actionWithTitle:@"确定" style:UIAlertActionStyleDefault handler:^(UIAlertAction * _Nonnull action) {
        UITextField *mytextField = alertController.textFields.firstObject;
        Shop *shop = allShops[indexPath.row];
        shop.title = mytextField.text;
        allShops[indexPath.row] = shop;
        [tableView reloadRowsAtIndexPaths:@[indexPath] withRowAnimation:UITableViewRowAnimationBottom];
        
        [[NSNotificationCenter defaultCenter] removeObserver:myviewController name:UITextFieldTextDidChangeNotification object:weakAlert.textFields.firstObject];
    }];
    
    
    [alertController addAction:cancelAction];
    [alertController addAction:defult];
    
    // 定义全局变量保存确定按钮
    secureTextAlertAction = defult;
    
    [self presentViewController:alertController animated:YES completion:nil];
}

#pragma mark 当输入超过5个字符时候，使self.secureTextAlertAction ＝ YES：
- (void)handleTextFieldTextDidChangeNotification:(NSNotification *)notification {
    UITextField *textField = notification.object;
    secureTextAlertAction.enabled = textField.text.length >= 5;
}

@end
