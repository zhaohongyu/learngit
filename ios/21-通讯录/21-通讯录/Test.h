//
//  Test.h
//  21-通讯录
//
//  Created by 赵洪禹 on 16/4/30.
//  Copyright © 2016年 赵洪禹. All rights reserved.
//

#import <Foundation/Foundation.h>

@interface Test : NSObject


@property (assign, nonatomic) int errorno;
@property (copy, nonatomic) NSString *msg;
@property (strong, nonatomic) NSDictionary *data;



-(instancetype)initWithDict:(NSDictionary *)dict;
+(instancetype)testWithDict:(NSDictionary *)dict;


@end
