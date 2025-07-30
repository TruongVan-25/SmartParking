#include <wiringPi.h>
#include <stdio.h>

//#define ENA 22  //enable A on pin 5 (needs to be a pwm pin)
//#define ENB 2  //enable B on pin 3 (needs to be a pwm pin)
#define IN1 9  //IN1 on pin 2 conrtols one side of bridge A
#define IN2 10  //IN2 on pin 4 controls other side of A
#define IN3 22  //IN3 on pin 6 conrtols one side of bridge B
#define IN4 17  //IN4 on pin 7 controls other side of B
int main(void)

{

    wiringPiSetupGpio();

    //pinMode(ENA, OUTPUT);
    //pinMode(ENB, OUTPUT);
    pinMode(IN1, OUTPUT);
    pinMode(IN2, OUTPUT);
    pinMode(IN3, OUTPUT);
    pinMode(IN4, OUTPUT);

    //while(1){
        //int duty = 0;
        //setting IN1 low connects motor lead 1 to ground
        digitalWrite(IN1, LOW);
 
      //setting IN2 high connects motor lead 2 to +voltage
        digitalWrite(IN2, LOW);
 
      //use pwm to control motor speed through enable pin
        //analogWrite(ENA, duty);
        
        digitalWrite(IN3, LOW);
 
      //setting IN2 high connects motor lead 2 to +voltage
        digitalWrite(IN4, LOW);
 
      //use pwm to control motor speed through enable pin
        //analogWrite(ENB, duty);
 
     //   }

    return 0;
    
}
