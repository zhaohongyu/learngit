//
//  WeiboFrame.h
//  18-通过代码自定义cell
//
//  Created by 赵洪禹 on 16/3/5.
//  Copyright © 2016年 赵洪禹. All rights reserved.
//

#import <Foundation/Foundation.h>
#import <UIKit/UIKit.h>

// 边框宽度
#define kCellBorder 10
// 头像宽高
#define kIconWH 25
// vip图标宽高
#define kVipWH 14
// 内容图片宽高
#define kContentImageWH 100
// 昵称字体
#define kNickNameFont [UIFont systemFontOfSize:14.0f]
// 正文字体
#define kContentFont [UIFont systemFontOfSize:16.0f]

@class Weibo;

@interface WeiboFrame : NSObject

@property (nonatomic, assign, readonly) CGRect iconF;
@property (nonatomic, assign, readonly) CGRect nicknameF;
@property (nonatomic, assign, readonly) CGRect vipF;
@property (nonatomic, assign, readonly) CGRect timeF;
@property (nonatomic, assign, readonly) CGRect sourceF;
@property (nonatomic, assign, readonly) CGRect contentF;
@property (nonatomic, assign, readonly) CGRect contentImageF;

@property (nonatomic, assign) CGFloat cellHeight;
@property (nonatomic, assign) CGFloat viewWidth;

@property (nonatomic, strong) Weibo *weibo;

- (id)initWithWidth:(CGFloat)viewWidth;

@end
