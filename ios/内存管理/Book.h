//
//  Book.h
//  内存管理
//
//  Created by 赵洪禹 on 16/2/3.
//  Copyright © 2016年 赵洪禹. All rights reserved.
//

#import <Foundation/Foundation.h>

@class Person;

@interface Book : NSObject
{
    NSString *_bookName;
}

-(void)setBookName:(NSString *)bookName;
-(NSString *)bookName;

@property (nonatomic,weak) Person *person;


@end
