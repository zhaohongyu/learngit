//
//  ViewController.m
//  保存照片到相册
//
//  Created by 赵洪禹 on 16/7/31.
//  Copyright © 2016年 赵洪禹. All rights reserved.
//

#import "ViewController.h"
#import "ALAssetsLibrary+CustomPhotoAlbum.h"


@interface ViewController ()

@property (weak, nonatomic) IBOutlet UIImageView *imageContent;

- (IBAction)savePhoto:(UIButton *)sender;
@end

@implementation ViewController


- (IBAction)savePhoto:(UIButton *)sender {
    
    // [self saveImageToPhotos:self.imageContent.image];
    
    ALAssetsLibrary *library = [[ALAssetsLibrary alloc] init];
    
    
    __block NSString *msg = nil ;
    [library saveImage:self.imageContent.image toAlbum:@"hongyu" completion:^(NSURL *assetURL, NSError *error) {
        if(!error){
            msg = @"保存图片成功" ;
            [self myAlertView:msg];
        }
    } failure:^(NSError *error) {
        msg = @"保存图片失败" ;
        [self myAlertView:msg];
    }];
    
    
}


- (void)saveImageToPhotos:(UIImage*)savedImage
{
    UIImageWriteToSavedPhotosAlbum(savedImage, self, @selector(image:didFinishSavingWithError:contextInfo:), NULL);
}

// 指定回调方法
- (void)image: (UIImage *) image didFinishSavingWithError: (NSError *) error contextInfo: (void *) contextInfo
{
    NSString *msg = nil ;
    
    if(error != NULL){
        msg = @"保存图片失败" ;
    }else{
        msg = @"保存图片成功" ;
    }
    [self myAlertView:msg];
}


-(void)myAlertView:(NSString *)msg{
    UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"保存图片结果提示"
                                                    message:msg
                                                   delegate:self
                                          cancelButtonTitle:@"确定"
                                          otherButtonTitles:nil];
    [alert show];
}

@end
