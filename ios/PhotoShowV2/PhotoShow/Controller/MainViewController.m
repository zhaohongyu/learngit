//
//  MainViewController.m
//  PhotoShow
//
//  Created by 沈健 on 16/5/22.
//  Copyright © 2016年 shenjian. All rights reserved.
//

#import "MainViewController.h"
#import "MainCollectionViewCell.h"
#import "MJRefresh.h"
#import "HYBNetworking.h"
#import "CategoryView.h"
#import "PSSubListModel.h"
#import "PSImgDetailListController.h"
#import "MWPhotoBrowser.h"
#import "MBProgressHUD+SJ.h"
#import "PSImgDetailModel.h"

#define ImgDetailBaseUrl @"http://123.206.61.52/imgDetailList?href="

#define KCellIdentifier @"maincell"
#define Kcellmargin 1
#define kCategoryH 4

@interface MainViewController ()<UICollectionViewDelegate, UICollectionViewDataSource,MWPhotoBrowserDelegate,CategoryViewDelegate>

@property (nonatomic, strong) NSArray *btnsArray;
@property (nonatomic, strong) UICollectionView *collectionView;
@property (nonatomic, strong) NSMutableArray *dataSource;

@property (nonatomic, strong) NSMutableArray *ImgArray;
@property (nonatomic, strong) NSMutableArray *photos;
@property (nonatomic, strong) NSMutableArray *thumbs;

@end

@implementation MainViewController

- (UICollectionView *)collectionView{
    if (!_collectionView) {
        UICollectionViewFlowLayout *flowLayout = [[UICollectionViewFlowLayout alloc]init];
        _collectionView = [[UICollectionView alloc]initWithFrame:CGRectMake(0, kCategoryH + 64, self.view.width, self.view.height - ADViewHieght- kCategoryH) collectionViewLayout:flowLayout];
        [_collectionView registerClass:[MainCollectionViewCell class] forCellWithReuseIdentifier:KCellIdentifier];
        _collectionView.backgroundColor = gbColor;
        _collectionView.delegate = self;
        _collectionView.dataSource = self;
    }
    return _collectionView;
}

- (NSMutableArray *)dataSource{
    if (!_dataSource) {
        _dataSource = [NSMutableArray array];
    }
    return _dataSource;
}

- (NSArray *)btnsArray{
    if (!_btnsArray) {
        categoryItem *siwaItem = [categoryItem itemWithTitle:@"丝袜美腿" en:@"siwameitui"];
        categoryItem *xingganItem = [categoryItem itemWithTitle:@"性感美女" en:@"xingganmote"];
        categoryItem *weimeiItem = [categoryItem itemWithTitle:@"唯美写真" en:@"weimeixiezhen"];
        categoryItem *wangluoItem = [categoryItem itemWithTitle:@"网络美女" en:@"wangluomeinv"];
        categoryItem *gaoqinItem = [categoryItem itemWithTitle:@"高清美女" en:@"gaoqingmeinv"];
        categoryItem *moteItem = [categoryItem itemWithTitle:@"模特美女" en:@"motemeinv"];
        categoryItem *tiyuItem = [categoryItem itemWithTitle:@"体育美女" en:@"tiyumeinv"];
        _btnsArray = [NSArray arrayWithObjects:siwaItem,xingganItem,weimeiItem,wangluoItem,gaoqinItem,moteItem,tiyuItem,nil];
    }
    return _btnsArray;
}

- (NSMutableArray *)ImgArray{
    if (!_ImgArray) {
        _ImgArray = [NSMutableArray array];
    }
    return _ImgArray;
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

- (void)viewDidLoad {
    [super viewDidLoad];
    self.automaticallyAdjustsScrollViewInsets = NO;
    [self addCategoryView];
    
    [self addCollectVC];
//
    [self addMJRefresh];
//
//    [self addGoogleAD];
}

- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
}

- (void)addCategoryView{
    CategoryView *categorys = [[CategoryView alloc]initWithFrame:CGRectMake(0, 64, self.view.width, kCategoryH)];
    categorys.CategoryViewdelegate = self;
    categorys.arraylist = self.btnsArray;
    
    [self.view addSubview:categorys];
}

- (void)fakeData{
    NSString *plistPath = [[NSBundle mainBundle] pathForResource:@"FakeData" ofType:@"plist"];
    self.dataSource = [[NSMutableArray alloc] initWithContentsOfFile:plistPath];
}

- (void)addMJRefresh{
//    __weak __typeof(self) weakSelf = self;
    
    // 设置回调（一旦进入刷新状态就会调用这个refreshingBlock）
    self.collectionView.mj_header = [MJRefreshNormalHeader headerWithRefreshingBlock:^{
//        [weakSelf loadData];
        [self.collectionView.mj_header endRefreshing];
    }];
    
    [self.collectionView.mj_header beginRefreshing];
    
//    self.collectionView.footer = [MJRefreshBackNormalFooter footerWithRefreshingTarget:self refreshingAction:@selector(loadMoreData)];
}

- (void)loadDataWithCategory:(NSString *)Categoryen{
    [HYBNetworking enableInterfaceDebug:YES];
    [HYBNetworking cacheGetRequest:YES shoulCachePost:YES];
    
    NSString *baseUrl = @"http://123.206.61.52/imgSubList";
    NSString *url = [NSString stringWithFormat:@"%@/%@/1",baseUrl,Categoryen];
    
    [HYBNetworking getWithUrl:url
                 refreshCache:YES
                      success:^(id response) {
                          
                          NSArray *responseArray = response[@"data"];
                          
                          NSArray *modelArray = [NSArray yy_modelArrayWithClass:[PSSubListModel class] json:responseArray];
                          
                          self.dataSource = [NSMutableArray arrayWithArray:modelArray];
                          
                          [self.collectionView reloadData];
                          [self.collectionView.mj_header endRefreshing];
                      } fail:^(NSError *error) {
//                          HYBAppLog(@"error ----  %@",error);
                          [self.collectionView.mj_header endRefreshing];
                      }];
}

