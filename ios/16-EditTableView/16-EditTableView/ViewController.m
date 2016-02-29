//
//  ViewController.m
//  16-EditTableView
//
//  Created by 赵洪禹 on 16/2/22.
//  Copyright © 2016年 赵洪禹. All rights reserved.
//

#import "ViewController.h"
#import "Shop.h"

#define rowH 78

@interface ViewController () <UITableViewDataSource,UITableViewDelegate>
{
    NSMutableArray *allShops;
}
@end

@implementation ViewController

- (void)viewDidLoad {
    [super viewDidLoad];
    allShops = [NSMutableArray array];
    
    NSString *path = [[NSBundle mainBundle] pathForResource:@"shops.plist" ofType:nil];
    NSArray *arr = [NSArray arrayWithContentsOfFile:path];
    allShops = [NSMutableArray array];
    for (NSDictionary *dict in arr) {
        Shop *s = [Shop shopWithDict:dict];
        [allShops addObject:s];
    }
}

- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section{
    return allShops.count;
}

- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath{
    
    static NSString *ID = @"cell1";
    
    UITableViewCell *cell = [tableView dequeueReusableCellWithIdentifier:ID];
    if (nil == cell) {
        cell = [[UITableViewCell alloc] initWithStyle:UITableViewCellStyleValue1 reuseIdentifier:ID];
    }
    
    // 设置数据
    Shop *s = allShops[indexPath.row];
    cell.textLabel.text = s.name;
    cell.detailTextLabel.text = s.desc;
    
    return cell;
}
#pragma mark 设置每行高度
- (CGFloat)tableView:(UITableView *)tableView heightForRowAtIndexPath:(NSIndexPath *)indexPath{
    return rowH;
}

#pragma mark 排序
- (void)tableView:(UITableView *)tableView moveRowAtIndexPath:(NSIndexPath *)sourceIndexPath toIndexPath:(NSIndexPath *)destinationIndexPath{
    // 取出数据
    Shop *s = allShops[sourceIndexPath.row];
    // 删除数据
    [allShops removeObject:s];
    // 添加数据
    [allShops insertObject:s atIndex:destinationIndexPath.row];
}

#pragma mark 每一行数据的编辑状态图标
//- (UITableViewCellEditingStyle)tableView:(UITableView *)tableView editingStyleForRowAtIndexPath:(NSIndexPath *)indexPath{
//    return nil;
//}

#pragma mark 提交编辑
- (void)tableView:(UITableView *)tableView commitEditingStyle:(UITableViewCellEditingStyle)editingStyle forRowAtIndexPath:(NSIndexPath *)indexPath{
    // 删除数据
    Shop *s = allShops[indexPath.row];
    [allShops removeObject:s];
    // 刷新
    [tableView deleteRowsAtIndexPaths:@[indexPath] withRowAnimation:UITableViewRowAnimationTop];
}

#pragma mark 监听删除按钮
- (IBAction)remove:(id)sender {
    
    BOOL result = !self.tableView.isEditing;
    
    
    [self.tableView setEditing:result animated:YES];
    
}
@end
