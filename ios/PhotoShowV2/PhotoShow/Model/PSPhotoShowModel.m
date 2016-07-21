//
//  PSPhotoShowModel.m
//  PhotoShow
//
//  Created by 沈健 on 16/7/4.
//  Copyright © 2016年 shenjian. All rights reserved.
//

#import "PSPhotoShowModel.h"

@implementation PSPhotoShowModel
+ (NSDictionary *)modelCustomPropertyMapper {
    return @{@"imgStr" : @"src"};
}
@end
