#include <ESP8266WiFi.h>
#include <WiFiClient.h>
#include <Servo.h>
#include <Wire.h>
#include <LiquidCrystal_I2C.h>

// // WiFi Credentials
const char* ssid = "CyberCenter";
const char* password = "";

// TCP Server Settings
const char* serverHost = "172.16.10.188"; // PC IP/ Server ip
const int serverPort = 30000; // TCP port

// Set a static IP address (adjust for your network)
IPAddress local_IP(172, 16, 10, 172);
IPAddress gateway(172, 16, 10, 1);
IPAddress subnet(255, 255, 255, 0);

#define TOTAL_SLOTS 2

// IR Sensor Pins
#define ENTRY_SENSOR D3
#define EXIT_SENSOR D5
const int sensorPins[] = { D0, D4 }; // slot 1 (A1), slot 2 (B1)

// Servo Motors
Servo entryGate, exitGate;

// LCD Display
LiquidCrystal_I2C lcd(0x27, 16, 2);

int availableSpots = 2;
bool slotOccupied[] = { false, false };
bool carEntering = false;
bool carExiting = false;

bool entryGateOpen = false;
bool exitGateOpen = false;
bool rfidAuthorized = false;  // Cờ kiểm tra RFID hợp lệ

unsigned long entryGateActionTime = 0;
unsigned long exitGateActionTime = 0;
bool isEntryGateOpening = false;
bool isExitGateOpening = false;

#define OPEN_ANGLE 180
#define CLOSE_ANGLE 0

WiFiClient tcpClient;

unsigned long lastTcpAttempt = 0;
const unsigned long tcpRetryInterval = 5000; // thử lại mỗi 5 giây

void setup() {
  Serial.begin(9600); // Nhận dữ liệu từ Arduino Uno
  // Serial.begin(115200);
  WiFi.setAutoReconnect(true);
  WiFi.persistent(true);
  WiFi.begin(ssid, password);
  connectWiFi();

  pinMode(ENTRY_SENSOR, INPUT);
  pinMode(EXIT_SENSOR, INPUT);
  for (int i = 0; i < 2; i++) {
    pinMode(sensorPins[i], INPUT);
  }

  entryGate.attach(D6);
  exitGate.attach(D7);
  entryGate.write(CLOSE_ANGLE);
  exitGate.write(CLOSE_ANGLE);

  lcd.init();
  lcd.backlight();
  lcd.setCursor(0, 0);
  lcd.print("Smart Parking");

  Serial.println("System Initialized.");
  updateLCD();
}

void loop() {
  if (WiFi.status() != WL_CONNECTED) {
    connectWiFi();
  }
  // if (Serial.available()) {
  //   String s = Serial.readStringUntil('\n');
  //   s.trim();
  //   if(s == "1"){
  //     Serial.println(WiFi.localIP());
  //   }
  // }
  
  checkRFID();
  checkParkingSlots();
  handleEntry();
  handleExit();
  // connectTCP();
  // handleTCPCommunication();
  // connectTCP();
  // Kiểm tra timeout sau khi xử lý các lệnh mới
  if (isEntryGateOpening && (millis() - entryGateActionTime >= 3000)) {
    closeEntryGate();
    isEntryGateOpening = false;
  }
  
  if (isExitGateOpening && (millis() - exitGateActionTime >= 3000)) {
    closeExitGate();
    isExitGateOpening = false;
  }
  
  delay(200); // Delay ngắn để giảm tải CPU
}

void connectWiFi() {
  // static IP set
  if (!WiFi.config(local_IP, gateway, subnet)) {
    Serial.println("Static IP Configuration Failed");
  }
  
  if (WiFi.status() == WL_CONNECTED) return;
  
  Serial.print("Connecting to WiFi: ");
  Serial.println(ssid);

  WiFi.begin(ssid, password);
  int timeout = 20;

  while (WiFi.status() != WL_CONNECTED && timeout > 0) {
    Serial.print(".");
    delay(1000);
    timeout--;
  }

  if (WiFi.status() == WL_CONNECTED) {
    Serial.println("\nWiFi Connected");
    Serial.print("IP Address: ");
    Serial.println(WiFi.localIP());

  } else {
    Serial.println("\nFailed to connect to WiFi!");
  }
}

// void checkRFID() {
//   if (Serial.available()) {
//     String rfidData = Serial.readStringUntil('\n');
//     rfidData.trim();

