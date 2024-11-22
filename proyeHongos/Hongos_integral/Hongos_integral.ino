#include <Wire.h>
#include <RTClib.h>
#include <SD.h>
#include <SPI.h>
#include <DHT.h>

#define DHTPIN1 2
#define DHTPIN2 3
#define DHTPIN3 4
#define DHTPIN4 5
#define DHTPIN5 6
#define DHTPIN_EXT 9  // Pin del sensor de temperatura y humedad exterior
#define DHTTYPE DHT11

DHT dht1(DHTPIN1, DHTTYPE);
DHT dht2(DHTPIN2, DHTTYPE);
DHT dht3(DHTPIN3, DHTTYPE);
DHT dht4(DHTPIN4, DHTTYPE);
DHT dht5(DHTPIN5, DHTTYPE);
DHT dhtExt(DHTPIN_EXT, DHTTYPE);  // Sensor de temperatura y humedad exterior

RTC_DS3231 rtc;
const int chipSelect = 10;

const int relayFan1 = 8;  // Queda solo un relevador operativo
const int relayPump = 7;  // Pin antes asignado a un relevador, ahora disponible

float temperatures[5];
float humidities[5];
float tempExterior;  // Almacenar temperatura exterior
float humExterior;   // Almacenar humedad exterior
bool headerWritten = false;  // Variable para verificar si el encabezado ha sido escrito

void setup() {
  Serial.begin(9600);
  Wire.begin();

  dht1.begin();
  dht2.begin();
  dht3.begin();
  dht4.begin();
  dht5.begin();
  dhtExt.begin();  // Inicializar sensor de temperatura y humedad exterior

  if (!rtc.begin()) {
    Serial.println("Couldn't find RTC");
    while (1);
  }

  if (!SD.begin(chipSelect)) {
    Serial.println("Initialization failed!");
    return;
  } else {
    Serial.println("Empezamos a grabar perrillo");
  }

  // Escribir el encabezado en el archivo SD
  if (!headerWritten) {
    File dataFile = SD.open("datalog.txt", FILE_WRITE);
    if (dataFile) {
      dataFile.println("Fecha\tHora\tTemp1\tTemp2\tTemp3\tTemp4\tTemp5\tT_ext\tHum_ext\tTempAvg\tHumAvg");
      dataFile.close();
      headerWritten = true;
    } else {
      Serial.println("Error opening datalog.txt");
    }
  }

  pinMode(relayFan1, OUTPUT);
  digitalWrite(relayFan1, HIGH);
}

void loop() {
  DateTime now = rtc.now();

  // Imprimir la fecha y hora en el Serial
  Serial.print(now.year(), DEC);
  Serial.print('/');
  Serial.print(now.month(), DEC);
  Serial.print('/');
  Serial.print(now.day(), DEC);
  Serial.print(" ");
  Serial.print(now.hour(), DEC);
  Serial.print(':');
  Serial.print(now.minute(), DEC);
  Serial.print(':');
  Serial.println(now.second(), DEC);

  temperatures[0] = dht1.readTemperature();
  temperatures[1] = dht2.readTemperature();
  temperatures[2] = dht3.readTemperature();
  temperatures[3] = dht4.readTemperature();
  temperatures[4] = dht5.readTemperature();
  tempExterior = dhtExt.readTemperature();  // Lectura de temperatura exterior

  humidities[0] = dht1.readHumidity();
  humidities[1] = dht2.readHumidity();
  humidities[2] = dht3.readHumidity();
  humidities[3] = dht4.readHumidity();
  humidities[4] = dht5.readHumidity();
  humExterior = dhtExt.readHumidity();  // Lectura de humedad exterior

  float sumTemp = 0;
  float sumHumidity = 0;
  int tempCount = 0;
  int humidityCount = 0;

  for (int i = 0; i < 5; i++) {
    if (!isnan(temperatures[i])) {
      sumTemp += temperatures[i];
      tempCount++;
    }
    if (!isnan(humidities[i])) {
      sumHumidity += humidities[i];
      humidityCount++;
    }
  }

  float averageTemp = (tempCount > 0) ? sumTemp / tempCount : NAN;
  float averageHumidity = (humidityCount > 0) ? sumHumidity / humidityCount : NAN;

  Serial.print("Temperatures: ");
  for (int i = 0; i < 5; i++) {
    Serial.print(temperatures[i]);
    if (i < 4) Serial.print(", ");
  }
  Serial.print(" T_ext: ");
  Serial.print(tempExterior);
  Serial.print(" Average: ");
  Serial.println(averageTemp);
  
  Serial.print("Humidities: ");
  for (int i = 0; i < 5; i++) {
    Serial.print(humidities[i]);
    if (i < 4) Serial.print(", ");
  }
  Serial.print(" Hum_ext: ");
  Serial.print(humExterior);
  Serial.print(" Average: ");
  Serial.println(averageHumidity);

  // Control Fan
  if (averageTemp > 28) {
    digitalWrite(relayFan1, LOW);
  } else if (averageTemp < 26) {
    digitalWrite(relayFan1, HIGH);
  }

  logData(now, temperatures, tempExterior, humExterior, averageTemp, averageHumidity);
  logDataToSerial(now, temperatures, tempExterior, humExterior, averageTemp, averageHumidity);

  delay(300000); // 5 minutes
}

void logData(DateTime now, float temps[], float tempExt, float humExt, float avgTemp, float avgHum) {
  File dataFile = SD.open("datalog.txt", FILE_WRITE);
  if (dataFile) {
    dataFile.print(now.year(), DEC);
    dataFile.print('-');
    dataFile.print(now.month(), DEC);
    dataFile.print('-');
    dataFile.print(now.day(), DEC);
    dataFile.print('\t');
    dataFile.print(now.hour(), DEC);
    dataFile.print(':');
    dataFile.print(now.minute(), DEC);
    dataFile.print(':');
    dataFile.print(now.second(), DEC);

    for (int i = 0; i < 5; i++) {
      dataFile.print('\t');
      dataFile.print(temps[i]);
    }
    dataFile.print('\t');
    dataFile.print(tempExt);  // Registrar temperatura exterior
    dataFile.print('\t');
    dataFile.print(humExt);  // Registrar humedad exterior
    dataFile.print('\t');
    dataFile.print(avgTemp);
    dataFile.print('\t');
    dataFile.println(avgHum);

    dataFile.close();
  } else {
    Serial.println("Error opening datalog.txt");
  }

}

void logDataToSerial(DateTime now, float temps[], float tempExt, float humExt, float avgTemp, float avgHum) {
  Serial.print(now.year(), DEC);
  Serial.print('-');
  Serial.print(now.month(), DEC);
  Serial.print('-');
  Serial.print(now.day(), DEC);
  Serial.print('\t');
  Serial.print(now.hour(), DEC);
  Serial.print(':');
  Serial.print(now.minute(), DEC);
  Serial.print(':');
  Serial.print(now.second(), DEC);

  for (int i = 0; i < 5; i++) {
    Serial.print('\t');
    Serial.print(temps[i]);
  }
  Serial.print('\t');
  Serial.print(tempExt);
  Serial.print('\t');
  Serial.print(humExt);
  Serial.print('\t');
  Serial.print(avgTemp);
  Serial.print('\t');
  Serial.println(avgHum);
}
