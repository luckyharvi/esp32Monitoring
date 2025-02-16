#include <WiFi.h>
#include <MQTT.h>
#include <ESP32Servo.h>
#include <NusabotSimpleTimer.h>
#include "DHTesp.h"

WiFiClient net;
MQTTClient client;
Servo servo;
NusabotSimpleTimer timer;
DHTesp dhtSensor;

const char ssid[] = "...";//input ssid
const char pass[] = "...";//input password

// Pin definitions
const int pinRed = 16;
const int pinGreen = 4;
const int pinBlue = 2;
const int pinLED1 = 18;
const int pinLED2 = 19;
const int pinServo = 13;
const int pinPot = 34;
const int pinDHT = 25;

int pot, oldPot = 0; // Stores current and previous potentiometer values
String serial_number = "0123456789";
unsigned long lastReconnectAttempt = 0;

void setup() {
  Serial.begin(115200);
  
  // Pin setup
  pinMode(pinRed, OUTPUT);
  pinMode(pinGreen, OUTPUT);
  pinMode(pinBlue, OUTPUT);
  pinMode(pinLED1, OUTPUT);
  pinMode(pinLED2, OUTPUT);
  servo.attach(pinServo, 500, 2400);
  pinMode(pinPot, INPUT);
  dhtSensor.setup(pinDHT, DHTesp::DHT22);

  // Wi-Fi and MQTT setup
  WiFi.begin(ssid, pass);
  client.begin("192.168.1.12", net);//just need to enter di ip address
  client.onMessage(subscribe);

  // Timer setup
  timer.setInterval(1000, publishPot);
  timer.setInterval(2000, publishDHT);

  connect(); // Initial connection attempt
}

void loop() {
  client.loop();
  timer.run();

  // Check if MQTT is disconnected and reconnect if needed
  if (!client.connected() && millis() - lastReconnectAttempt > 5000) {
    lastReconnectAttempt = millis();
    connect();
  }
}

void subscribe(String &topic, String &data) {
  Serial.println("Received message on topic: " + topic + " -> " + data);

  if (topic == "luckyharvi/" + serial_number + "/led/1") {
    digitalWrite(pinLED1, data == "on" ? HIGH : LOW);
  } else if (topic == "luckyharvi/" + serial_number + "/led/2") {
    digitalWrite(pinLED2, data == "on" ? HIGH : LOW);
  } else if (topic == "luckyharvi/" + serial_number + "/servo") {
    servo.write(data.toInt());
  }
}

void publishPot() {
  pot = analogRead(pinPot);
  float potPercent = (float(pot) / 4095.0) * 100.0;

  if (pot != oldPot) {
    client.publish("luckyharvi/" + serial_number + "/potensiometer", String(potPercent, 2), true, 1);
    oldPot = pot;
  }
}

void publishDHT() {
  TempAndHumidity data = dhtSensor.getTempAndHumidity();

  client.publish("luckyharvi/" + serial_number + "/suhu", String(data.temperature, 2), true, 1);
  client.publish("luckyharvi/" + serial_number + "/kelembapan", String(data.humidity, 2), true, 1);
}

void rgb(bool red, bool green, bool blue) {
  digitalWrite(pinRed, red);
  digitalWrite(pinGreen, green);
  digitalWrite(pinBlue, blue);
}

void connect() {
  Serial.println("Connecting to Wi-Fi...");
  rgb(1, 0, 0); // Red for Wi-Fi connection attempt

  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("\nWi-Fi connected!");
  rgb(0, 1, 0); // Green for MQTT connection attempt

  client.setWill("luckyharvi/status/0123456789", "offline", true, 1);
  Serial.println("Connecting to MQTT...");
  
  while (!client.connect("klienidunik")) { //also put the username and password if set
    delay(500);
    Serial.print(".");
  }
  Serial.println("\nMQTT connected!");
  rgb(0, 0, 1); // Blue for successful connections

  client.publish("luckyharvi/status/0123456789", "online", true, 1);
  client.subscribe("luckyharvi/#", 1);
}
