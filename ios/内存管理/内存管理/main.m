//
//  main.m
//  内存管理
//
//  Created by 赵洪禹 on 16/2/3.
//  Copyright © 2016年 赵洪禹. All rights reserved.
//

#import <Foundation/Foundation.h>
#import "Book.h"
#import "Person.h"

int main(int argc, const char * argv[]) {
    @autoreleasepool {
        
        // p - 1
        Person *p = [[Person alloc] init];
        p.name = @"李四";
        
        // b - 1
        Book *b = [[Book alloc] init];
        b.bookName = @"射雕英雄传";
        
        // p - 2
        p.book = b;
        
        // b - 2
        b.person = p;
        
        
        
        
        
        
        
        
        // insert code here...
        NSLog(@"Hello, World!");
    }
    return 0;
}

void test(){
    Book *b = [[Book alloc] init];
    
    // b.bookName = @"射雕英雄传";
    [b setBookName:@"射雕英雄传"];
    
    //NSString *bookName = b.bookName;
    NSString *bookName = [b bookName];
    
    NSLog(@"内存地址是：%p",b);
    
    NSLog(@"书名是：%@",bookName);

}
