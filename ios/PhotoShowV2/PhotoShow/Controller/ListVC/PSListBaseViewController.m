//
//  PSListBaseViewController.m
//  PhotoShow
//
//  Created by 沈健 on 16/7/3.
//  Copyright © 2016年 shenjian. All rights reserved.
//

#import "PSListBaseViewController.h"
#import "PSListModel.h"
#import "PSListBaseCell.h"
#import "MJRefresh.h"
#import "HYBNetworking.h"
#import "PSPhotoShowModel.h"
#import "MWPhotoBrowser.h"
#import "ALAssetsLibrary+CustomPhotoAlbum.h"
#import "MBProgressHUD+SJ.h"

#define Kcellmargin 10
#define kListBaseUrl @"http://www.tngou.net/tnfs/api/list"

@interface PSListBaseViewController ()<MWPhotoBrowserDelegate>
@property (nonatomic, strong) NSMutableArray *dataSource;
@property (nonatomic, assign) int currentPage;
//@property (nonatomic, strong) MWPhotoBrowser *photoBrowser;
@property (nonatomic, strong) NSMutableArray *photosArray;

@property (nonatomic, strong) NSMutableArray *photos;
@property (nonatomic, strong) NSMutableArray *thumbs;

@property (nonatomic, strong) UILongPressGestureRecognizer *longPressGestureRecognizer;

@property (nonatomic, assign) NSUInteger *currentCatImageIndex;// 当前正在查看的照片索引

@property (nonatomic, strong) ALAssetsLibrary *assetslibrary;

@end

@implementation PSListBaseViewController

//- (MWPhotoBrowser *)photoBrowser{
//    if (!_photoBrowser) {
//        _photoBrowser = [[MWPhotoBrowser alloc]initWithDelegate:self];
//        _photoBrowser.displayActionButton = NO; // Show action button to allow sharing, copying, etc (defaults to YES)
//        _photoBrowser.displayNavArrows = NO; // Whether to display left and right nav arrows on toolbar (defaults to NO)
//        _photoBrowser.displaySelectionButtons = NO; // Whether selection buttons are shown on each image (defaults to NO)
//        _photoBrowser.zoomPhotosToFill = YES; // Images that almost fill the screen will be initially zoomed to fill (defaults to YES)
//        _photoBrowser.alwaysShowControls = NO; // Allows to control whether the bars and controls are always visible or whether they fade away to show the photo full (defaults to NO)
//        _photoBrowser.enableGrid = YES; // Whether to allow the viewing of all the photo thumbnails on a grid (defaults to YES)
//        _photoBrowser.startOnGrid = YES; // Whether to start on the grid of thumbnails instead of the first photo (defaults to NO)
//        _photoBrowser.autoPlayOnAppear = NO; // Auto-play first video
//    }
//    return _photoBrowser;
//}

-(ALAssetsLibrary *)assetslibrary{
    if(!_assetslibrary){
        _assetslibrary = [[ALAssetsLibrary alloc] init];
    }
    return _assetslibrary;
}

-(UILongPressGestureRecognizer *)longPressGestureRecognizer{
    if(!_longPressGestureRecognizer){
        _longPressGestureRecognizer =[[UILongPressGestureRecognizer alloc] initWithTarget:self action:@selector(handleLongPressGestures:)];
        /* numberOfTouchesRequired这个属性保存了有多少个手指点击了屏幕,因此你要确保你每次的点击手指数目是一样的,默认值是为 0. */
        _longPressGestureRecognizer.numberOfTouchesRequired = 1;
        /* Maximum 100 pixels of movement allowed before the gesture is recognized */
        /*最大100像素的运动是手势识别所允许的*/
        _longPressGestureRecognizer.allowableMovement = 100.0f;
        /*这个参数表示,两次点击之间间隔的时间长度。*/
        _longPressGestureRecognizer.minimumPressDuration = 1.0;
    }
    return _longPressGestureRecognizer;
}

- (NSMutableArray *)photosArray{
    if (!_photosArray) {
        _photosArray = [NSMutableArray array];
    }
    return _photosArray;
}

- (NSMutableArray *)dataSource{
    if (!_dataSource) {
        _dataSource = [NSMutableArray array];
    }
    return _dataSource;
}

- (NSMutableArray *)photos{
    if (!_photos) {
        _photos = [NSMutableArray array];
    }
    return _photos;
}

