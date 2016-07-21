//
//  PSSubListModel.h
//  PhotoShow
//
//  Created by 沈健 on 16/5/26.
//  Copyright © 2016年 shenjian. All rights reserved.
//

#import <Foundation/Foundation.h>

@interface PSSubListModel : NSObject

@property (nonatomic, copy) NSString *imgUrl;

@property (nonatomic, assign) NSInteger like;

@property (nonatomic, copy) NSString *title;

@property (nonatomic, copy) NSString *href;

@property (nonatomic, copy) NSString *dateStr;

@end
