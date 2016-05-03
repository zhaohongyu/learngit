//
//  Contact.h
//  21-通讯录
//
//  Created by 赵洪禹 on 16/4/30.
//  Copyright © 2016年 赵洪禹. All rights reserved.
//

#import <Foundation/Foundation.h>

@interface Contact : NSObject

@property (nonatomic,copy) NSString *name;

@property (nonatomic,copy) NSString *phone;


-(instancetype)initWitcDict:(NSDictionary *)dict;
+(instancetype)contactWithDict:(NSDictionary *)dict;

@end