- (NSMutableArray *)thumbs{
    if (!_thumbs) {
        _thumbs = [NSMutableArray array];
    }
    return _thumbs;
}

static NSString * const reuseIdentifier = @"Cell";

- (instancetype)init{
    self = [super initWithCollectionViewLayout:[[UICollectionViewFlowLayout alloc]init]];
    if (self) {
        self.collectionView.alwaysBounceVertical = YES;
    }
    return self;
    
    //    UICollectionViewFlowLayout *flowLayout = [[UICollectionViewFlowLayout alloc]init];
    //    return [super initWithCollectionViewLayout:flowLayout];
}

- (void)viewDidLoad {
    [super viewDidLoad];
    NSLog(@"self.title ---- %@",self.title);
    self.collectionView.backgroundColor = [UIColor whiteColor];
    [self.collectionView registerNib:[UINib nibWithNibName:@"PSListBaseCell" bundle:nil] forCellWithReuseIdentifier:reuseIdentifier];
    [self addMJRefresh];
    
}

- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
}

- (void)addMJRefresh{
    
    self.collectionView.mj_header = [MJRefreshNormalHeader headerWithRefreshingTarget:self refreshingAction:@selector(loadNewData)];
    [self.collectionView.mj_header beginRefreshing];
    self.collectionView.mj_footer = [MJRefreshBackNormalFooter footerWithRefreshingTarget:self refreshingAction:@selector(loadMoreData)];
}

- (int)getListID{
    return 0;
}

- (void)loadNewData{
    [HYBNetworking cancelAllRequest];
    self.currentPage = 1;
    
    [HYBNetworking enableInterfaceDebug:YES];
    [HYBNetworking cacheGetRequest:YES shoulCachePost:YES];
    
    NSMutableDictionary *params = [NSMutableDictionary dictionary];
    [params setValue:@(self.currentPage) forKey:@"page"];
    [params setValue:@(10) forKey:@"rows"];
    [params setValue:@([self getListID]) forKey:@"id"];
    
    [HYBNetworking getWithUrl:kListBaseUrl
                 refreshCache:YES
                       params:params
                      success:^(id response) {
                          if(response[@"status"]){
                              self.currentPage ++;
                              [self.dataSource removeAllObjects];
                              NSArray *responseArray = response[@"tngou"];
                              NSArray *modelArray = [NSArray yy_modelArrayWithClass:[PSListModel class] json:responseArray];
                              [self.dataSource addObjectsFromArray:modelArray];
                              [self.collectionView reloadData];
                          }
                          [self.collectionView.mj_header endRefreshing];
                      }
                         fail:^(NSError *error) {
                             [self.collectionView.mj_header endRefreshing];
                         }];
}

- (void)loadMoreData{
    [HYBNetworking cancelAllRequest];
    [HYBNetworking enableInterfaceDebug:YES];
    [HYBNetworking cacheGetRequest:YES shoulCachePost:YES];
    
    NSMutableDictionary *params = [NSMutableDictionary dictionary];
    [params setValue:@(self.currentPage) forKey:@"page"];
    [params setValue:@(10) forKey:@"rows"];
    [params setValue:@([self getListID]) forKey:@"id"];
    
    [HYBNetworking getWithUrl:kListBaseUrl
                 refreshCache:YES
                       params:params
                      success:^(id response) {
                          if(response[@"status"]){
                              self.currentPage ++;
                              NSArray *responseArray = response[@"tngou"];
                              NSArray *modelArray = [NSArray yy_modelArrayWithClass:[PSListModel class] json:responseArray];
                              [self.dataSource addObjectsFromArray:modelArray];
                              [self.collectionView reloadData];
                          }
                          [self.collectionView.mj_footer endRefreshing];
                      }
                         fail:^(NSError *error) {
                             [self.collectionView.mj_footer endRefreshing];
                         }];
}

- (void)loadImgDetailWithId:(NSString *)ID{
    [self.photosArray removeAllObjects];
    [HYBNetworking cancelAllRequest];
    [HYBNetworking enableInterfaceDebug:YES];
    [HYBNetworking cacheGetRequest:YES shoulCachePost:YES];
    NSString *url = @"http://www.tngou.net/tnfs/api/show";
    NSMutableDictionary *params = [NSMutableDictionary dictionary];
    [params setValue:ID forKey:@"id"];
    
    [HYBNetworking getWithUrl:url refreshCache:YES params:params success:^(id response) {
        if(response[@"status"]){
            NSArray *listArray = response[@"list"];
            NSArray *modelArray = [NSArray yy_modelArrayWithClass:[PSPhotoShowModel class] json:listArray];
            [self.photosArray addObjectsFromArray:modelArray];
            if (self.photosArray.count) {
                [self jumpToPhotoBrowser];
            }
        }
    } fail:^(NSError *error) {
        
    }];
}

