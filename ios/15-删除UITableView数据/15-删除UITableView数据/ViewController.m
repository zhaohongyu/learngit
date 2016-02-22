//
//  ViewController.m
//  15-删除UITableView数据
//
//  Created by 赵洪禹 on 16/2/21.
//  Copyright © 2016年 赵洪禹. All rights reserved.
//

#import "ViewController.h"
#import "Shop.h"

#define rowH 78

@interface ViewController () <UITableViewDataSource,UITableViewDelegate>
{
    NSMutableArray *allShops;
    NSMutableArray *deleteShops;
}

@end

@implementation ViewController

- (void)viewDidLoad {
    [super viewDidLoad];
    // 初始化数据
    // 容易返回空，尝试换个路径试试，中文路径有可能有问题
    // 如果文件夹不是黄色而是蓝色就会出现路径问题记得添加到组
    NSString *path = [[NSBundle mainBundle] pathForResource:@"shops.plist" ofType:nil];
    NSArray *arr = [NSArray arrayWithContentsOfFile:path];
    allShops = [NSMutableArray array];
    for (NSDictionary *dict in arr) {
        Shop *s = [Shop shopWithDict:dict];
        [allShops addObject:s];
    }
    
    deleteShops = [NSMutableArray array];
}

#pragma mark - 数据源方法
- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section{
    
    if (0 == deleteShops.count) {
        self.labelTitle.text = [NSString stringWithFormat:@"淘宝"];
        _removeBarBtn.enabled = NO;
    }else{
        self.labelTitle.text = [NSString stringWithFormat:@"淘宝（%ld）",deleteShops.count];
        _removeBarBtn.enabled = YES;
        
    }
    
    if (0 == allShops.count) {
        self.inverseBarBtn.enabled = NO;
    }
    
    return allShops.count;
}
-(UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath{
    static NSString *ID = @"cell1";
    
    UITableViewCell *cell = [tableView dequeueReusableCellWithIdentifier:ID];
    
    if (nil == cell) {
        cell = [[UITableViewCell alloc] initWithStyle:UITableViewCellStyleSubtitle reuseIdentifier:ID];
    }
    
    // 设置数据
    Shop *s = allShops[indexPath.row];
    cell.textLabel.text = s.name;
    cell.detailTextLabel.text = s.desc;
    cell.imageView.image = [UIImage imageNamed:s.icon];
    
    // 判断是否选中
    if ([deleteShops containsObject:s]) {
        cell.accessoryType = UITableViewCellAccessoryCheckmark;
    }else{
        cell.accessoryType = UITableViewCellAccessoryNone;
    }
    
    return cell;
}

- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath{
    Shop *s = allShops[indexPath.row];
    
    // 将选中的cell如果不存在添加到数组中存在则移除
    if ([deleteShops containsObject:s]) {
        [deleteShops removeObject:s];
    }else{
        [deleteShops addObject:s];
    }
    
    
    // 设置选中后去掉选中状态
    [tableView deselectRowAtIndexPath:indexPath animated:YES];
    
    // 局部刷新
    [tableView reloadRowsAtIndexPaths:@[indexPath] withRowAnimation:UITableViewRowAnimationBottom];
    
}

- (CGFloat)tableView:(UITableView *)tableView heightForRowAtIndexPath:(NSIndexPath *)indexPath{
    return rowH;
}

#pragma mark - 监听方法

- (IBAction)removeCells:(UIBarButtonItem *)sender {
    
    NSMutableArray *arr = [NSMutableArray array];
    for (Shop *s in deleteShops) {
        NSUInteger row = [allShops indexOfObject:s];
        NSIndexPath *indexPath = [NSIndexPath indexPathForRow:row inSection:0];
        [arr addObject:indexPath];
    }
    
    // 从数组中删除选中的数据
    [allShops removeObjectsInArray:deleteShops];
    // 清空deleteShops
    [deleteShops removeAllObjects];
    // 刷新
    // [self.tableView reloadData];
    
    // 带动画刷新
    [self.tableView deleteRowsAtIndexPaths:arr withRowAnimation:UITableViewRowAnimationTop];
    
}
// 反选
- (IBAction)InvertSelection:(UIBarButtonItem *)sender {
    
    // 取出allShops中除了deleteShops中的数据放入deleteShops
    NSMutableArray *arr = [NSMutableArray array];
    NSMutableArray *indexPathArr = [NSMutableArray array];
    NSMutableArray *indexPathArr1 = [NSMutableArray array];
    for (Shop *s in allShops) {
        NSUInteger row = [allShops indexOfObject:s];
        NSIndexPath *indexPath = [NSIndexPath indexPathForRow:row inSection:0];
        if (![deleteShops containsObject:s]) {
            [arr addObject:s];
            [indexPathArr addObject:indexPath];
        }else{
            [indexPathArr1 addObject:indexPath];
        }
    }
    // 删除deleteShops中原有的数据 ，只要数据改变就要进行刷新，不然状态会保留导致页面混乱
    [deleteShops removeAllObjects];
    // 刷新
    [self.tableView reloadRowsAtIndexPaths:indexPathArr1 withRowAnimation:UITableViewRowAnimationBottom];
    
    // 将反选后的数据放到数组中
    [deleteShops addObjectsFromArray:arr];
    // 刷新
    // 局部刷新
    [self.tableView reloadRowsAtIndexPaths:indexPathArr withRowAnimation:UITableViewRowAnimationBottom];
}
@end
