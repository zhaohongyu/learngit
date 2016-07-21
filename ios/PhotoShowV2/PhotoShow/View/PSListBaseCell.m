//
//  PSListBaseCell.m
//  PhotoShow
//
//  Created by 沈健 on 16/7/3.
//  Copyright © 2016年 shenjian. All rights reserved.
//

#import "PSListBaseCell.h"
#import "PSListModel.h"
#import "UIImageView+WebCache.h"
#import "UIImage+MultiFormat.h"

@interface PSListBaseCell()

@property (weak, nonatomic) IBOutlet UIImageView *imgView;
@property (weak, nonatomic) IBOutlet UILabel *titleLabel;
@property (weak, nonatomic) IBOutlet UILabel *timeLabel;
@property (weak, nonatomic) IBOutlet UILabel *likesLabel;

@end

@implementation PSListBaseCell

- (void)awakeFromNib {
    [super awakeFromNib];
}

//+ (PSListBaseCell *)listBaseCell{
//    return [[[NSBundle mainBundle]loadNibNamed:@"PSListBaseCell" owner:self options:nil]objectAtIndex:0];
//}

- (void)setModel:(PSListModel *)model{
    _model = model;
    self.titleLabel.text = model.title;
    
    self.likesLabel.text = model.count>99999?@"99999+":[NSString stringWithFormat:@"%d",model.count];
    
    self.timeLabel.text = [NSDate strFromDate:[NSDate dateFromTimesp:[[model.time substringWithRange:NSMakeRange(0, 10)] doubleValue]]];
    NSURL *url =[NSURL URLWithString:[NSString stringWithFormat:@"%@%@",kImgUrl,model.img]];
    
    [self.imgView sd_setImageWithURL:url placeholderImage:[UIImage imageNamed:@"placeholder"]];

//    if (img) {
//        dispatch_async(dispatch_get_global_queue(0, 0), ^{
//            UIImage *image = [self clipImage:img toSize:self.imgView.frame.size];
//            dispatch_async(dispatch_get_main_queue(), ^{
//                self.imgView.layer.contents = (__bridge id _Nullable)(image.CGImage);
//            });
//        });
//    }
}

- (UIImage *)clipImage:(UIImage *)image toSize:(CGSize)size {
    UIGraphicsBeginImageContextWithOptions(size, YES, [UIScreen mainScreen].scale);
    
    CGSize imgSize = image.size;
    CGFloat x = MAX(size.width / imgSize.width, size.height / imgSize.height);
    CGSize resultSize = CGSizeMake(x * imgSize.width, x * imgSize.height);
    
    [image drawInRect:CGRectMake(0, 0, resultSize.width, resultSize.height)];
    
    UIImage *finalImage = UIGraphicsGetImageFromCurrentImageContext();
    UIGraphicsEndImageContext();
    
    return finalImage;
}


@end
