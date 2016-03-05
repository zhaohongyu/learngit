//
//  Weibo.h
//  18-通过代码自定义cell
//
//  Created by 赵洪禹 on 16/3/5.
//  Copyright © 2016年 赵洪禹. All rights reserved.
//

#import <Foundation/Foundation.h>

@interface Weibo : NSObject

@property (nonatomic,copy) NSString *icon;
@property (nonatomic , copy) NSString *nickname;
@property (nonatomic , assign) BOOL vip;
@property (nonatomic , copy) NSString *time;
@property (nonatomic , copy) NSString *source;
@property (nonatomic , copy) NSString *content;
@property (nonatomic , copy) NSString *contentImage;

- (id)initWithDict:(NSDictionary *)dict;
+ (id)weiboWithDict:(NSDictionary *)dict;

@end
