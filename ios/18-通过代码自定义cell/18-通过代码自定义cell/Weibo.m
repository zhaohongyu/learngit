//
//  Weibo.m
//  18-通过代码自定义cell
//
//  Created by 赵洪禹 on 16/3/5.
//  Copyright © 2016年 赵洪禹. All rights reserved.
//

#import "Weibo.h"

@implementation Weibo

-(id)initWithDict:(NSDictionary *)dict{
    self = [super init];
    if(self){
        self.icon = dict[@"icon"];
        self.nickname = dict[@"nickname"];
        self.vip = [dict[@"vip"] boolValue];
        self.time = dict[@"time"];
        self.source = dict[@"source"];
        self.content = dict[@"content"];
        self.contentImage = dict[@"icon"];
    }
    return self;
}

+ (id)weiboWithDict:(NSDictionary *)dict{
    return [[self alloc] initWithDict:dict];
}

@end
