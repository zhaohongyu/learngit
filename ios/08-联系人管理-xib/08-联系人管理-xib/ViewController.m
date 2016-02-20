//
//  ViewController.m
//  08-联系人管理-xib
//
//  Created by 赵洪禹 on 16/2/19.
//  Copyright © 2016年 赵洪禹. All rights reserved.
//

#import "ViewController.h"
#import "RowView.h"

#define kDuration 0.5
#define kRowH 60

// 类扩展，匿名分类
@interface ViewController ()
{
    NSArray *_allNames;
}
@end

@implementation ViewController

- (void)viewDidLoad {
    [super viewDidLoad];
    // Do any additional setup after loading the view, typically from a nib.
    _allNames = @[@"西门庆",@"北门庆",@"东门庆",@"南门庆",@"西南门庆",@"西北庆"];
    
}

- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

// 监听添加按钮
- (IBAction)addContact:(UIBarButtonItem *)sender {
    
    RowView *rowView = [self createContact];
    
    // 计算y值
    UIView *last = [self.view.subviews lastObject];
    
    // 添加到视图中
    [self.view addSubview:rowView];
    
    // 这行的Y = 最后一个子控件的Y + 最后一个子控件的高度 + 间距
    CGFloat rowY = last.frame.origin.y + last.frame.size.height + 1;
    
    rowView.frame = CGRectMake(self.view.frame.size.width, rowY, self.view.frame.size.width, kRowH);
    rowView.alpha = 0;
    // 添加动画
    [UIView animateWithDuration:kDuration animations:^{
        rowView.frame = CGRectMake(0, rowY, self.view.frame.size.width, kRowH);
        rowView.alpha = 1;
    }];
    
    // 垃圾桶按钮是否禁用
    _removeItem.enabled = self.view.subviews.count > 1;
    [self changeAddButtonStatus];
}

- (void)changeAddButtonStatus{
    UIView *last = [self.view.subviews lastObject];
    int tmp = self.view.frame.size.height / kRowH;
    _addItem.enabled = last.frame.origin.y + last.frame.size.height + tmp * 1 < self.view.frame.size.height;
}


// 监听点击icon事件，打印联系人姓名
- (void)getContactName:(UIButton *)btn{
    RowView *tmpView = (RowView *)btn.superview;
    NSLog(@"%@",tmpView.concatName.text);
}


// 右上角删除按钮
- (IBAction)deleteContact:(UIBarButtonItem *)sender {
    UIView *last = [self.view.subviews lastObject];
    
    // 添加动画
    [UIView animateWithDuration:kDuration animations:^{
        last.frame = CGRectMake(self.view.frame.size.width, last.frame.origin.y, self.view.frame.size.width, kRowH);
        last.alpha = 0;
    } completion:^(BOOL finished) {
        [last removeFromSuperview];
        _removeItem.enabled = self.view.subviews.count > 1;
        [self changeAddButtonStatus];
    }];
    
}

#pragma mark 监听删除按钮点击
- (void)deleteClick:(UIButton *)btn
{
    [UIView animateWithDuration:kDuration animations:^{
        CGRect tempF = btn.superview.frame;
        tempF.origin.y = btn.superview.frame.origin.y-btn.superview.frame.size.height;
        btn.superview.frame = tempF;
        btn.superview.alpha = 0;
    } completion:^(BOOL finished) {
        // 1.获得即将删除的这行在数组中的位置
        int startIndex = (int)[self.view.subviews indexOfObject:btn.superview];
        
        // 2.删除当前行
        [btn.superview removeFromSuperview];
        
        [UIView animateWithDuration:0.3 animations:^{
            // 3.遍历后面的子控件
            for (int i = startIndex; i<self.view.subviews.count; i++) {
                UIView *child = self.view.subviews[i];
                CGRect tempF = child.frame;
                tempF.origin.y -= kRowH + 1;
                child.frame = tempF;
            }
        }];
        
        // 4.判断垃圾桶
        _removeItem.enabled = self.view.subviews.count > 1;
        [self changeAddButtonStatus];
    }];
}

- (RowView *)createContact {
    // 创建联系人
    NSString *icon = [NSString stringWithFormat:@"01%d.png",arc4random_uniform(9)];
    NSString *concatName = _allNames[arc4random_uniform((int)_allNames.count)];
    
    RowView *rowView = [RowView rowViewWithIcon:icon concatName:concatName];
    
    // 添加监听删除事件
    [rowView.deleteBtn addTarget:self action:@selector(deleteClick:) forControlEvents:UIControlEventTouchUpInside];
    
    // 添加监听点击icon事件
    [rowView.icon addTarget:self action:@selector(getContactName:) forControlEvents:UIControlEventTouchUpInside];
    
    return rowView;
}


@end
