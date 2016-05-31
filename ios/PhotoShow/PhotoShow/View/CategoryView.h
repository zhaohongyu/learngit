//
//  CategoryView.h
//  PhotoShow
//
//  Created by 沈健 on 16/5/25.
//  Copyright © 2016年 shenjian. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface categoryItem : NSObject
@property (nonatomic, copy) NSString *categoryTitle;
@property (nonatomic, copy) NSString *categoryEn;

+(categoryItem *)itemWithTitle:(NSString *)title en:(NSString *)en;
@end

@class CategoryView;
@protocol CategoryViewDelegate <NSObject>

@required
- (void)categoryView:(CategoryView *)CategoryView didClickBtnAtIndex:(NSInteger)index;
@end

@interface CategoryView : UIScrollView

@property (nonatomic, weak) id<CategoryViewDelegate> CategoryViewdelegate;
@property (nonatomic, strong) NSArray *arraylist;

@end
