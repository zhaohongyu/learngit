//
//  EditController.h
//  21-通讯录
//
//  Created by 赵洪禹 on 16/5/4.
//  Copyright © 2016年 赵洪禹. All rights reserved.
//

#import <UIKit/UIKit.h>

@class EditController,Contact;

@protocol EditControllerDelegate <NSObject>

@optional

-(void)editController:(EditController *)editController didClickSaveBtn:(Contact *)contact indexPath:(NSIndexPath *)indexPath;

@end

@interface EditController : UIViewController

@property (nonatomic,strong) Contact *contact;
@property (nonatomic,strong) NSIndexPath *indexPath;
@property (nonatomic, weak) id<EditControllerDelegate> delegate;

@end
