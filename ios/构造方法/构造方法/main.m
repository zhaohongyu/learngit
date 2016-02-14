//
//  main.m
//  构造方法
//
//  Created by 赵洪禹 on 16/1/29.
//  Copyright © 2016年 赵洪禹. All rights reserved.
//

#import <Foundation/Foundation.h>
#import "Person.h"
#import "Student.h"

int main(int argc, const char * argv[]) {
    @autoreleasepool {
        
        printf("%s\n",__FILE__);
        
        
        Student *s = [[Student alloc] init];
        
        NSLog(@"the student age is %d and no is %@",s.age,s.no);

        
        
        
        Person *p = [Person new];
        
        p.age = 28;
        [p setAge:77];
        
        
        NSLog(@"the person age is %d",p.age);
        
        
        // insert code here...
        NSLog(@"Hello, World!");
    }
    return 0;
}
