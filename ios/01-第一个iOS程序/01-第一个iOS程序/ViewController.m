//
//  ViewController.m
//  01-第一个iOS程序
//
//  Created by 赵洪禹 on 16/2/15.
//  Copyright © 2016年 赵洪禹. All rights reserved.
//

#import "ViewController.h"

@interface ViewController ()

@end

@implementation ViewController

- (void)viewDidLoad {
    [super viewDidLoad];
    // Do any additional setup after loading the view, typically from a nib.
}

- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

- (void)calc{
    
    NSString *mynum1 = self.num1.text;
    NSString *mynum2 = self.num2.text;
    
    _myres.text = [NSString stringWithFormat:@"%d",[mynum1 intValue]+[mynum2 intValue]];
    
    
    NSLog(@"计算结果=%@",_myres.text);
}


@end
