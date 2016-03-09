//
//  GoodsCell.m
//  20-团购列表2
//
//  Created by 赵洪禹 on 16/3/9.
//  Copyright © 2016年 赵洪禹. All rights reserved.
//

#import "GoodsCell.h"
#import "Goods.h"

@implementation GoodsCell

+(instancetype)goodsCellWithTableView:(UITableView *)tableView{
    static NSString *ID = @"goodsCell";
    GoodsCell *cell = [tableView dequeueReusableCellWithIdentifier:ID];
    if (nil == cell) {
        // 从xib中加载cell
        cell = [[[NSBundle mainBundle] loadNibNamed:@"GoodsCell" owner:nil options:nil] lastObject];
    }
    return cell;
}

- (void)setGoods:(Goods *)goods{
    _goods = goods;
    
    // 缩略图
    _icon.image = [UIImage imageNamed:_goods.icon];
    // 标题
    _title.text = _goods.title;
    // 价格
    _price.text = [NSString stringWithFormat:@"￥%@",_goods.price];
    // 购买数
    _buyCount.text = [NSString stringWithFormat:@"%@人已购买",_goods.buyCount];
    
}

@end
