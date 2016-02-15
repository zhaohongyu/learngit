//
//  main.m
//  Foundation框架
//
//  Created by 赵洪禹 on 16/2/14.
//  Copyright © 2016年 赵洪禹. All rights reserved.
//

#import <Foundation/Foundation.h>

#pragma mark 函数声明

void mydic();
NSUInteger countCodeLine(NSString *str_url);
void myrect();
void mypoint();
void myrange();

#pragma mark 主函数

int main(int argc, const char * argv[]) {
    @autoreleasepool {
        
        
        // mydic();
        
        // NSString *str_url = @"/Library/WebServer/Documents/learngit/ios/";
        
        // NSUInteger countLines = countCodeLine(str_url);
        
        // NSLog(@"代码总行数是:%ld",countLines);
        
        // myrect();
        
        // mypoint();
        
        // myrange();
        
        // insert code here...
        // NSLog(@"Hello, World!");
    }
    return 0;
}

#pragma mark 函数实现

// 字典操作
void mydic(){
    // 创建不可变字典
    // NSDictionary *dic = [NSDictionary dictionaryWithObject:@"honyuzhao" forKey:@"name"];
    
    // 编译器特性创建不可变字典
    NSDictionary *dic2 = @{@"name1":@"honyuzhao1",@"name2":@"honyuzhao22",@"name3":@"honyuzhao333",};
    
    // 遍历字典
    NSArray *key_arr = [dic2 allKeys];
    for (int i=0; i<dic2.count; i++) {
        // NSString *key = [key_arr objectAtIndex:i];
        NSString *key = key_arr[i];
        
        NSLog(@"%@",key);
        NSLog(@"%@",dic2[key]);
    }
    
    
    // 遍历字典
    for (id obj in dic2) {
        NSLog(@"%@",obj);
        NSLog(@"%@",[dic2 objectForKey:obj]);
    }
    
    // 遍历字典
    [dic2 enumerateKeysAndObjectsUsingBlock:^(id key, id obj, BOOL *stop) {
        NSLog(@"key=%@,obj=%@",key,obj);
    }];
    
    
    // NSLog(@"dic=%@",dic2);
    
    // NSLog(@"dic[0]=%@",[dic2 objectForKey:@"name3"]);
    // NSLog(@"dic[0]=%@",dic2[@"name2"]);
    
}

// 统计代码行数
NSUInteger countCodeLine(NSString *str_url){
    
    NSFileManager *mgr = [NSFileManager defaultManager];
    
    BOOL isDir = NO;
    BOOL isExist = [mgr fileExistsAtPath:str_url isDirectory:&isDir];
    
    if(!isExist){
        NSLog(@"传入的文件或文件夹不存在");
        return 0;
    }
    
    NSUInteger lines = 0;
    
    // 检测路径是文件还是文件夹
    if(isDir){
        
        NSArray *dir_content_arr = [mgr contentsOfDirectoryAtPath:str_url error:nil];
        for (NSString *obj in dir_content_arr) {
            NSString *full_path = [NSString stringWithFormat:@"%@/%@",str_url,obj];
            lines += countCodeLine(full_path);
        }
        
    }else{
        
        // 只统计.m .c .h文件的行数
        NSString *pathExtension = [str_url pathExtension];
        
        if(![pathExtension isEqualToString:@"m"]
           && ![pathExtension isEqualToString:@"c"]
           && ![pathExtension isEqualToString:@"h"]){
            return lines = 0;
        }
        
        
        NSString *content = [NSString stringWithContentsOfFile:str_url encoding:NSUTF8StringEncoding error:nil];
        
        NSArray *content_arr = [content componentsSeparatedByString:@"\n"];
        
        lines = content_arr.count;
        
        NSLog(@"%ld - %@",lines,str_url);
        
    }
    
    return lines;
}


void myrect(){
    
    CGRect r = CGRectMake(0,0, 200, 300);
    
    NSLog(@"r=%@",NSStringFromRect(r));
    
}

void mypoint(){
    
    CGPoint p = CGPointMake(20, 33);
    
    NSLog(@"p=%@",NSStringFromPoint(p));
    
}


void myrange(){
    NSRange range = {10,20};
    NSRange range1 = {.location=10,.length=25};
    NSRange range2 = NSMakeRange(30, 40);
    
    NSLog(@"range=%@",NSStringFromRange(range));
    NSLog(@"range=%@",NSStringFromRange(range1));
    NSLog(@"range=%@",NSStringFromRange(range2));
    
}

