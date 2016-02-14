//
//  Book.m
//  内存管理
//
//  Created by 赵洪禹 on 16/2/3.
//  Copyright © 2016年 赵洪禹. All rights reserved.
//

#import "Book.h"

@implementation Book

- (void)setBookName:(NSString *)bookName{
    _bookName = bookName;
}

-(NSString *)bookName
{
    return _bookName;
}

-(void)dealloc{
    NSLog(@"Book--dealloc");
}

@end
