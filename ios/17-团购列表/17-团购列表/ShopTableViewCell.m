//
//  ShopTableViewCell.m
//  17-团购列表
//
//  Created by 赵洪禹 on 16/3/1.
//  Copyright © 2016年 赵洪禹. All rights reserved.
//

#import "ShopTableViewCell.h"
#import "Shop.h"

@implementation ShopTableViewCell

+ (id)shopTableViewCell{
    // ShopTableViewCell *cell = [[NSBundle mainBundle] loadNibNamed:@"ShopTableViewCell" owner:nil options:nil][0];
    
    UINib *nib = [UINib nibWithNibName:@"ShopTableViewCell" bundle:[NSBundle mainBundle]];
    ShopTableViewCell *cell = [nib instantiateWithOwner:nil options:nil][0];
    
    return cell;
}

#pragma mark 设置shop属性值到view上
- (void)setMyshop:(Shop *)myshop{
    _myshop = myshop;
    
    _name.text = myshop.name;
    _desc.text = myshop.desc;
    _icon.image = [UIImage imageNamed:myshop.icon];
    
    // icon的显示与否取决于isShow
    // _icon.hidden = !myshop.isShow;
    
}

#pragma mark 返回cell的高度
+ (CGFloat)shopTableViewCellRowHeight{
    return 60;
}
#pragma mark cell的重用id
+ (NSString *)shopTableViewCellReuseIdentifier{
    return @"cell";
}


@end
