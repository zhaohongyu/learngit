//
//  ViewController.h
//  08-联系人管理-xib
//
//  Created by 赵洪禹 on 16/2/19.
//  Copyright © 2016年 赵洪禹. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface ViewController : UIViewController

- (IBAction)addContact:(UIBarButtonItem *)sender;
- (IBAction)deleteContact:(UIBarButtonItem *)sender;
@property (weak, nonatomic) IBOutlet UIBarButtonItem *removeItem;
@property (weak, nonatomic) IBOutlet UIBarButtonItem *addItem;

@end

