//
//  WeiboCell.m
//  18-通过代码自定义cell
//
//  Created by 赵洪禹 on 16/3/5.
//  Copyright © 2016年 赵洪禹. All rights reserved.
//

#import "WeiboCell.h"
#import "Weibo.h"

// 边框宽度
#define kCellBorder 10
// 头像宽高
#define kIconWH 25
// vip图标宽高
#define kVipWH 7
// 内容图片宽高
#define kContentImageWH 45


@interface WeiboCell ()
{
    // 头像
    UIImageView *_icon;
    // 昵称
    UILabel *_nickname;
    // vip图标
    UIImageView *_vip;
    // 时间
    UILabel *_time;
    // 来源
    UILabel *_source;
    // 正文
    UILabel *_content;
    // 内容图片
    UIImageView *_contentImage;
    
}
@end

@implementation WeiboCell

- (instancetype)initWithStyle:(UITableViewCellStyle)style reuseIdentifier:(NSString *)reuseIdentifier{
    self = [super initWithStyle:style reuseIdentifier:reuseIdentifier];
    if(self){
        // 添加自定义控件
        
        // iOS7_API_根据文字 字数动态确定Label宽高
        // 设置Label的字体 HelveticaNeue  Courier
        // UIFont *fnt = [UIFont fontWithName:@"HelveticaNeue" size:5.0f];
        UIFont *fnt = [UIFont systemFontOfSize:5.0f];
        
        // 头像
        _icon = [[UIImageView alloc] init];
        [self.contentView addSubview:_icon];
        
        // 昵称
        _nickname = [[UILabel alloc] init];
        _nickname.font = fnt;
        [self.contentView addSubview:_nickname];
        
        // vip图标
        _vip= [[UIImageView alloc] init];
        [self.contentView addSubview:_vip];
        
        // 时间
        _time = [[UILabel alloc] init];
        _time.font = _nickname.font;
        [self.contentView addSubview:_time];
        
        // 来源
        _source= [[UILabel alloc] init];
        _source.font = _nickname.font;
        [self.contentView addSubview:_source];
        
        // 正文
        _content= [[UILabel alloc] init];
        _content.font = _nickname.font;
        [self.contentView addSubview:_content];
        
        // 内容图片
        _contentImage = [[UIImageView alloc] init];
        [self.contentView addSubview:_contentImage];
        
    }
    return self;
}



- (void)setWeibo:(Weibo *)weibo{
    
    _weibo = weibo;
    
    // 设置微博数据
    [self setWeiboData];
    // 设置frame
    [self setFrame];
}

#pragma mark 设置控件的frame
- (void)setFrame{
    
    // 头像
    CGFloat iconX = kCellBorder;
    CGFloat iconY = kCellBorder;
    _icon.frame = CGRectMake(iconX, iconY, kIconWH, kIconWH);
    
    // 昵称
    CGFloat nicknameX = CGRectGetMaxX(_icon.frame)+kCellBorder;
    CGFloat nicknameY = iconY;
    CGSize nicknameSize = [_nickname.text sizeWithAttributes:[NSDictionary dictionaryWithObjectsAndKeys:_nickname.font,NSFontAttributeName, nil]];
    _nickname.frame = CGRectMake(nicknameX, nicknameY, nicknameSize.width, nicknameSize.height);
    if (_weibo.vip) {
        _nickname.textColor = [UIColor redColor];
    }
    
    // vip
    CGFloat vipX = CGRectGetMaxX(_nickname.frame)+kCellBorder;
    CGFloat vipY = nicknameY;
    _vip.frame = CGRectMake(vipX, vipY, kVipWH, kVipWH);
    
    // 时间
    CGFloat timeX = CGRectGetMaxX(_icon.frame)+kCellBorder;
    CGFloat timeY = CGRectGetMaxY(_nickname.frame)+kCellBorder;
    CGSize timeSize = [_time.text sizeWithAttributes:[NSDictionary dictionaryWithObjectsAndKeys:_time.font,NSFontAttributeName, nil]];
    _time.frame = CGRectMake(timeX, timeY, timeSize.width, timeSize.height);
    
    // 来源
    CGFloat sourceX = CGRectGetMaxX(_time.frame)+kCellBorder;
    CGFloat sourceY = timeY;
    CGSize sourceSize = [_source.text sizeWithAttributes:[NSDictionary dictionaryWithObjectsAndKeys:_source.font,NSFontAttributeName, nil]];
    _source.frame = CGRectMake(sourceX, sourceY, sourceSize.width, sourceSize.height);
    
    // 内容
    _content.numberOfLines = 0;
    _content.lineBreakMode = NSLineBreakByWordWrapping;
    CGFloat contentX = iconX;
    CGFloat contentY = MAX(CGRectGetMaxY(_icon.frame), CGRectGetMaxY(_time.frame));
    CGFloat contentW = self.frame.size.width - 2 * kCellBorder;
    // iOS7中用以下方法替代过时的iOS6中的sizeWithFont:constrainedToSize:lineBreakMode:方法
    CGRect tmpRect = [_content.text boundingRectWithSize:CGSizeMake(contentW, MAXFLOAT)
                                                 options:NSStringDrawingUsesLineFragmentOrigin
                                              attributes:[NSDictionary dictionaryWithObjectsAndKeys:_content.font,NSFontAttributeName, nil]
                                                 context:nil
                      ];
    CGFloat contentH = tmpRect.size.height;
    _content.frame = CGRectMake(contentX, contentY, contentW, contentH);
    
    // 图片\cell高度计算
    CGFloat cellHeight = 0;
    if (_weibo.contentImage) {
        CGFloat contentImageX = contentX + 3 * kCellBorder;
        CGFloat contentImageY = CGRectGetMaxY(_content.frame)+kCellBorder;
        _contentImage.frame = CGRectMake(contentImageX, contentImageY, kContentImageWH, kContentImageWH);
        cellHeight = CGRectGetMaxY(_contentImage.frame)+kCellBorder;
    }else{
        cellHeight = CGRectGetMaxY(_content.frame)+kCellBorder;
    }
    
    // TODO 计算cell高度
    
}

#pragma mark 设置微博数据
- (void)setWeiboData {
    
    // 头像
    _icon.image = [UIImage imageNamed:_weibo.icon];
    
    // 昵称
    _nickname.text = _weibo.nickname;
    
    // vip图标
    if(_weibo.vip){
        _vip.image = [UIImage imageNamed:@"weibo_vip.png"];
    }else{
        _vip.image = [UIImage imageNamed:@"weibo_not_vip.png"];
    }
    
    // 时间
    _time.text = _weibo.time;
    
    // 来源
    _source.text = [NSString stringWithFormat:@"来自%@",_weibo.source];
    
    // 正文
    _content.text = _weibo.content;
    
    // 图片
    if (_weibo.contentImage) {
        _contentImage.image = [UIImage imageNamed:_weibo.contentImage];
    }
}


@end
