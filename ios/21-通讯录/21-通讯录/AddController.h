//
//  AddController.h
//  21-通讯录
//
//  Created by 赵洪禹 on 16/4/30.
//  Copyright © 2016年 赵洪禹. All rights reserved.
//

#import <UIKit/UIKit.h>


@class AddController,Contact;

@protocol AddControllerDelegate <NSObject>

@optional

-(void)addController:(AddController *)addController didClickAddBtn:(Contact *)contact;

@end


@interface AddController :UIViewController

@property (nonatomic, weak) id<AddControllerDelegate> delegate;

@end
