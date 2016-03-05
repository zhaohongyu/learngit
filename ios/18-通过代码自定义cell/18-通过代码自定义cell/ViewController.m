//
//  ViewController.m
//  18-通过代码自定义cell
//
//  Created by 赵洪禹 on 16/3/5.
//  Copyright © 2016年 赵洪禹. All rights reserved.
//

#import "ViewController.h"
#import "Weibo.h"
#import "WeiboCell.h"

@interface ViewController ()
{
    NSMutableArray *allWeibo;
}
@end

@implementation ViewController

- (void)viewDidLoad {
    [super viewDidLoad];
    
    allWeibo = [NSMutableArray array];
    NSArray *arr = [NSArray arrayWithContentsOfFile:[[NSBundle mainBundle] pathForResource:@"weibo.plist" ofType:nil]];
    for (NSDictionary *dict in arr) {
        [allWeibo addObject:[Weibo weiboWithDict:dict]];
    }
    
}

- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section{
    return allWeibo.count;
}

- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath{
    static NSString *ID = @"CELL";
    WeiboCell *cell = [tableView dequeueReusableCellWithIdentifier:ID];
    if(nil == cell){
        cell = [[WeiboCell alloc] initWithStyle:UITableViewCellStyleDefault reuseIdentifier:ID];
    }
    
    cell.weibo = allWeibo[indexPath.row];
    
    return cell;
}


@end
