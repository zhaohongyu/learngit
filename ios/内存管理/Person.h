//
//  Person.h
//  内存管理
//
//  Created by 赵洪禹 on 16/2/3.
//  Copyright © 2016年 赵洪禹. All rights reserved.
//

#import <Foundation/Foundation.h>

@class Book;

@interface Person : NSObject

@property (nonatomic,strong) NSString *name;

@property (nonatomic,strong) Book *book;

@end
