//
//  ViewController.m
//  06-表情排列
//
//  Created by 赵洪禹 on 16/2/17.
//  Copyright © 2016年 赵洪禹. All rights reserved.
//

#import "ViewController.h"

#define IMAGE_WIDTH 40
#define IMAGE_HEIGHT 40

@interface ViewController ()
{
    NSArray *_imageDatas;
}
@end

@implementation ViewController

- (void)viewDidLoad {
    [super viewDidLoad];
    // Do any additional setup after loading the view, typically from a nib.
    
    [self myinit];
}

- (void)myinit{
    
    NSBundle *bun = [NSBundle mainBundle];
    NSString *path = [bun pathForResource:@"image" ofType:@"plist"];
    _imageDatas = [NSArray arrayWithContentsOfFile:path];
    // NSLog(@"%@",_imageDatas);
    [self settingViewWithColumns:2 isAdd:YES];
    
}

- (void)settingViewWithColumns:(int)columns isAdd:(BOOL)isAdd{
    
    // 列间距 = （视图总宽度-表情占据的总宽度）/ (列数+1)
    int margin = (self.view.frame.size.width - IMAGE_WIDTH * columns) / (columns + 1);
    
    // 第一个表情的x坐标
    CGFloat oneX = margin;
    // 第一个表情的y坐标
    CGFloat oneY = 100;
    
    for (int i=0; i<10; i++) {
        
        int no = i % 9;
        
        // 决定x值
        int col = i % columns;
        // 决定y值
        int row = i / columns;
        
        CGFloat x = oneX + (IMAGE_WIDTH + margin) * col;
        CGFloat y = oneY + (IMAGE_HEIGHT + margin) * row;
        
        // NSLog(@"第%d行第%d列的第%d个表情的x坐标值是%f，y坐标值是%f",row,col,i,x,y);
        
        if (isAdd) {
            [self myAddViewWithX:x andY:y andImageIndex:no];
        }else{
            [self updateMyViewWithIndex:i x:x y:y];
        }
    }
    
}

// 重新排列时改变已经好表情坐标
- (void)updateMyViewWithIndex:(int)index x:(CGFloat)x y:(CGFloat)y {
    NSArray *subviews = self.view.subviews;
    UIView *myviews = subviews[index+1];
    CGRect tmpF = myviews.frame;
    tmpF.origin.x = x;
    tmpF.origin.y = y;
    myviews.frame = tmpF;
}

// 添加UImageView 到视图控件中
- (void)myAddViewWithX:(CGFloat)x andY:(CGFloat)y andImageIndex:(int)index{
    UIImageView *image = [[UIImageView alloc] init];
    
    // image.image = [UIImage imageNamed:[NSString stringWithFormat:@"01%d.png",index]];
    image.image = [UIImage imageNamed:_imageDatas[index]];

    image.frame = CGRectMake(x, y, IMAGE_WIDTH, IMAGE_HEIGHT);
    [self.view addSubview:image];
}

- (void)myanimation:(void (^)())myblock{
    
    [UIView beginAnimations:nil context:nil];
    [UIView setAnimationDuration:0.5];
    myblock();
    [UIView commitAnimations];
    
}


- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

// 监听chage事件
- (IBAction)changeColumn:(UISegmentedControl *)sender {
    
    [self myanimation:^{
        int columns = (int) sender.selectedSegmentIndex+2;
        [self settingViewWithColumns:columns isAdd:NO];
    }];
    
}
@end
