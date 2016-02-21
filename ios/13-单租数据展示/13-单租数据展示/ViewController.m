//
//  ViewController.m
//  13-单租数据展示
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
    // Do any additional setup after loading the view, typically from a nib.
    
    // 创建UITableView
    UITableView *tableView = [[UITableView alloc] initWithFrame:self.view.bounds style:UITableViewStylePlain];
    // 设置数据源
    tableView.dataSource = self;
    // 设置代理
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

- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

// 多少组
- (NSInteger)numberOfSectionsInTableView:(UITableView *)tableView{
    return 1;
}

// 每组显示多少行
- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section{
    return allShops.count;
}

// 每一行显示什么样的数据
- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath{
    
    UITableViewCell *cell = [tableView dequeueReusableCellWithIdentifier:@"c1"];
    if(nil == cell){
        cell = [[UITableViewCell alloc] initWithStyle:UITableViewCellStyleSubtitle reuseIdentifier:@"c1"];
    }
    
    Shop *shop = allShops[indexPath.row];
    
    cell.textLabel.text = shop.title;
    cell.detailTextLabel.text = shop.detail;
    cell.imageView.image = [UIImage imageNamed:shop.icon];
    return cell;
}

// 监听点击每一行
- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath{
    // 拿到要显示的内容
    Shop *shop = allShops[indexPath.row];
    
    // [self showOkayCancelAlert];
    // [self showOkayCancelActionSheet];
    // [self showSecureTextEntryAlert];
    [self myalert:shop.title indexPath:indexPath tableView:tableView];
}

- (void)myalert:(NSString *)mytext indexPath:(NSIndexPath *)indexPath tableView:(UITableView *)tableView{
    NSString *title = [NSString stringWithFormat:@"修改商品-%@",mytext];
    
    UIAlertController *alertController = [UIAlertController alertControllerWithTitle:title message:nil preferredStyle:UIAlertControllerStyleAlert];
    
    __weak typeof(self) myviewController = self;
    //这里需要防止循环引用（可以通过新建一个自己的控制器继承UIAlertController，在dealloc中打印信息，验证是否有最终释放）
    __weak typeof(alertController) weakAlert = alertController;
    
    [alertController addTextFieldWithConfigurationHandler:^(UITextField * _Nonnull textField) {
        textField.clearButtonMode = UITextFieldViewModeAlways;
        textField.text = mytext;
        // 监听文本框输入
        [[NSNotificationCenter defaultCenter] addObserver:myviewController selector:@selector(handleTextFieldTextDidChangeNotification:) name:UITextFieldTextDidChangeNotification object:textField];
    }];
    
    UIAlertAction *cancelAction = [UIAlertAction actionWithTitle:@"取消" style:UIAlertActionStyleCancel handler:^(UIAlertAction * _Nonnull action) {
        [[NSNotificationCenter defaultCenter] removeObserver:myviewController name:UITextFieldTextDidChangeNotification object:weakAlert.textFields.firstObject];
    }];
    UIAlertAction *defult = [UIAlertAction actionWithTitle:@"确定" style:UIAlertActionStyleDefault handler:^(UIAlertAction * _Nonnull action) {
        UITextField *mytextField = alertController.textFields.firstObject;
        NSString *shopTitle = mytextField.text;
        // 存入数组
        Shop *shop = allShops[indexPath.row];
        shop.title = shopTitle;
        allShops[indexPath.row] = shop;
        // 重新载入一行数据
        [tableView reloadRowsAtIndexPaths:@[indexPath] withRowAnimation:UITableViewRowAnimationBottom];
        
        [[NSNotificationCenter defaultCenter] removeObserver:myviewController name:UITextFieldTextDidChangeNotification object:weakAlert.textFields.firstObject];
    }];
    
    
    [alertController addAction:cancelAction];
    [alertController addAction:defult];
    
    // 定义全局变量保存确定按钮
    secureTextAlertAction = defult;
    
    [self presentViewController:alertController animated:YES completion:nil];
}



