//
//  ViewController.h
//  01-第一个iOS程序
//
//  Created by 赵洪禹 on 16/2/15.
//  Copyright © 2016年 赵洪禹. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface ViewController : UIViewController


- (IBAction)calc;

@property (nonatomic,weak) IBOutlet UITextField *num1;
@property (nonatomic,weak) IBOutlet UITextField *num2;

@property (nonatomic,weak) IBOutlet UILabel *myres;

@end

