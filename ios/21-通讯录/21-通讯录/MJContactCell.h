//
//  MJContactCell.h
//  01-私人通讯录
//
//  Created by apple on 14-4-10.
//  Copyright (c) 2014年 itcast. All rights reserved.
//

#import <UIKit/UIKit.h>
@class Contact;
@interface MJContactCell : UITableViewCell
@property (nonatomic, strong) Contact *contact;

+ (instancetype)cellWithTableView:(UITableView *)tableView;
@end
