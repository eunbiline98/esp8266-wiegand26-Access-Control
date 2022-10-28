#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <MFRC522.h>
#include <LiquidCrystal_I2C.h>
#include <Arduino.h>
#include <ArduinoJson.h>

#define SDA_PIN 2
#define RST_PIN 0

const char *SSID = "Padi_solution";
const char *PASS = "s0lut10npadinet";
String serverAdd = "192.168.8.24";
String UIDCard;
String id_device = "4";

int trigger = 0;

LiquidCrystal_I2C lcd(0X27, 16, 2);
MFRC522::MIFARE_Key key;
MFRC522 rfid = MFRC522(SDA_PIN, RST_PIN);

// Config IP Static
// const IPAddress ip = IPAddress(192, 168, 8, 239);
// const IPAddress gateway = IPAddress(192, 168, 8, 1);
// const IPAddress subnet = IPAddress(255, 255, 255, 0);

byte progressBar[8] = {
    B11111,
    B11111,
    B11111,
    B11111,
    B11111,
    B11111,
    B11111,
};

void WiFiSetup()
{
  WiFi.mode(WIFI_STA);
  WiFi.begin(SSID, PASS);
  // WiFi.config(ip, gateway, subnet); // set IP Static

  while (WiFi.waitForConnectResult() != WL_CONNECTED)
  {
    Serial.println("Connection Failed! Rebooting...");
    delay(5000);
    ESP.restart();
  }
}

void doorLock()
{
  // digitalWrite(relay, LOW);
  // delay(1000);
  // digitalWrite(relay, HIGH);
}

void alarm(int time)
{
  for (int i = 1; i <= time; i++)
  {
    for (int c = 1; c <= 3; c++)
    {
      // digitalWrite(beeper, HIGH);
      // delay(200);
      // digitalWrite(beeper, LOW);
      // delay(200);
    }
    delay(1000);
  }
}

void storeData()
{
  WiFiSetup();
  WiFiClient client;
  String address, massage, first_name;

  address = "http://" + serverAdd + "/access-control/webapi/api/create.php?uid=" + UIDCard + "&id_device=" + id_device;
  Serial.print("address : ");
  Serial.println(address);

  HTTPClient http;
  // http.begin(address);
  http.begin(client, address);
  int httpCode = http.GET(); // Send the GET request
  String payload;
  Serial.print("Response: ");
  if (httpCode > 0)
  {
    payload = http.getString();
    payload.trim(); // remove \n character
    if (payload.length() > 0)
    {
      Serial.println(payload + "\n");
    }
  }
  http.end();
  const size_t capacity = JSON_OBJECT_SIZE(4) + 192; // simulate your JSON data https://arduinojson.org/v6/assistant/
  DynamicJsonDocument doc(capacity);
  DeserializationError error = deserializeJson(doc, payload);

  if (error)
  {
    Serial.print(F("deserializeJson() failed: "));
    Serial.println(error.c_str());
    return;
  }

  const char *waktu_res = doc["waktu"];
  String nama_res = doc["nama"];
  const char *uid_res = doc["uid"];
  String status_res = doc["status"];
  String status_dev = doc["id_device"];
  String status_mem = doc["member"];

  // Rules For Door Lock
  if (status_res == "INVALID")
  {
    Serial.println("Access : NOT");
    // alarm(3);
    lcd.setCursor(0, 0);
    lcd.print("Status: Invalid");
    lcd.setCursor(0, 1);
    lcd.print("Please Register");
    delay(2000);
  }
  else
  {
    if (status_mem == "membership" || status_mem == "staff")
    {
      if (status_res == "IN")
      {
        Serial.println("Access : IN  ");
        // doorLock();
        lcd.setCursor(0, 0);
        lcd.print("Status: Allowed  ");
        lcd.setCursor(0, 1);
        lcd.print("Door Unlock      ");
        delay(2000);
      }
      else
      {
        Serial.println("Access : OUT");
        // doorLock();
        lcd.setCursor(0, 0);
        lcd.print("Status: Allowed  ");
        lcd.setCursor(0, 1);
        lcd.print("Door Unlock      ");
        delay(2000);
      }
    }
    else
    {
      lcd.setCursor(0, 0);
      lcd.print("Status: Rejected ");
      lcd.setCursor(0, 1);
      lcd.print("Door Lock        ");
      delay(2000);
    }
  }
  trigger = 0;
  Serial.println("===============================================================");
}

void readUID()
{
  for (byte i = 0; i < 6; i++)
  {
    key.keyByte[i] = 0xFF;
  }

  if (!rfid.PICC_IsNewCardPresent())
  {
    return;
  }

  if (!rfid.PICC_ReadCardSerial())
  {
    return;

    Serial.print(F("PICC type: "));
    MFRC522::PICC_Type piccType = rfid.PICC_GetType(rfid.uid.sak);
    Serial.println(rfid.PICC_GetTypeName(piccType));
  }
  Serial.println();
  UIDCard = "";

  for (byte i = 0; i < 4; i++)
  {
    UIDCard += (rfid.uid.uidByte[i] < 0x10 ? "0" : "") +
               String(rfid.uid.uidByte[i], HEX);
  }
  trigger++;
  UIDCard.toUpperCase();
  rfid.PICC_HaltA();
  rfid.PCD_StopCrypto1();
}

void setup()
{
  Serial.begin(9600);
  lcd.init();
  lcd.backlight();
  SPI.begin();
  rfid.PCD_Init();
  // pinMode(beeper, OUTPUT);
  // pinMode(relay, OUTPUT);

  lcd.createChar(0, progressBar);
  lcd.setCursor(0, 1);
  lcd.print("                   ");
  for (int i = 0; i <= 16; i++)
  {
    lcd.setCursor(0, 0);
    lcd.print("Start System!!!       ");
    lcd.setCursor(i, 1);
    lcd.write(byte(0));
    delay(150);
  }
  lcd.clear();
  WiFiSetup();
  lcd.setCursor(0, 0);
  lcd.print("Ip Address         ");
  lcd.setCursor(0, 1);
  lcd.print(WiFi.localIP());
  delay(5000);
  lcd.clear();
  Serial.print("IP address: ");
  Serial.println(WiFi.localIP());
  Serial.print("MAC Address:  ");
  Serial.println(WiFi.macAddress());
  WiFi.setAutoReconnect(true);
  WiFi.persistent(true);
}

void loop()
{
  readUID();
  if (trigger == 0)
  {
    lcd.setCursor(0, 0);
    lcd.print(" PadiNET Product");
    lcd.setCursor(0, 1);
    lcd.print(" Access Control");
  }
  else if (trigger == 1)
  {
    lcd.setCursor(0, 1);
    lcd.print("                   ");
    for (int i = 0; i <= 16; i++)
    {
      lcd.setCursor(0, 0);
      lcd.print("Reading!!!       ");
      lcd.setCursor(i, 1);
      lcd.write(byte(0));
      delay(55);
    }
    lcd.clear();
    lcd.setCursor(0, 0);
    lcd.print("Waiting...!!!       ");
    storeData();
  }
}
