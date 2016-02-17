//
//  ViewController.h
//  02-按钮操作
//
//  Created by 赵洪禹 on 16/2/16.
//  Copyright © 2016年 赵洪禹. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface ViewController : UIViewController

@property (weak, nonatomic) IBOutlet UIButton *image;

- (IBAction)move:(UIButton *)sender;

- (IBAction)scale:(UIButton *)sender;

- (IBAction)rotate:(UIButton *)sender;

- (IBAction)reset:(UIButton *)sender;


@end

