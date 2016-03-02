//
//  MyTableViewController.m
//  17-团购列表
//
//  Created by 赵洪禹 on 16/3/1.
//  Copyright © 2016年 赵洪禹. All rights reserved.
//

#import "MyTableViewController.h"
#import "Shop.h"
#import "ShopTableViewCell.h"

@interface MyTableViewController ()
{
    NSMutableArray *allShops;
}
@end

@implementation MyTableViewController

- (void)viewDidLoad {
    [super viewDidLoad];
    
    NSString *path = [[NSBundle mainBundle] pathForResource:@"shops.plist" ofType:nil];
    NSArray *arr = [NSArray arrayWithContentsOfFile:path];
    
    allShops = [NSMutableArray array];
    for (NSDictionary *dict in arr) {
        [allShops addObject:[Shop shopWithDict:dict]];
    }
    
    self.tableView.rowHeight = [ShopTableViewCell shopTableViewCellRowHeight];
    
}


#pragma mark - Table view data source

- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section {
    return allShops.count;
}


- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath {
    
    ShopTableViewCell *cell = [tableView dequeueReusableCellWithIdentifier:[ShopTableViewCell shopTableViewCellReuseIdentifier]];
    
    if (nil == cell) {
        cell = [ShopTableViewCell shopTableViewCell];
    }
    
    cell.myshop = allShops[indexPath.row];
    
    return cell;
}

@end