- (void)showOkayCancelAlert {
    NSString *title = NSLocalizedString(@"修改商品的标题", nil);
    NSString *message = NSLocalizedString(@"这里是要修改的商品名称", nil);
    NSString *cancelButtonTitle = NSLocalizedString(@"取消", nil);
    NSString *otherButtonTitle = NSLocalizedString(@"确定", nil);
    
    UIAlertController *alertController = [UIAlertController alertControllerWithTitle:title message:message preferredStyle:UIAlertControllerStyleAlert];
    
    // 创建按钮以及监听事件
    UIAlertAction *cancelAction = [UIAlertAction actionWithTitle:cancelButtonTitle style:UIAlertActionStyleCancel handler:^(UIAlertAction *action) {
        NSLog(@"你点击了取消按钮");
    }];
    
    UIAlertAction *otherAction = [UIAlertAction actionWithTitle:otherButtonTitle style:UIAlertActionStyleDefault handler:^(UIAlertAction *action) {
        NSLog(@"你点击了确定按钮");
    }];
    
    // 添加文本框
    [alertController addTextFieldWithConfigurationHandler:^(UITextField *textField) {
        // 可以在这里对textfield进行定制，例如改变背景色
        // textField.backgroundColor = [UIColor orangeColor];
    }];
    
    
    // 添加按钮
    [alertController addAction:cancelAction];
    [alertController addAction:otherAction];
    
    // 显示（AppDelegate.h里使用self.window.rootViewController代替self
    // [self.window.rootViewController presentViewController:alertController animated:YES completion:nil];
    [self presentViewController:alertController animated:YES completion:nil];
}

- (void)showSecureTextEntryAlert {
    NSString *title = NSLocalizedString(@"密码标题", nil);
    NSString *message = NSLocalizedString(@"简介信息。。。", nil);
    NSString *cancelButtonTitle = NSLocalizedString(@"取消", nil);
    NSString *otherButtonTitle = NSLocalizedString(@"确定", nil);
    
    UIAlertController *alertController = [UIAlertController alertControllerWithTitle:title message:message preferredStyle:UIAlertControllerStyleAlert];
    
    // Add the text field for the secure text entry.
    [alertController addTextFieldWithConfigurationHandler:^(UITextField *textField) {
        // Listen for changes to the text field's text so that we can toggle the current
        // action's enabled property based on whether the user has entered a sufficiently
        // secure entry.
        [[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(handleTextFieldTextDidChangeNotification:) name:UITextFieldTextDidChangeNotification object:textField];
        
        // 加密输入
        // textField.secureTextEntry = YES;
    }];
    
    // Create the actions.
    UIAlertAction *cancelAction = [UIAlertAction actionWithTitle:cancelButtonTitle style:UIAlertActionStyleCancel handler:^(UIAlertAction *action) {
        NSLog(@"你点击了取消按钮");
        
        // 在“OK”action中去掉通知：
        // Stop listening for text changed notifications.
        [[NSNotificationCenter defaultCenter] removeObserver:self name:UITextFieldTextDidChangeNotification object:alertController.textFields.firstObject];
    }];
    
    UIAlertAction *otherAction = [UIAlertAction actionWithTitle:otherButtonTitle style:UIAlertActionStyleDefault handler:^(UIAlertAction *action) {
        NSLog(@"你点击了确定按钮");
        
        // Stop listening for text changed notifications.
        [[NSNotificationCenter defaultCenter] removeObserver:self name:UITextFieldTextDidChangeNotification object:alertController.textFields.firstObject];
    }];
    
    // The text field initially has no text in the text field, so we'll disable it.
    otherAction.enabled = NO;
    
    // Hold onto the secure text alert action to toggle the enabled/disabled state when the text changed.
    secureTextAlertAction = otherAction;//定义一个全局变量来存储
    
    // Add the actions.
    [alertController addAction:cancelAction];
    [alertController addAction:otherAction];
    
    [self presentViewController:alertController animated:YES completion:nil];
}

// 当输入超过5个字符时候，使self.secureTextAlertAction ＝ YES：
- (void)handleTextFieldTextDidChangeNotification:(NSNotification *)notification {
    UITextField *textField = notification.object;
    
    // Enforce a minimum length of >= 5 characters for secure text alerts.
    secureTextAlertAction.enabled = textField.text.length >= 5;
}

// actionsheet
- (void)showOkayCancelActionSheet {
    NSString *cancelButtonTitle = NSLocalizedString(@"取消", nil);
    NSString *destructiveButtonTitle = NSLocalizedString(@"确定", nil);
    
    UIAlertController *alertController = [UIAlertController alertControllerWithTitle:nil message:nil preferredStyle:UIAlertControllerStyleActionSheet];
    
    // Create the actions.
    UIAlertAction *cancelAction = [UIAlertAction actionWithTitle:cancelButtonTitle style:UIAlertActionStyleCancel handler:^(UIAlertAction *action) {
        NSLog(@"你点击了取消按钮");
    }];
    
    UIAlertAction *destructiveAction = [UIAlertAction actionWithTitle:destructiveButtonTitle style:UIAlertActionStyleDestructive handler:^(UIAlertAction *action) {
        NSLog(@"你点击了确定按钮");
    }];
    
    // Add the actions.
    [alertController addAction:cancelAction];
    [alertController addAction:destructiveAction];
    
    [self presentViewController:alertController animated:YES completion:nil];
}

@end
