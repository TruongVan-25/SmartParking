#include <wiringPi.h>
#include <stdio.h>
#include <signal.h>
#include <unistd.h>

//#define ENA 11  //enable A on pin 5 (needs to be a pwm pin)
//#define ENB 17 //enable B on pin 3 (needs to be a pwm pin)
#define IN1 9  //IN1 on pin 2 conrtols one side of bridge A
#define IN2 10  //IN2 on pin 4 controls other side of A
#define IN3 22  //IN3 on pin 6 conrtols one side of bridge B
#define IN4 17  //IN4 on pin 7 controls other side of B


void sig_handler(int signo)
{ 
	if(signo == SIGINT) {
		printf("received Ctr+C \n");
		digitalWrite(IN1, LOW);
	//	pinMode(IN1, OUTPUT);
        
        digitalWrite(IN2, LOW);
	//	pinMode(IN2, OUTPUT);
        
        digitalWrite(IN3, LOW);
	//	pinMode(IN3, OUTPUT);
        
        digitalWrite(IN4, LOW);
	//	pinMode(IN4, OUTPUT);
	}
}

int main(void)

{

    wiringPiSetupGpio();

    //pinMode(ENA, OUTPUT);
    //pinMode(ENB, OUTPUT);
    pinMode(IN1, OUTPUT);
    pinMode(IN2, OUTPUT);
    pinMode(IN3, OUTPUT);
    pinMode(IN4, OUTPUT);

  //  if (signal (SIGINT, sig_handler) == SIG_ERR)
	//{
//		printf("\n can't catch Ctr+C \n");
//	}
    
  //  while(1){
       
        //setting IN1 low connects motor lead 1 to ground
        digitalWrite(IN1, HIGH);
 
      //setting IN2 high connects motor lead 2 to +voltage
        digitalWrite(IN2, LOW);
 
      //use pwm to control motor speed through enable pin
        //analogWrite(ENA, 100);
        
        digitalWrite(IN3, LOW);
 
      //setting IN2 high connects motor lead 2 to +voltage
        digitalWrite(IN4, HIGH);
 
      //use pwm to control motor speed through enable pin
        //analogWrite(ENB, 100);
 
     //   }
        

    return 0;
    
}
