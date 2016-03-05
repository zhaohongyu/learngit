//
//  ViewController.m
//  18-通过代码自定义cell
//
//  Created by 赵洪禹 on 16/3/5.
//  Copyright © 2016年 赵洪禹. All rights reserved.
//

#import "ViewController.h"
#import "WeiboCell.h"
#import "Weibo.h"
#import "WeiboFrame.h"

@interface ViewController ()
{
    NSMutableArray *allFrames;
}
@end

@implementation ViewController

- (void)viewDidLoad {
    [super viewDidLoad];
    
    allFrames = [NSMutableArray array];
    NSArray *arr = [NSArray arrayWithContentsOfFile:[[NSBundle mainBundle] pathForResource:@"weibo.plist" ofType:nil]];
    CGFloat viewWidth = self.view.frame.size.width;
    for (NSDictionary *dict in arr) {
        WeiboFrame *frame = [[WeiboFrame alloc] initWithWidth:viewWidth];
        Weibo *weibo = [Weibo weiboWithDict:dict];
        frame.weibo = weibo;
        [allFrames addObject:frame];
    }
    
}

- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section{
    return allFrames.count;
}

- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath{
    static NSString *ID = @"CELL";
    WeiboCell *cell = [tableView dequeueReusableCellWithIdentifier:ID];
    if(nil == cell){
        cell = [[WeiboCell alloc] initWithStyle:UITableViewCellStyleDefault reuseIdentifier:ID];
    }
    
    cell.weiboFrame = allFrames[indexPath.row];
    
    return cell;
}

- (CGFloat)tableView:(UITableView *)tableView heightForRowAtIndexPath:(NSIndexPath *)indexPath;{
    WeiboFrame *weiboFrame = allFrames[indexPath.row];
    return weiboFrame.cellHeight;
}

- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath{
    // 设置选中后去掉选中状态
    [tableView deselectRowAtIndexPath:indexPath animated:YES];
}

@end
