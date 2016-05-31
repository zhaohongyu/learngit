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

#define KCellIdentifier @"maincell"
#define Kcellmargin 10
#define kCategoryH 40

@interface MainViewController ()<UICollectionViewDelegate, UICollectionViewDataSource>

@property (nonatomic, strong) NSArray *btnsArray;
@property (nonatomic, strong) UICollectionView *collectionView;
@property (nonatomic, strong) NSMutableArray *dataSource;

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
    categorys.arraylist = self.btnsArray;
    categorys.CategoryViewdelegate = self;
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
}

#pragma mark -UICollectionViewDataSource

-(CGSize)collectionView:(UICollectionView *)collectionView layout:(UICollectionViewLayout *)collectionViewLayout sizeForItemAtIndexPath:(NSIndexPath *)indexPath{
    CGSize size;
    size.width = (self.view.width - 3 * Kcellmargin)/2;
    size.height = size.width * 4/3;
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


@end
