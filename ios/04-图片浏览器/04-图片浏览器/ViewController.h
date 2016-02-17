//
//  ViewController.h
//  04-图片浏览器
//
//  Created by 赵洪禹 on 16/2/17.
//  Copyright © 2016年 赵洪禹. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface ViewController : UIViewController

- (IBAction)setting:(UIButton *)sender;
@property (weak, nonatomic) IBOutlet UIView *settingView;

- (IBAction)night:(UISwitch *)sender;

- (IBAction)scale:(UISlider *)sender;


- (IBAction)change:(UISlider *)sender;
@property (weak, nonatomic) IBOutlet UILabel *imageNo;
@property (weak, nonatomic) IBOutlet UIImageView *image;
@property (weak, nonatomic) IBOutlet UILabel *desc;

@end

