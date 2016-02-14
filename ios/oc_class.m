#import <Foundation/Foundation.h>


@interface Car : NSObject
{
    @public
    int wheels;
    int speed;
}

- (void)run;


@end


@implementation Car

- (void)run{
    NSLog(@"哈哈哈终于能跑了.");
}

@end

int main(){
    // NSLog(@"Hello World");
    
    Car *p = [Car new];
    Car *pp = [Car new];
    
    p->wheels = 4;
    p->speed = 200;
    
    pp->wheels = 2;
    pp->speed = 15;
    
    NSLog(@"car1 有%i轮子,速度是%ikm/h",p->wheels,p->speed);
    NSLog(@"car2 有%i轮子,速度是%ikm/h",pp->wheels,pp->speed);
    
    [p run];
    [pp run];

    
    
    return 0;
}