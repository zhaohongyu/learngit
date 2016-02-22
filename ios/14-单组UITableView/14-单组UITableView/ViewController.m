//
//  ViewController.m
//  14-单组数据展示
//
//  Created by 赵洪禹 on 16/2/21.
//  Copyright © 2016年 赵洪禹. All rights reserved.
//
/*
 [viewWillAppear」当收到视图在视窗将可见时的通知会呼叫的方法
 
 「viewDidAppear」当收到视图在视窗已可见时的通知会呼叫的方法
 
 「viewWillDisappear」当收到视图将去除、被覆盖或隐藏于视窗时的通知会呼叫的方法
 
 「viewDidDisappear」当收到视图已去除、被覆盖或隐藏于视窗时的通知会呼叫的方法
 
 「didReceiveMemoryWarning」收到系统传来的内存警告通知后会执行的方法
 
 「shouldAutorotateToInterfaceOrientation」是否支持不同方向的旋转视图
 
 「willAnimateRotationToInterfaceOrientation」在进行旋转视图前的会执行的方法（用于调整旋转视图之用）
 
 当一个视图控制器被创建，并在屏幕上显示的时候。 代码的执行顺序
 
 1、 alloc                                   创建对象，分配空间
 
 2、init (initWithNibName) 初始化对象，初始化数据
 
 3、loadView                          从nib载入视图 ，通常这一步不需要去干涉。除非你没有使用xib文件创建视图
 
 4、viewDidLoad                   载入完成，可以进行自定义数据以及动态创建其他控件
 
 5、viewWillAppear              视图将出现在屏幕之前，马上这个视图就会被展现在屏幕上了
 
 6、viewDidAppear               视图已在屏幕上渲染完成
 
 当一个视图被移除屏幕并且销毁的时候的执行顺序，这个顺序差不多和上面的相反
 
 1、viewWillDisappear            视图将被从屏幕上移除之前执行
 
 2、viewDidDisappear             视图已经被从屏幕上移除，用户看不到这个视图了
 
 3、dealloc                                 视图被销毁，此处需要对你在init和viewDidLoad中创建的对象进行释放
 
 */

#import "ViewController.h"
#import "Shop.h"
#import "MyAlertController.h"

#define rowH 78

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
}


#pragma mark - 实现数据源协议

- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section{
    return allShops.count;
}

- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath{
    
    static NSString *ID = @"cell1";
    
    UITableViewCell *cell = [tableView dequeueReusableCellWithIdentifier:ID];
    if(nil == cell){
        cell = [[UITableViewCell alloc] initWithStyle:UITableViewCellStyleSubtitle reuseIdentifier:ID];
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

- (CGFloat)tableView:(UITableView *)tableView heightForRowAtIndexPath:(NSIndexPath *)indexPath{
    return rowH;
}

#pragma mark 弹出框
- (void)myalert:(NSString *)mytext indexPath:(NSIndexPath *)indexPath tableView:(UITableView *)tableView{
    NSString *title = [NSString stringWithFormat:@"修改商品-%@",mytext];
    
    MyAlertController *alertController = [MyAlertController alertControllerWithTitle:title message:nil preferredStyle:UIAlertControllerStyleAlert];
    
    __weak typeof(self) weakViewController = self;
    __weak typeof(alertController) weakAlert = alertController;
    
    [alertController addTextFieldWithConfigurationHandler:^(UITextField * _Nonnull textField) {
        textField.clearButtonMode = UITextFieldViewModeAlways;
        textField.text = mytext;
        
        [[NSNotificationCenter defaultCenter] addObserver:weakViewController selector:@selector(handleTextFieldTextDidChangeNotification:) name:UITextFieldTextDidChangeNotification object:textField];
    }];
    
    UIAlertAction *cancelAction = [UIAlertAction actionWithTitle:@"取消" style:UIAlertActionStyleCancel handler:^(UIAlertAction * _Nonnull action) {
        [[NSNotificationCenter defaultCenter] removeObserver:weakViewController name:UITextFieldTextDidChangeNotification object:weakAlert.textFields.firstObject];
    }];
    
    UIAlertAction *defult = [UIAlertAction actionWithTitle:@"确定" style:UIAlertActionStyleDefault handler:^(UIAlertAction * _Nonnull action) {
        UITextField *mytextField = weakAlert.textFields.firstObject;
        Shop *shop = allShops[indexPath.row];
        shop.title = mytextField.text;
        allShops[indexPath.row] = shop;
        [tableView reloadRowsAtIndexPaths:@[indexPath] withRowAnimation:UITableViewRowAnimationBottom];
        
        [[NSNotificationCenter defaultCenter] removeObserver:weakViewController name:UITextFieldTextDidChangeNotification object:weakAlert.textFields.firstObject];
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
