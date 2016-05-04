//
//  MJContactCell.m
//  01-私人通讯录
//
//  Created by apple on 14-4-10.
//  Copyright (c) 2014年 itcast. All rights reserved.
//

#import "MJContactCell.h"
#import "Contact.h"

@interface MJContactCell ()
@property (nonatomic, weak) UIView *divider;
@end

@implementation MJContactCell

+ (instancetype)cellWithTableView:(UITableView *)tableView
{
    static NSString *ID = @"contact";
    // 先从缓存池中取,如果缓存池中没有可循环利用的cell,先去storyboard中找到合适的cell
    // cell是从storyboard中创建出来的
    return [tableView dequeueReusableCellWithIdentifier:ID];
}

/**
 *  如果cell是通过storyboard或者xib创建的,就不可能会调用这个方法来初始化cell
 *  如果cell是通过手写代码创建,才会调用这个方法来初始化cell
 */
- (id)initWithStyle:(UITableViewCellStyle)style reuseIdentifier:(NSString *)reuseIdentifier
{
    self = [super initWithStyle:style reuseIdentifier:reuseIdentifier];
    if (self) {
        
    }
    return self;
}

/**
 *  如果cell是通过storyboard或者xib创建的,就会调用这个方法来初始化cell
 *  这个方法的作用类似于init方法
 */
- (void)awakeFromNib
{
    // Initialization code
    UIView *divider = [[UIView alloc] init];
    divider.backgroundColor = [UIColor blackColor];
    divider.alpha = 0.2;
    [self.contentView addSubview:divider];
    self.divider = divider;
}

/**
 *  在这个方法中设置子控件的frame
 */
- (void)layoutSubviews
{
    [super layoutSubviews];
    
    CGFloat dividerX = 0;
    CGFloat dividerH = 1;
    CGFloat dividerY = self.frame.size.height - dividerH;
    CGFloat dividerW = self.frame.size.width;
    self.divider.frame = CGRectMake(dividerX, dividerY, dividerW, dividerH);
}

- (void)setContact:(Contact *)contact
{
    _contact = contact;
    
    self.textLabel.text = contact.name;
    self.detailTextLabel.text = contact.phone;
}
@end