- (void)addCollectVC{
    [self.view addSubview:self.collectionView];
}

// 添加谷歌广告
- (void)addGoogleAD{
    UIView *view = [[UIView alloc]initWithFrame:CGRectMake(0, self.view.height - ADViewHieght, self.view.width, ADViewHieght)];
    view.backgroundColor = [UIColor yellowColor];
    [self.view addSubview:view];
}

#pragma mark -UICollectionViewDataSource
- (NSInteger)numberOfSectionsInCollectionView:(UICollectionView *)collectionView {
    return 1;
}

- (NSInteger)collectionView:(UICollectionView *)collectionView numberOfItemsInSection:(NSInteger)section {
//    return self.dataSource.count;
    if (self.dataSource.count == 0) {
        return 0;
    }else{
        return self.dataSource.count;
    }
}

-(UICollectionViewCell *)collectionView:(UICollectionView *)collectionView cellForItemAtIndexPath:(NSIndexPath *)indexPath
{
    MainCollectionViewCell *cell = [collectionView dequeueReusableCellWithReuseIdentifier:KCellIdentifier forIndexPath:indexPath];

    cell.model = self.dataSource[indexPath.item];
    return cell;
}

-(void)collectionView:(UICollectionView *)collectionView didSelectItemAtIndexPath:(NSIndexPath *)indexPath
{
    [collectionView deselectItemAtIndexPath:indexPath animated:YES];
    PSSubListModel *model = self.dataSource[indexPath.item];
//    PSImgDetailListController *detailVC = [[PSImgDetailListController alloc]init];
//    detailVC.href = model.href;
//    [self.navigationController pushViewController:detailVC animated:YES];
    
    [self loadImgDetailWith:model.href];
    
    }

#pragma mark -UICollectionViewDataSource

-(CGSize)collectionView:(UICollectionView *)collectionView layout:(UICollectionViewLayout *)collectionViewLayout sizeForItemAtIndexPath:(NSIndexPath *)indexPath{
    CGSize size;
    size.width = (self.view.width - 3 * Kcellmargin)/2;
    size.height = size.width * 5/3;
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

#pragma mark -- categoryViewdelegate
- (void)categoryView:(CategoryView *)CategoryView didClickBtnAtIndex:(NSInteger)index{
    categoryItem *item = self.btnsArray[index];
    NSLog(@"item --- %@",item.categoryEn);
    [self loadDataWithCategory:item.categoryEn];
}

- (void)loadImgDetailWith:(NSString *)urlStr{
    [MBProgressHUD showHUD];
    [HYBNetworking enableInterfaceDebug:YES];
    [HYBNetworking cacheGetRequest:YES shoulCachePost:YES];
    
    NSString *url = [NSString stringWithFormat:@"%@%@",ImgDetailBaseUrl,urlStr];
    
    __weak __typeof(self)weakSelf = self;
    [HYBNetworking postWithUrl:url
                  refreshCache:YES
                        params:nil
                       success:^(id response) {
                           [MBProgressHUD hideHUD];
                           NSArray *responseArray = response[@"data"];
                           
                           NSArray *modelArray = [NSArray yy_modelArrayWithClass:[PSImgDetailModel class] json:responseArray];
                           
                           weakSelf.ImgArray = [NSMutableArray arrayWithArray:modelArray];
                           [weakSelf loadPhtotBrowserWith:self.ImgArray];
                       }
                          fail:^(NSError *error) {
                              [MBProgressHUD hideHUD];
                          }];
}

- (void)loadPhtotBrowserWith:(NSMutableArray *)array{
    
    NSMutableArray *photos = [[NSMutableArray alloc] init];
    NSMutableArray *thumbs = [[NSMutableArray alloc] init];
    MWPhoto *photo;
    BOOL displayActionButton = YES;
    BOOL displaySelectionButtons = NO;
    BOOL displayNavArrows = YES;
    BOOL enableGrid = YES;
    BOOL startOnGrid = YES;
    BOOL autoPlayOnAppear = NO;
    
    for (PSImgDetailModel *model in array) {
        photo = [MWPhoto photoWithURL:[NSURL URLWithString:model.imgUrl]];
        [photos addObject:photo];
        [thumbs addObject:photo];
    }
    self.photos = photos;
    self.thumbs = thumbs;
    
    // Create browser
    MWPhotoBrowser *browser = [[MWPhotoBrowser alloc] initWithDelegate:self];
    browser.displayActionButton = displayActionButton;
    browser.displayNavArrows = displayNavArrows;
    browser.displaySelectionButtons = displaySelectionButtons;
    browser.alwaysShowControls = displaySelectionButtons;
    browser.zoomPhotosToFill = YES;
    browser.enableGrid = enableGrid;
    browser.startOnGrid = startOnGrid;
    browser.enableSwipeToDismiss = NO;
    browser.autoPlayOnAppear = autoPlayOnAppear;
    [browser setCurrentPhotoIndex:0];
    
    UINavigationController *nc = [[UINavigationController alloc] initWithRootViewController:browser];
    nc.modalTransitionStyle = UIModalTransitionStyleCrossDissolve;
    [self presentViewController:nc animated:YES completion:nil];
}

#pragma mark - MWPhotoBrowserDelegate

- (NSUInteger)numberOfPhotosInPhotoBrowser:(MWPhotoBrowser *)photoBrowser {
    return _photos.count;
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


@end
