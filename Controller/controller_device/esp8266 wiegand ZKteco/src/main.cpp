#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <Wiegand.h>
#include <Arduino.h>
#include <ArduinoJson.h>

#define W0 5
#define W1 4
#define beeper 14
#define relay 16

const char *SSID = "Padi_solution";
const char *PASS = "s0lut10npadinet";
String serverAdd = "192.168.8.24";
String UIDCard;
String id_device = "1";

WIEGAND wg;

// Config IP Static
// const IPAddress ip = IPAddress(192, 168, 8, 239);
// const IPAddress gateway = IPAddress(192, 168, 8, 1);
// const IPAddress subnet = IPAddress(255, 255, 255, 0);

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
  digitalWrite(relay, LOW);
  delay(1000);
  digitalWrite(relay, HIGH);
}

void alarm(int time)
{
  for (int i = 1; i <= time; i++)
  {
    for (int c = 1; c <= 3; c++)
    {
      digitalWrite(beeper, HIGH);
      delay(200);
      digitalWrite(beeper, LOW);
      delay(200);
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
  const size_t capacity = JSON_OBJECT_SIZE(4) + 194; // simulate your JSON data https://arduinojson.org/v6/assistant/
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
    alarm(3);
  }
  else
  {
    if (status_mem == "meetingroom" || status_mem == "staff")
    {
      if (status_res == "IN")
      {
        Serial.println("Access : IN");
        doorLock();
      }
      else
      {
        Serial.println("Access : OUT");
        doorLock();
      }
    }
    else
    {
      alarm(3);
    }
  }
  Serial.println("===============================================================");
}

void readUID()
{
  if (wg.available())
  {
    // Serial.print("Wiegand HEX = ");
    // Serial.print(wg.getCode(), HEX);
    // Serial.print(", DECIMAL = ");
    // Serial.print(wg.getCode());
    // Serial.print(", Type W");
    // Serial.println(wg.getWiegandType());

    UIDCard = String(wg.getCode()).c_str();
    Serial.print("UID Card :");
    Serial.println(UIDCard);
    storeData();
  }
}

void setup()
{
  Serial.begin(9600);
  wg.begin(W0, W1);

  pinMode(beeper, OUTPUT);
  pinMode(relay, OUTPUT);
  digitalWrite(relay, HIGH);

  WiFiSetup();

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
}
