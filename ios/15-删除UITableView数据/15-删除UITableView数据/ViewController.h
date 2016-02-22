//
//  ViewController.h
//  15-删除UITableView数据
//
//  Created by 赵洪禹 on 16/2/21.
//  Copyright © 2016年 赵洪禹. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface ViewController : UIViewController

- (IBAction)removeCells:(UIBarButtonItem *)sender;
@property (weak, nonatomic) IBOutlet UIBarButtonItem *removeBarBtn;

@property (weak, nonatomic) IBOutlet UILabel *labelTitle;

- (IBAction)InvertSelection:(UIBarButtonItem *)sender;
@property (weak, nonatomic) IBOutlet UITableView *tableView;
@property (weak, nonatomic) IBOutlet UIBarButtonItem *inverseBarBtn;

@end

