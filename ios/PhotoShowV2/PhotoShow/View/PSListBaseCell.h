//
//  PSListBaseCell.h
//  PhotoShow
//
//  Created by 沈健 on 16/7/3.
//  Copyright © 2016年 shenjian. All rights reserved.
//

#import <UIKit/UIKit.h>
@class PSListModel;

@interface PSListBaseCell : UICollectionViewCell
@property (nonatomic, strong) PSListModel *model;
@end
