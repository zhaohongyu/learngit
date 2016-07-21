//
//  MainCollectionViewCell.m
//  PhotoShow
//
//  Created by 沈健 on 16/5/22.
//  Copyright © 2016年 shenjian. All rights reserved.
//

#import "MainCollectionViewCell.h"
#import "UIImageView+WebCache.h"
#import "PSSubListModel.h"

#define kCellMargin 5

@interface MainCollectionViewCell()

@property (nonatomic, strong) UIImageView *imageView;

@property (nonatomic, strong) UILabel *titleLabel;

@end

@implementation MainCollectionViewCell


- (instancetype)initWithFrame:(CGRect)frame{
    self = [super initWithFrame:frame];
    if (self) {
        self.backgroundColor = [UIColor yellowColor];
        
        CGFloat imgW = self.width - 2 *kCellMargin;
        CGFloat imgH = imgW*10/7;
        
        self.imageView = [[UIImageView alloc]init];
        self.imageView.frame = CGRectMake(kCellMargin, kCellMargin, imgW, imgH);
        self.imageView.contentMode = UIViewContentModeScaleAspectFit;
        [self.contentView addSubview:self.imageView];
        
        self.titleLabel = [[UILabel alloc]init];
        self.titleLabel.font = [UIFont systemFontOfSize:13];
        self.titleLabel.frame = CGRectMake(kCellMargin, CGRectGetMaxY(self.imageView.frame) + kCellMargin, imgW, 30);
        
        [self.contentView addSubview:self.titleLabel];
    }
    return self;
}

-(void)setModel:(PSSubListModel *)model{
    _model = model;
    SDWebImageDownloader *downloader = [SDWebImageDownloader sharedDownloader];
    
    [downloader setValue:@"http://www.4493.com" forHTTPHeaderField:@"Referer"];
    
    [downloader downloadImageWithURL:[NSURL URLWithString:model.imgUrl]
                             options:SDWebImageDownloaderUseNSURLCache
                            progress:^(NSInteger receivedSize, NSInteger expectedSize) {
                                
                            }
                           completed:^(UIImage *image, NSData *data, NSError *error, BOOL finished) {
                               if (image && finished) {
                                   self.imageView.image  = image;
                               }
                           }];
    
    self.titleLabel.text = model.title;
}

//- (void)awakeFromNib {
//    [super awakeFromNib];
//    self.imageView.contentMode = UIViewContentModeScaleAspectFit;
//}

@end
