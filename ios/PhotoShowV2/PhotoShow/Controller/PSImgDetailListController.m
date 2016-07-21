//
//  PSImgDetailListController.m
//  PhotoShow
//
//  Created by 沈健 on 16/5/28.
//  Copyright © 2016年 shenjian. All rights reserved.
//

#import "PSImgDetailListController.h"
#import "HYBNetworking.h"
#import "MBProgressHUD+SJ.h"
#import "PSImgDetailModel.h"

#define ImgDetailBaseUrl @"http://123.206.61.52/imgDetailList?href="

@interface PSImgDetailListController ()
@property(nonatomic, strong) NSMutableArray *ImgArray;
@end

@implementation PSImgDetailListController

- (NSMutableArray *)ImgArray{
    if (!_ImgArray) {
        _ImgArray = [NSMutableArray array];
    }
    return _ImgArray;
}

- (void)viewDidLoad {
    [super viewDidLoad];
    self.view.backgroundColor = gbColor;
    [self loadImgDetail];
}

- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
}

- (void)loadImgDetail{
    [MBProgressHUD showHUD];
    [HYBNetworking enableInterfaceDebug:YES];
    [HYBNetworking cacheGetRequest:YES shoulCachePost:YES];
    
    NSString *url = [NSString stringWithFormat:@"%@%@",ImgDetailBaseUrl,self.href];
    
    [HYBNetworking postWithUrl:url
                  refreshCache:YES
                        params:nil
                       success:^(id response) {
                           [MBProgressHUD hideHUD];
                           NSArray *responseArray = response[@"data"];
                           
                           NSArray *modelArray = [NSArray yy_modelArrayWithClass:[PSImgDetailModel class] json:responseArray];
                           
                           self.ImgArray = [NSMutableArray arrayWithArray:modelArray];
                           NSLog(@"self.ImgArray ----  %@",self.ImgArray);
                       }
                          fail:^(NSError *error) {
                              [MBProgressHUD hideHUD];
                          }];
}

@end
