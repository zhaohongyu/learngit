//
//  ViewController.m
//  12-UITableView多组数据展示
//
//  Created by 赵洪禹 on 16/2/21.
//  Copyright © 2016年 赵洪禹. All rights reserved.
//

#import "ViewController.h"
#import "Province.h"

@interface ViewController () <UITableViewDataSource>
{
    NSArray *allProvinces;
}
@end

@implementation ViewController

- (void)viewDidLoad {
    [super viewDidLoad];
    // Do any additional setup after loading the view, typically from a nib.
    
    UITableView *tableView = [[UITableView alloc] initWithFrame:self.view.bounds style:UITableViewStyleGrouped];
    // 设置数据源
    tableView.dataSource = self;
    [self.view addSubview:tableView];
    
    allProvinces = @[
                     [Province provinceWithHeader:@"广东"
                                           footer:@"这是广东省的简介"
                                            citys:@[@"深圳",@"广州",@"茂名",@"潮州"]
                      ],
                     [Province provinceWithHeader:@"湖南"
                                           footer:@"这是湖南省的简介"
                                            citys:@[@"长沙",@"岳阳",@"郴州",@"永州"]
                      ],
                     [Province provinceWithHeader:@"辽宁"
                                           footer:@"这是辽宁省的简介"
                                            citys:@[@"锦州",@"沈阳",@"铁岭",@"本溪"]
                      ],
                     [Province provinceWithHeader:@"吉林"
                                           footer:@"这是吉林省的简介"
                                            citys:@[@"吉林",@"长春",@"四平",@"通化"]
                      ],
                     [Province provinceWithHeader:@"湖北"
                                           footer:@"这是湖北省的简介"
                                            citys:@[@"黄冈",@"午饭"]
                      ]
                     ];
    
    
}

// 多少组
- (NSInteger)numberOfSectionsInTableView:(UITableView *)tableView{
    return allProvinces.count;
}

// 每组显示多少行
- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section{
    Province *province = allProvinces[section];
    return province.citys.count;
}

// 每一行显示的内容
- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath{
    Province *province = allProvinces[indexPath.section];
    UITableViewCell *cell = [[UITableViewCell alloc] initWithStyle:UITableViewCellStyleDefault reuseIdentifier:nil];
    cell.textLabel.text = [province citys][indexPath.row];
    return cell;
}

// 组标题
- (nullable NSString *)tableView:(UITableView *)tableView titleForHeaderInSection:(NSInteger)section{
    Province *province = allProvinces[section];
    return province.header;
}
// 组脚部
- (nullable NSString *)tableView:(UITableView *)tableView titleForFooterInSection:(NSInteger)section{
    Province *province = allProvinces[section];
    return province.footer;
}

// 索引
- (NSArray<NSString *> *)sectionIndexTitlesForTableView:(UITableView *)tableView{
    NSMutableArray *ma = [NSMutableArray array];
    for (Province *province in allProvinces) {
        [ma addObject:province.header];
    }
    return  ma;
}

@end
