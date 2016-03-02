//
//  ShopTableViewCell.h
//  17-团购列表
//
//  Created by 赵洪禹 on 16/3/1.
//  Copyright © 2016年 赵洪禹. All rights reserved.
//

#import <UIKit/UIKit.h>

@class Shop;

@interface ShopTableViewCell : UITableViewCell

@property (weak, nonatomic) IBOutlet UILabel *name;
@property (weak, nonatomic) IBOutlet UILabel *desc;
@property (weak, nonatomic) IBOutlet UIImageView *icon;
@property (nonatomic , strong) Shop *myshop;

+ (id)shopTableViewCell;
+ (CGFloat)shopTableViewCellRowHeight;
+ (NSString *)shopTableViewCellReuseIdentifier;

@end
