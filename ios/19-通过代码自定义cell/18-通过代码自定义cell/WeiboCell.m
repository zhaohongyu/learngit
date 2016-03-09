//
//  WeiboCell.m
//  18-通过代码自定义cell
//
//  Created by 赵洪禹 on 16/3/5.
//  Copyright © 2016年 赵洪禹. All rights reserved.
//

#import "WeiboCell.h"
#import "WeiboFrame.h"
#import "Weibo.h"

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

+(instancetype)weiboCellWithTableView:(UITableView *)tableView{
    static NSString *ID = @"CELL";
    WeiboCell *cell = [tableView dequeueReusableCellWithIdentifier:ID];
    if(nil == cell){
        cell = [[WeiboCell alloc] initWithStyle:UITableViewCellStyleDefault reuseIdentifier:ID];
    }
    return cell;
}

- (instancetype)initWithStyle:(UITableViewCellStyle)style reuseIdentifier:(NSString *)reuseIdentifier{
    self = [super initWithStyle:style reuseIdentifier:reuseIdentifier];
    if(self){
        // 添加自定义控件
        
        // 头像
        _icon = [[UIImageView alloc] init];
        [self.contentView addSubview:_icon];
        
        // 昵称
        _nickname = [[UILabel alloc] init];
        _nickname.font = kNickNameFont;
        [self.contentView addSubview:_nickname];
        
        // vip图标
        _vip= [[UIImageView alloc] init];
        [self.contentView addSubview:_vip];
        
        // 时间
        _time = [[UILabel alloc] init];
        _time.font = _nickname.font;
        _time.textColor = [UIColor grayColor];
        [self.contentView addSubview:_time];
        
        // 来源
        _source= [[UILabel alloc] init];
        _source.font = _nickname.font;
        _source.textColor = [UIColor grayColor];
        [self.contentView addSubview:_source];
        
        // 正文
        _content= [[UILabel alloc] init];
        _content.font = kContentFont;
        _content.numberOfLines = 0;
        _content.lineBreakMode = NSLineBreakByWordWrapping;
        [self.contentView addSubview:_content];
        
        // 内容图片
        
        _contentImage = [[UIImageView alloc] init];
        [self.contentView addSubview:_contentImage];
        
    }
    return self;
}


-(void)setWeiboFrame:(WeiboFrame *)weiboFrame{
    _weiboFrame = weiboFrame;
    
    [self setData];
    [self setFrame];
}

#pragma mark 设置微博数据
- (void)setData {
    
    // 头像
    _icon.image = [UIImage imageNamed:_weiboFrame.weibo.icon];
    
    // 昵称
    _nickname.text = _weiboFrame.weibo.nickname;
    if (_weiboFrame.weibo.vip) {
        _nickname.textColor = [UIColor redColor];
    }else{
        _nickname.textColor = [UIColor blackColor];
    }
    
    // vip图标
    if(_weiboFrame.weibo.vip){
        _vip.image = [UIImage imageNamed:@"weibo_vip"];
    }else{
        _vip.image = [UIImage imageNamed:@"weibo_not_vip.png"];
    }
    
    // 时间
    _time.text = _weiboFrame.weibo.time;
    
    // 来源
    _source.text = _weiboFrame.weibo.source;
    
    // 正文
    _content.text = _weiboFrame.weibo.content;
    
    // 图片
    if (_weiboFrame.weibo.contentImage) {
        _contentImage.hidden = NO;
        _contentImage.image = [UIImage imageNamed:_weiboFrame.weibo.contentImage];
    }else{
        _contentImage.hidden = YES;
    }
}

#pragma mark 设置控件的frame
- (void)setFrame{
    
    // 头像
    _icon.frame = _weiboFrame.iconF;
    
    // 昵称
    _nickname.frame = _weiboFrame.nicknameF;
    
    // vip
    _vip.frame = _weiboFrame.vipF;
    
    // 时间
    _time.frame = _weiboFrame.timeF;
    
    // 来源
    _source.frame = _weiboFrame.sourceF;
    
    // 内容
    
    _content.frame = _weiboFrame.contentF;
    
    // 图片
    if (_weiboFrame.weibo.contentImage) {
        _contentImage.frame = _weiboFrame.contentImageF;
    }
    
}

@end
