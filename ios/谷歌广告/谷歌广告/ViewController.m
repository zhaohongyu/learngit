//
//  ViewController.m
//  谷歌广告
//
//  Created by 赵洪禹 on 16/8/9.
//  Copyright © 2016年 zhaohongyu. All rights reserved.
//

#import "ViewController.h"

@interface ViewController ()

@property (weak, nonatomic) IBOutlet GADBannerView *bannerView;// 横幅

@property(nonatomic, strong) GADInterstitial *interstitial;// 插页

- (IBAction)showMainAd:(id)sender;

@end

@implementation ViewController

- (void)viewDidLoad {
    [super viewDidLoad];
    // [self showBannerView];// 横幅广告
    self.interstitial = [self createAndLoadInterstitial];// 插页广告
}

- (void)gameOver {
    if ([self.interstitial isReady]) {
        [self.interstitial presentFromRootViewController:self];
    }
    // Rest of game over logic goes here.
}


- (GADInterstitial *)createAndLoadInterstitial {
    GADInterstitial *interstitial =
    [[GADInterstitial alloc] initWithAdUnitID:@"ca-app-pub-3940256099942544/4411468910"];
    interstitial.delegate = self;
    GADRequest *request = [GADRequest request];
    request.testDevices = @[ kGADSimulatorID ];
    [interstitial loadRequest:request];
    return interstitial;
}

/**
 显示横幅式广告
 */
-(void)showBannerView{
    self.bannerView.delegate = self;
    // Replace this ad unit ID with your own ad unit ID.
    self.bannerView.adUnitID = @"ca-app-pub-4200152447911751/2208902825";
    self.bannerView.rootViewController = self;
    self.bannerView.adSize = kGADAdSizeSmartBannerPortrait;
    GADRequest *request = [GADRequest request];
    request.testDevices = @[ kGADSimulatorID ];
    [self.bannerView loadRequest:request];
}


#pragma mark -

#pragma mark GADBannerView广告代理

//:在 loadRequest 时发送：已成功。这是将发送者添加到视图层次的好机会（如果到目前为止已隐藏视图层次），例如
- (void)adViewDidReceiveAd:(GADBannerView *)bannerView {
    bannerView.hidden = NO;
}

//当 loadRequest: 失败时发送，通常是网络故障、应用配置错误或缺少广告库存导致的。您可能希望记录这些事件以进行调试
- (void)adView:(GADBannerView *)adView didFailToReceiveAdWithError:(GADRequestError *)error {
    HYLog(@"adView:didFailToReceiveAdWithError: %@", error.localizedDescription);
}


#pragma mark -

#pragma mark GADInterstitial广告代理

/// Called when an interstitial ad request succeeded. Show it at the next transition point in your
/// application such as when transitioning between view controllers.
- (void)interstitialDidReceiveAd:(GADInterstitial *)ad{
    HYLog(@"%s",__func__);
}

/// Called when an interstitial ad request completed without an interstitial to
/// show. This is common since interstitials are shown sparingly to users.
- (void)interstitial:(GADInterstitial *)ad didFailToReceiveAdWithError:(GADRequestError *)error{
    HYLog(@"%s",__func__);
}

#pragma mark Display-Time Lifecycle Notifications

/// Called just before presenting an interstitial. After this method finishes the interstitial will
/// animate onto the screen. Use this opportunity to stop animations and save the state of your
/// application in case the user leaves while the interstitial is on screen (e.g. to visit the App
/// Store from a link on the interstitial).
- (void)interstitialWillPresentScreen:(GADInterstitial *)ad{
    HYLog(@"%s",__func__);
    
}

/// Called when |ad| fails to present.
- (void)interstitialDidFailToPresentScreen:(GADInterstitial *)ad{
    HYLog(@"%s",__func__);

}

/// Called before the interstitial is to be animated off the screen.
- (void)interstitialWillDismissScreen:(GADInterstitial *)ad{
    HYLog(@"%s",__func__);
}

- (void)interstitialDidDismissScreen:(GADInterstitial *)interstitial {
    self.interstitial = [self createAndLoadInterstitial];
}

/// Called just before the application will background or terminate because the user clicked on an
/// ad that will launch another application (such as the App Store). The normal
/// UIApplicationDelegate methods, like applicationDidEnterBackground:, will be called immediately
/// before this.
- (void)interstitialWillLeaveApplication:(GADInterstitial *)ad{
    HYLog(@"%s",__func__);
}


- (IBAction)showMainAd:(id)sender {
    [self gameOver];
}
@end
