//
//  Contact.m
//  21-通讯录
//
//  Created by 赵洪禹 on 16/4/30.
//  Copyright © 2016年 赵洪禹. All rights reserved.
//

#import "Contact.h"

@interface Contact() <NSCoding,NSCopying>

@end

@implementation Contact

-(instancetype)initWithDict:(NSDictionary *)dict{
    if(self = [super init]){
        self.name = dict[@"name"];
        self.phone = dict[@"phone"];
    }
    return self;
}

+(instancetype)contactWithDict:(NSDictionary *)dict{
    return [[self alloc] initWithDict:dict];
}


#pragma --mark 实现NSCoding

- (void)encodeWithCoder:(NSCoder *)aCoder{
    
    [aCoder encodeObject:self.name forKey:@"name"];
    [aCoder encodeObject:self.phone forKey:@"phone"];
    
}
- (nullable instancetype)initWithCoder:(NSCoder *)aDecoder{
    if (self = [super init]) {
        if (nil == aDecoder) {
            return self;
        }
        self.name = [aDecoder decodeObjectForKey:@"name"];
        self.phone = [aDecoder decodeObjectForKey:@"phone"];
    }
    return self;
}

#pragma --mark 拷贝

- (instancetype)copyWithZone:(NSZone *)zone
{
    id copy = [[[self class] alloc] init];
    if (copy) {
        [copy setName:[self.name copyWithZone:zone]];
        [copy setPhone:[self.phone copyWithZone:zone]];
    }
    
    return copy;
}


@end
