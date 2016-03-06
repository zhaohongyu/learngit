//
//  WeiboCell.h
//  18-通过代码自定义cell
//
//  Created by 赵洪禹 on 16/3/5.
//  Copyright © 2016年 赵洪禹. All rights reserved.
//

#import <UIKit/UIKit.h>

@class WeiboFrame;

@interface WeiboCell : UITableViewCell

@property (nonatomic , strong) WeiboFrame *weiboFrame;

+ (instancetype)weiboCellWithTableView:(UITableView *)tableView;

@end
