//
//  PSListModel.h
//  PhotoShow
//
//  Created by 沈健 on 16/7/3.
//  Copyright © 2016年 shenjian. All rights reserved.
//

#import <Foundation/Foundation.h>

@interface PSListModel : NSObject
/*
 class  Gallery {
 private int id;
 private int  galleryclass ;//          图片分类
 private String title     ;//          标题
 private String img     ;//          图库封面
 private int count     ;//          访问数
 private int rcount     ;//           回复数
 private int fcount     ;//          收藏数
 private int size     ;//      图片多少张
 }
 */
@property (nonatomic, copy) NSString *idStr;
@property (nonatomic, copy) NSString *galleryclass;
@property (nonatomic, copy) NSString *title;
@property (nonatomic, copy) NSString *img;
@property (nonatomic, assign) int count;
@property (nonatomic, assign) int rcount;
@property (nonatomic, assign) int fcount;
@property (nonatomic, assign) int size;
@property (nonatomic, copy) NSString *time;

@end