- (void)jumpToPhotoBrowser{
    MWPhotoBrowser *photoBrowser = [[MWPhotoBrowser alloc]initWithDelegate:self];
    photoBrowser.displayActionButton = NO; // Show action button to allow sharing, copying, etc (defaults to YES)
    photoBrowser.displayNavArrows = YES; // Whether to display left and right nav arrows on toolbar (defaults to NO)
    photoBrowser.displaySelectionButtons = NO; // Whether selection buttons are shown on each image (defaults to NO)
    photoBrowser.zoomPhotosToFill = NO; // Images that almost fill the screen will be initially zoomed to fill (defaults to YES)
    photoBrowser.alwaysShowControls = NO; // Allows to control whether the bars and controls are always visible or whether they fade away to show the photo full (defaults to NO)
    photoBrowser.enableGrid = YES; // Whether to allow the viewing of all the photo thumbnails on a grid (defaults to YES)
    photoBrowser.startOnGrid = YES; // Whether to start on the grid of thumbnails instead of the first photo (defaults to NO)
    photoBrowser.autoPlayOnAppear = NO; // Auto-play first video
    
    NSMutableArray *photos = [[NSMutableArray alloc] init];
    NSMutableArray *thumbs = [[NSMutableArray alloc] init];
    MWPhoto *photo;
    
    for (PSPhotoShowModel *model in self.photosArray) {
        NSString *imageUrl =[NSString stringWithFormat:@"%@%@",kImgUrl,model.imgStr];
        photo = [MWPhoto photoWithURL:[NSURL URLWithString:imageUrl]];
        [photos addObject:photo];
        [thumbs addObject:photo];
    }
    self.photos = photos;
    self.thumbs = thumbs;
    
    UINavigationController *nc = [[UINavigationController alloc] initWithRootViewController:photoBrowser];
    nc.modalTransitionStyle = UIModalTransitionStyleCrossDissolve;
    [self presentViewController:nc animated:YES completion:nil];
}

#pragma mark <UICollectionViewDataSource>

- (NSInteger)numberOfSectionsInCollectionView:(UICollectionView *)collectionView {
    return 1;
}


- (NSInteger)collectionView:(UICollectionView *)collectionView numberOfItemsInSection:(NSInteger)section {
    return self.dataSource.count;
}

- (UICollectionViewCell *)collectionView:(UICollectionView *)collectionView cellForItemAtIndexPath:(NSIndexPath *)indexPath {
    PSListBaseCell *cell = [collectionView dequeueReusableCellWithReuseIdentifier:reuseIdentifier forIndexPath:indexPath];
    cell.model = self.dataSource[indexPath.item];
    return cell;
}

-(void)collectionView:(UICollectionView *)collectionView didSelectItemAtIndexPath:(NSIndexPath *)indexPath
{
    [collectionView deselectItemAtIndexPath:indexPath animated:YES];
    PSListModel *model = self.dataSource[indexPath.item];
    [self loadImgDetailWithId:model.idStr];
    
}

-(CGSize)collectionView:(UICollectionView *)collectionView layout:(UICollectionViewLayout *)collectionViewLayout sizeForItemAtIndexPath:(NSIndexPath *)indexPath{
    CGSize size;
    size.width = (self.view.width - 3 * Kcellmargin)/2;
    size.height = size.width * 249/187;
    return size;
}

- (CGFloat)collectionView:(UICollectionView *)collectionView layout:(UICollectionViewLayout*)collectionViewLayout minimumLineSpacingForSectionAtIndex:(NSInteger)section
{
    return Kcellmargin;
}

- (CGFloat)collectionView:(UICollectionView *)collectionView layout:(UICollectionViewLayout*)collectionViewLayout minimumInteritemSpacingForSectionAtIndex:(NSInteger)section
{
    return Kcellmargin;
}

- (UIEdgeInsets)collectionView:(UICollectionView *)collectionView layout:(UICollectionViewLayout*)collectionViewLayout insetForSectionAtIndex:(NSInteger)section
{
    return UIEdgeInsetsMake(Kcellmargin, Kcellmargin, Kcellmargin, Kcellmargin);
}

