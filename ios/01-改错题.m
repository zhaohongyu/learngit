//第1题：类的声明和实现、对象的创建
/*
#import <Foundation/Foundation.h>
@interface Person : NSObject
{
    @public
    int age;
    double height;
}

- (void)study;

@end

@implementation Person
- (void)study
{
    NSLog(@"年龄为%d的人在学习", age);
}
@end

int main()
{
    Person *p = [Person new];
    p->age = 10;
    p->height = 1.78f;
    [p study];
    return 0;
}
 */

//第2题：方法的声明和实现
/*
#import <Foundation/Foundation.h>
@interface Test : NSObject
- (int)addNum1:(int)num1 andNum2:(int)num2;
- (double)pi;
- (void)test;
@end

@implementation Test
- (int)addNum1:(int)num1 andNum2:(int)num2
{
    return num1 + num2;
}

- (double)pi
{
    return 3.14;
}

- (void)test
{
    NSLog(@"我啥也不想说，我就是想输出");
}
@end



int main()
{
    Test *t = [Test new];
    
    int num1 = 10 ;
    int num2 = 20 ;
    
    int res = [t addNum1:num1 andNum2:num2];
    
    NSLog(@"%d+%d = %d",num1,num2,res);
    
    double res_pi = [t pi];
    
    NSLog(@"pi 约等于 %f",res_pi);
    
    [t test];
    
    return 0;
}
*/
///*

//第3题：方法和函数的区别
#import <Foundation/Foundation.h>
@interface Car : NSObject
{
    @public
    int wheels;
}

- (void)run;
- (void)test;
@end

@implementation Car
- (void)test
{
    NSLog(@"测试一下车子：%i", wheels);
}

- (void)run
{
    NSLog(@"%i个轮子的车跑起来了", wheels);
}
@end

void haha()
{
    NSLog(@"调用了haha");
}

int main()
{
    Car *c = [Car new];
    c->wheels = 4;
    [c run];
    [c test];
    haha();
    return 0;
}
//*/