//     // Chỉ xử lý dữ liệu hợp lệ
//     if (rfidData == "<RFID:OK>") {
//       rfidAuthorized = true;
//       Serial.println("RFID Accepted");
//     } 
//     else if (rfidData == "<RFID:FAIL>") {
//       rfidAuthorized = false;
//       Serial.println("RFID Not Allowed");
//     }
//     // Bỏ qua dữ liệu khác
//   }
// }

void checkRFID() {
  if (Serial.available()) {
  String uid = Serial.readStringUntil('\n');
  uid.trim();

  if (uid.length() > 0) {
    Serial.println("RFID UID: " + uid);

    // Xóa tiền tố "UID:" nếu có
    uid.replace("UID:", "");
    uid.trim();

    // Thay khoảng trắng bằng dấu gạch hoặc bỏ khoảng trắng
    uid.replace(" ", "");

    // Gửi UID lên server
    sendRFIDToServer(uid);

    rfidAuthorized = true; // VD set rfid đã nhận diện là ok
  }
}

}


void checkParkingSlots() {
  int occupiedSlots = 0;
  for (int i = 0; i < 2; i++) {
    bool currentState = digitalRead(sensorPins[i]) == LOW;
    if (currentState != slotOccupied[i]) {
      slotOccupied[i] = currentState;
      // sendSlotStatusToServer(i, currentState);///////////////////////////
    }
    if (currentState) occupiedSlots++;
  }

  int newAvailableSpots = 2 - occupiedSlots;
  if (newAvailableSpots != availableSpots) {
    availableSpots = newAvailableSpots;
    updateLCD();
  }
}

void handleEntry() {
  if (digitalRead(ENTRY_SENSOR) == LOW && !carEntering && availableSpots > 0 && rfidAuthorized) {
    Serial.println("Car detected at entry + RFID OK. Opening barrier...");
    carEntering = true;
    sendGateStatus("ENTRY_OPEN");
    entryGate.write(OPEN_ANGLE);
    delay(3000);
    entryGate.write(CLOSE_ANGLE);
    sendGateStatus("ENTRY_CLOSE");
    delay(2000);
    carEntering = false;
    rfidAuthorized = false; // Reset sau khi mở
  }
}

void handleExit() {
  if (digitalRead(EXIT_SENSOR) == LOW && !carExiting && rfidAuthorized) {
    Serial.println("Car detected at exit + RFID OK. Opening barrier...");
    carExiting = true;
    sendGateStatus("EXIT_OPEN");
    exitGate.write(OPEN_ANGLE);
    delay(3000);
    exitGate.write(CLOSE_ANGLE);
    sendGateStatus("EXIT_CLOSE");
    delay(2000);
    carExiting = false;
    rfidAuthorized = false; // Reset sau khi mở
  }
}


// void sendSlotStatusToServer(int slotIndex, bool isOccupied) {
//   if (WiFi.status() != WL_CONNECTED) {
//     Serial.println("WiFi not connected, cannot send data.");
//     return;
//   }

//   if (!tcpClient.connected()) {
//     if (!tcpClient.connect(serverHost, serverPort)) {
//       Serial.println("TCP connection failed");
//       return;
//     }
//   }

//   String tcpMessage = (slotIndex == 0 ? "A1_" : "B1_") + String(isOccupied ? "IN" : "OUT");
//   tcpClient.println(tcpMessage);
//   Serial.println("✅ TCP Sent: " + tcpMessage);
// }

// void connectTCP() {
//   if (tcpClient.connected()) return; // Nếu đã kết nối thì không làm gì

//   unsigned long now = millis();
//   if (now - lastTcpAttempt < tcpRetryInterval) return; // chỉ thử lại sau 5s
//   lastTcpAttempt = now;

//   tcpClient.stop();
//   if (tcpClient.connect(serverHost, serverPort)) {
//     Serial.println("TCP Connected");
//   } else {
//     Serial.println("TCP Connect failed, offline mode active");
//   }
// }


// void handleTCPCommunication() {
//   // Xử lý dữ liệu đến
//   while (tcpClient.available()) {
//     String command = tcpClient.readStringUntil('\n');
//     command.trim();
//     processCommand(command);
//   }
// }

