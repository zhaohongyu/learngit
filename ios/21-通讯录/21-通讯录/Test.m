//
//  Test.m
//  21-通讯录
//
//  Created by 赵洪禹 on 16/4/30.
//  Copyright © 2016年 赵洪禹. All rights reserved.
//

#import "Test.h"

@implementation Test


-(instancetype)initWithDict:(NSDictionary *)dict{
    if(self = [super init]){
        self.errorno = [dict[@"errorno"] intValue];
        self.msg =dict[@"msg"];
        self.data =dict[@"data"];
    }
    return self;
}

+(instancetype)testWithDict:(NSDictionary *)dict{
    return [[self alloc] initWithDict:dict];
}

-(NSString *)description{

    NSLog(@"%d----%@-----%@",self.errorno,self.msg,self.data);
    return [super description];
}

@end
