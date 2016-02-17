//
//  ViewController.h
//  05-UITableView
//
//  Created by 赵洪禹 on 16/2/17.
//  Copyright © 2016年 赵洪禹. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface ViewController : UIViewController<UITableViewDataSource,UITableViewDelegate>


@property (strong,nonatomic) NSArray *dataList;

@end

