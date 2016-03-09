//
//  GoodsController.m
//  20-团购列表2
//
//  Created by 赵洪禹 on 16/3/9.
//  Copyright © 2016年 赵洪禹. All rights reserved.
//

#import "GoodsController.h"
#import "Goods.h"
#import "GoodsCell.h"
#import "GoodsHeaderView.h"
#import "GoodsFooterView.h"

@interface GoodsController () <GoodsFooterViewDelegate>

@property (nonatomic, strong) NSMutableArray *goodsArr;

@end

@implementation GoodsController

- (BOOL)prefersStatusBarHidden{
    return YES;
}

- (NSMutableArray *)goodsArr{
    if (nil == _goodsArr) {
        NSArray *tgsArr = [NSArray arrayWithContentsOfFile:[[NSBundle mainBundle] pathForResource:@"tgs.plist" ofType:nil]];
        NSMutableArray *arr = [[NSMutableArray alloc] init];
        for (NSDictionary *dict in tgsArr) {
            Goods *goods = [Goods goodsWithDict:dict];
            [arr addObject:goods];
        }
        _goodsArr = arr;
    }
    return _goodsArr;
}

- (void)viewDidLoad {
    [super viewDidLoad];
    
    // 禁止选择
    // self.tableView.allowsSelection = NO;
    self.tableView.rowHeight = 80;
    // 添加headerView
    self.tableView.tableHeaderView = [GoodsHeaderView goodsHeaderView];
    // 添加footerView
    GoodsFooterView *footerView = [GoodsFooterView goodsFooterView];
    // 设置footerView代理
    footerView.delegate = self;
    self.tableView.tableFooterView = footerView;
}

#pragma mark - Table view data source

- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section {
    return self.goodsArr.count;
}

- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath{
    GoodsCell *cell = [GoodsCell goodsCellWithTableView:tableView];
    cell.goods = self.goodsArr[indexPath.row];
    return cell;
}

- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath{
    // 设置选中后去掉选中状态
    [tableView deselectRowAtIndexPath:indexPath animated:YES];
}

#pragma mark - 实现footerView的代理方法

-(void)goodsFooterViewDidClickedLoadBtn:(GoodsFooterView *) goodsFooterView{
    Goods *goods = [[Goods alloc] init];
    goods.icon = @"37e4761e6ecf56a2d78685df7157f097.png";
    NSDate *date = [NSDate date];
    NSDateFormatter *fmt = [[NSDateFormatter alloc] init];
    fmt.dateFormat = @"yyyy-MM-dd HH:mm:ss";
    goods.title = [fmt stringFromDate:date];
    goods.price = [NSString stringWithFormat:@"%d",arc4random_uniform(1000)];
    goods.buyCount = [NSString stringWithFormat:@"%d",arc4random_uniform(10000)];
    [self.goodsArr addObject:goods];
    
    [self.tableView reloadData];
    NSIndexPath *indexPath = [NSIndexPath indexPathForRow:self.goodsArr.count-1 inSection:0];
    [self.tableView reloadRowsAtIndexPaths:@[indexPath] withRowAnimation:UITableViewRowAnimationTop];
    // 自动滚动表格到最后一行
    [self.tableView scrollToRowAtIndexPath:indexPath atScrollPosition:UITableViewScrollPositionBottom animated:YES];
}


@end