void processCommand(String command) {
  Serial.println("Received: " + command);
  
  if (command == "OPEN_ENTRY_GATE") {
    openEntryGate();
    isEntryGateOpening = true;
    entryGateActionTime = millis(); // Dùng biến riêng cho cổng vào
  } 
  else if (command == "CLOSE_ENTRY_GATE") {
    if (isEntryGateOpening) {
      closeEntryGate();
      isEntryGateOpening = false; // Hủy bỏ timeout nếu đóng thủ công
    }
  }
  else if (command == "OPEN_EXIT_GATE") {
    openExitGate();
    isExitGateOpening = true;
    exitGateActionTime = millis(); // Dùng biến riêng cho cổng ra
  }
  else if (command == "CLOSE_EXIT_GATE") {
    if (isExitGateOpening) {
      closeExitGate();
      isExitGateOpening = false; // Hủy bỏ timeout nếu đóng thủ công
    }
  }
}

void openEntryGate() {
  if (!entryGateOpen) {
    entryGate.write(OPEN_ANGLE);
    entryGateOpen = true;
    sendGateStatus("ENTRY_OPEN");
    sendGateLog("ENTRY", "OPEN", "RFID"); // Ghi log tự động
  }
}

void closeEntryGate() {
  if (entryGateOpen) {
    entryGate.write(CLOSE_ANGLE);
    entryGateOpen = false;
    sendGateStatus("ENTRY_CLOSE");
    sendGateLog("ENTRY", "CLOSE", "RFID"); // Ghi log tự động
  }
}

void openExitGate() {
  if (!exitGateOpen) {
    exitGate.write(OPEN_ANGLE);
    exitGateOpen = true;
    sendGateStatus("EXIT_OPEN");
    sendGateLog("EXIT", "OPEN", "RFID"); // Ghi log tự động
  }
}

void closeExitGate() {
  if (exitGateOpen) {
    exitGate.write(CLOSE_ANGLE);
    exitGateOpen = false;
    sendGateStatus("EXIT_CLOSE");
    sendGateLog("EXIT", "CLOSE", "RFID"); // Ghi log tự động
  }
}

void sendGateStatus(String status) {
  if (tcpClient.connected()) {
    tcpClient.println(status);
    Serial.println("Sent: " + status);
  }
}

void updateLCD() {
  lcd.clear();
  lcd.setCursor(0, 0);
  lcd.print("Smart Parking");
  lcd.setCursor(0, 1);
  lcd.print("Available: ");
  
  String availableSpots = "";
  bool firstSpot = true; // Biến đánh dấu slot đầu tiên
  
  for (int i = 0; i < TOTAL_SLOTS; i++) {
    if (!slotOccupied[i]) { // Nếu slot trống
      if (!firstSpot) {
        availableSpots += ",";
        lcd.print(",");
      }
      int position = i + 1; // Slot bắt đầu từ 1
      availableSpots += String(position);
      lcd.print(position);
      firstSpot = false;
    }
  }
  
  if (firstSpot) { // Nếu không có slot nào trống
    lcd.print("None");
    availableSpots = "None";
  }
  
  Serial.print("Available : ");
  Serial.println(availableSpots);
}

const char* host = "172.16.10.118"; // IP server PHP/MySQL
const int httpPort = 80;

void sendRFIDToServer(String uid) {
  if (WiFi.status() != WL_CONNECTED) return;

  WiFiClient client;
  if (!client.connect(host, httpPort)) {
    Serial.println("Connection failed");
    return;
  }

  // Đảm bảo UID không có khoảng trắng hoặc ký tự lạ
  String url = "/robotic/insert.php?rfid=" + uid;
  Serial.println("dscvxbc"+ uid);
  
  client.print(String("GET ") + url + " HTTP/1.1\r\n" +
               "Host: " + host + "\r\n" +
               "Connection: close\r\n\r\n");

  while (client.connected() || client.available()) {
    if (client.available()) {
      String line = client.readStringUntil('\n');
      Serial.println(line);
    }
  }
}

void sendGateLog(String gate, String action, String by) {
  if (WiFi.status() != WL_CONNECTED) return;

  WiFiClient client;
  if (!client.connect(host, httpPort)) {
    Serial.println("GateLog: Connection failed");
    return;
  }

  String url = "/robotic/gate_log.php?gate=" + gate + "&action=" + action + "&by=" + by;
  client.print(String("GET ") + url + " HTTP/1.1\r\n" +
               "Host: " + host + "\r\n" +
               "Connection: close\r\n\r\n");

  Serial.println("GateLog sent: " + gate + " - " + action + " - " + by);
}














