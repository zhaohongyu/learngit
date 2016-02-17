//
//  ViewController.m
//  04-图片浏览器
//
//  Created by 赵洪禹 on 16/2/17.
//  Copyright © 2016年 赵洪禹. All rights reserved.
//

#import "ViewController.h"

@interface ViewController ()
{
    NSDictionary *_imageDatas;
}

@end

@implementation ViewController

- (void)viewDidLoad {
    [super viewDidLoad];
    // Do any additional setup after loading the view, typically from a nib.
    
    [self initImageData];
    
    
}

- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

- (IBAction)night:(UISwitch *)sender {
    
    // 设置背景颜色
    NSLog(@"%@",sender);
    if(YES == sender.on){
        self.view.backgroundColor = [UIColor blackColor];
    }else{
        self.view.backgroundColor = [UIColor whiteColor];
    }
    
}

- (IBAction)scale:(UISlider *)sender {
    _image.transform = CGAffineTransformMakeScale(sender.value, sender.value);
}




// 监听设置按钮
- (IBAction)setting:(UIButton *)sender {
    
    [self myanimation:^() {
        
        CGRect tmpRect = _settingView.frame;
        
        if (tmpRect.origin.y == (self.view.frame.size.height-_settingView.frame.size.height)) {
            tmpRect.origin.y = self.view.frame.size.height+_settingView.frame.size.height;
        }else{
            tmpRect.origin.y = self.view.frame.size.height-_settingView.frame.size.height;
        }
        
        _settingView.frame = tmpRect;
        
    }];
    
}

- (void)myanimation:(void (^)())myblock{
    
    [UIView beginAnimations:nil context:nil];
    [UIView setAnimationDuration:0.5];
    
    myblock();
    
    [UIView commitAnimations];
}



// 初始化图片元数据
- (void)initImageData{
    NSBundle *bun = [NSBundle mainBundle];
    NSString *imageDataPath = [bun pathForResource:@"image_desc" ofType:@"plist"];
    
    _imageDatas = [NSDictionary dictionaryWithContentsOfFile:imageDataPath];
    
    // 设置默认值
    
    int val = 0;
    
    [self setImageData:val];
    
}

// 监听slider change事件
- (IBAction)change:(UISlider *)sender {
    
    int val = (int)sender.value;
    
    [self setImageData:val];
    
}

// 设置界面数据
-(void)setImageData:(int)imageNo{
    NSString *no = [NSString stringWithFormat:@"%d",imageNo];
    NSArray *imageData = _imageDatas[no];
    
    // 替换序号
    _imageNo.text = [NSString stringWithFormat:@"%d/7",imageNo+1];
    // 替换图片
    _image.image = [UIImage imageNamed:imageData[0]];
    // 替换描述文字
    _desc.text = imageData[1];
}

@end
