//
//  CategoryView.m
//  PhotoShow
//
//  Created by 沈健 on 16/5/25.
//  Copyright © 2016年 shenjian. All rights reserved.
//

#import "CategoryView.h"

#define kBtnW 90
#define KbtnH 40

@implementation categoryItem

+ (categoryItem *)itemWithTitle:(NSString *)title en:(NSString *)en{
    categoryItem *item = [[categoryItem alloc]init];
    item.categoryTitle = title;
    item.categoryEn = en;
    return item;
}

@end

@interface CategoryView ()
//@property (nonatomic, strong) NSArray *arraylist;
@end

@implementation CategoryView

- (instancetype)initWithFrame:(CGRect)frame{
    self = [super initWithFrame:frame];
    if (self) {
        self.backgroundColor = [UIColor greenColor];
        
        self.showsHorizontalScrollIndicator = NO;
        
            }
    return self;
}

- (void)setArraylist:(NSArray *)arraylist{
    _arraylist = arraylist;
    self.contentSize = CGSizeMake(kBtnW * self.arraylist.count, 0);
    [self initWithButtonList];
}

- (void)initWithButtonList{
    for (int index = 0; index<self.arraylist.count; index++) {
        UIButton *btn =[self buttonWihtCategoryItem:self.arraylist[index] index:index];
        
        [self addSubview:btn];
        if (self.subviews.count == 1 ) {
            [self btnClick:btn]; 
        }
    }
    
    
}

- (UIButton *)buttonWihtCategoryItem:(categoryItem *)item index:(int)index{
    
    CGFloat btnW = kBtnW;
    CGFloat btnY = 0;
    CGFloat btnH = KbtnH;
    CGFloat btnX = btnW * index;
    
    UIButton *btn = [UIButton buttonWithType:UIButtonTypeCustom];
    btn.frame = CGRectMake(btnX, btnY, btnW, btnH);
    btn.tag = index;
    [btn setTitle:item.categoryTitle forState:UIControlStateNormal];
    [btn setTitleColor:[UIColor blueColor] forState:UIControlStateNormal];
    btn.titleLabel.font = [UIFont systemFontOfSize:13];
    [btn addTarget:self action:@selector(btnClick:) forControlEvents:UIControlEventTouchUpInside];
    return btn;
}

- (void)btnClick:(UIButton *)btn{
    if (self.CategoryViewdelegate != nil && [self.CategoryViewdelegate respondsToSelector:@selector(categoryView:didClickBtnAtIndex:)]) {
        [self.CategoryViewdelegate categoryView:self didClickBtnAtIndex:btn.tag];
    }
}

@end
