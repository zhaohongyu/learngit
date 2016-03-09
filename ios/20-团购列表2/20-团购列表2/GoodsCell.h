//
//  GoodsCell.h
//  20-团购列表2
//
//  Created by 赵洪禹 on 16/3/9.
//  Copyright © 2016年 赵洪禹. All rights reserved.
//

#import <UIKit/UIKit.h>

@class Goods;

@interface GoodsCell : UITableViewCell
@property (weak, nonatomic) IBOutlet UIImageView *icon;
@property (weak, nonatomic) IBOutlet UILabel *title;
@property (weak, nonatomic) IBOutlet UILabel *price;
@property (weak, nonatomic) IBOutlet UILabel *buyCount;
@property (nonatomic, strong) Goods *goods;
+(instancetype)goodsCellWithTableView:(UITableView *)tableView;

@end