#pragma mark - MWPhotoBrowserDelegate

- (NSUInteger)numberOfPhotosInPhotoBrowser:(MWPhotoBrowser *)photoBrowser {
    return self.photos.count;
}

- (id <MWPhoto>)photoBrowser:(MWPhotoBrowser *)photoBrowser photoAtIndex:(NSUInteger)index {
    if (index < _photos.count)
        return [_photos objectAtIndex:index];
    return nil;
}

- (id <MWPhoto>)photoBrowser:(MWPhotoBrowser *)photoBrowser thumbPhotoAtIndex:(NSUInteger)index {
    if (index < _thumbs.count)
        return [_thumbs objectAtIndex:index];
    return nil;
}

//- (MWCaptionView *)photoBrowser:(MWPhotoBrowser *)photoBrowser captionViewForPhotoAtIndex:(NSUInteger)index {
//    MWPhoto *photo = [self.photos objectAtIndex:index];
//    MWCaptionView *captionView = [[MWCaptionView alloc] initWithPhoto:photo];
//    return [captionView autorelease];
//}

//- (void)photoBrowser:(MWPhotoBrowser *)photoBrowser actionButtonPressedForPhotoAtIndex:(NSUInteger)index {
//    NSLog(@"ACTION!");
//}

- (void)photoBrowser:(MWPhotoBrowser *)photoBrowser didDisplayPhotoAtIndex:(NSUInteger)index {
    self.currentCatImageIndex = index;
    [photoBrowser.view addGestureRecognizer:self.longPressGestureRecognizer];// 长按手势识别
    NSLog(@"Did start viewing photo at index %lu", (unsigned long)index);
}

//- (BOOL)photoBrowser:(MWPhotoBrowser *)photoBrowser isPhotoSelectedAtIndex:(NSUInteger)index {
//    return [[_selections objectAtIndex:index] boolValue];
//}

//- (NSString *)photoBrowser:(MWPhotoBrowser *)photoBrowser titleForPhotoAtIndex:(NSUInteger)index {
//    return [NSString stringWithFormat:@"Photo %lu", (unsigned long)index+1];
//}

//- (void)photoBrowser:(MWPhotoBrowser *)photoBrowser photoAtIndex:(NSUInteger)index selectedChanged:(BOOL)selected {
//    [_selections replaceObjectAtIndex:index withObject:[NSNumber numberWithBool:selected]];
//    NSLog(@"Photo at index %lu selected %@", (unsigned long)index, selected ? @"YES" : @"NO");
//}

- (void)photoBrowserDidFinishModalPresentation:(MWPhotoBrowser *)photoBrowser {
    // If we subscribe to this method we must dismiss the view controller ourselves
    NSLog(@"Did finish modal presentation");
    [self dismissViewControllerAnimated:YES completion:nil];
}


- (void) handleLongPressGestures:(UILongPressGestureRecognizer *)paramSender{
    if ([paramSender isEqual:self.longPressGestureRecognizer]){
        
        if (paramSender.state == UIGestureRecognizerStateBegan) {
            
            // 保存照片
            MWPhoto *toSaveImageMWPhoto = self.photos[(int)self.currentCatImageIndex];
            [self mySaveImage:toSaveImageMWPhoto.underlyingImage];
            
        }
        
        NSLog(@"receive long press");
    }
}

- (void)mySaveImage:(UIImage *)image{
    __block NSString *msg = nil ;
    [self.assetslibrary saveImage:image toAlbum:@"PhotoShow" completion:^(NSURL *assetURL, NSError *error) {
        if(!error){
            msg = @"保存图片成功" ;
            NSLog(@"%@",msg);
            
            [MBProgressHUD showSuccess:msg];
            
            dispatch_after(dispatch_time(DISPATCH_TIME_NOW, (int64_t)(0.35 * NSEC_PER_SEC)), dispatch_get_main_queue(), ^{
                // 移除遮盖
                [MBProgressHUD hideHUD];
            });
            
        }
    } failure:^(NSError *error) {
        msg = @"保存图片失败" ;
        NSLog(@"%@",msg);
        
        [MBProgressHUD showError:msg];
        
        dispatch_after(dispatch_time(DISPATCH_TIME_NOW, (int64_t)(0.35 * NSEC_PER_SEC)), dispatch_get_main_queue(), ^{
            // 移除遮盖
            [MBProgressHUD hideHUD];
        });
        
    }];
}

@end
