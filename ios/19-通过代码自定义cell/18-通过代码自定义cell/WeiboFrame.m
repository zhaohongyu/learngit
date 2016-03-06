//
//  WeiboFrame.m
//  18-通过代码自定义cell
//
//  Created by 赵洪禹 on 16/3/5.
//  Copyright © 2016年 赵洪禹. All rights reserved.
//

#import "WeiboFrame.h"
#import "Weibo.h"

@implementation WeiboFrame

- (id)initWithWidth:(CGFloat)viewWidth{
    if (self = [super init]) {
        _viewWidth = viewWidth;
    }
    return self;
}

-(void)setWeibo:(Weibo *)weibo{
    _weibo = weibo;
    // 设置frame
    [self setFrame];
}

#pragma mark 设置控件的frame
- (void)setFrame{
    
    // 头像
    CGFloat iconX = kCellBorder;
    CGFloat iconY = kCellBorder + 20;
    _iconF = CGRectMake(iconX, iconY, kIconWH, kIconWH);
    
    // 昵称
    CGFloat nicknameX = CGRectGetMaxX(_iconF)+kCellBorder;
    CGFloat nicknameY = iconY;
    CGSize nicknameSize = [_weibo.nickname sizeWithAttributes:[NSDictionary dictionaryWithObjectsAndKeys:kNickNameFont,NSFontAttributeName, nil]];
    _nicknameF = CGRectMake(nicknameX, nicknameY, nicknameSize.width, nicknameSize.height);
    
    // vip
    CGFloat vipX = CGRectGetMaxX(_nicknameF)+kCellBorder;
    CGFloat vipY = nicknameY;
    _vipF = CGRectMake(vipX, vipY, kVipWH, kVipWH);
    
    // 时间
    CGFloat timeX = CGRectGetMaxX(_iconF)+kCellBorder;
    CGFloat timeY = CGRectGetMaxY(_nicknameF)+kCellBorder;
    CGSize timeSize = [_weibo.time sizeWithAttributes:[NSDictionary dictionaryWithObjectsAndKeys:kNickNameFont,NSFontAttributeName, nil]];
    _timeF = CGRectMake(timeX, timeY, timeSize.width, timeSize.height);
    
    // 来源
    CGFloat sourceX = CGRectGetMaxX(_timeF)+kCellBorder;
    CGFloat sourceY = timeY;
    _weibo.source = [NSString stringWithFormat:@"来自%@",_weibo.source];
    CGSize sourceSize = [_weibo.source sizeWithAttributes:[NSDictionary dictionaryWithObjectsAndKeys:kNickNameFont,NSFontAttributeName, nil]];
    _sourceF = CGRectMake(sourceX, sourceY, sourceSize.width, sourceSize.height);
    
    // 内容
    CGFloat contentX = iconX;
    CGFloat contentY = MAX(CGRectGetMaxY(_iconF), CGRectGetMaxY(_timeF)) + kCellBorder;
    CGFloat contentW = _viewWidth - 2 * kCellBorder; 
    // iOS7中用以下方法替代过时的iOS6中的sizeWithFont:constrainedToSize:lineBreakMode:方法
    CGRect tmpRect = [_weibo.content boundingRectWithSize:CGSizeMake(contentW, MAXFLOAT)
                                                  options:NSStringDrawingUsesLineFragmentOrigin
                                               attributes:[NSDictionary dictionaryWithObjectsAndKeys:kContentFont,NSFontAttributeName, nil]
                                                  context:nil
                      ];
    CGFloat contentH = tmpRect.size.height;
    _contentF = CGRectMake(contentX, contentY, contentW, contentH);
    
    // 图片\cell高度计算
    if (_weibo.contentImage) {
        CGFloat contentImageX = contentX + kCellBorder;
        CGFloat contentImageY = CGRectGetMaxY(_contentF)+kCellBorder;
        _contentImageF = CGRectMake(contentImageX, contentImageY, kContentImageWH, kContentImageWH);
        _cellHeight = CGRectGetMaxY(_contentImageF)+kCellBorder;
    }else{
        _cellHeight = CGRectGetMaxY(_contentF)+kCellBorder;
    }
}

@end
