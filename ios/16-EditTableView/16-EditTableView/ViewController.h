//
//  ViewController.h
//  16-EditTableView
//
//  Created by 赵洪禹 on 16/2/22.
//  Copyright © 2016年 赵洪禹. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface ViewController : UIViewController

- (IBAction)remove:(id)sender;

@property (weak, nonatomic) IBOutlet UITableView *tableView;
@end

