#!/usr/bin/python

import logging
from grove.adc import ADC
from grove.grove_moisture_sensor import GroveMoistureSensor
from grove.grove_relay import GroveRelay
import seeed_dht

class hardware:
    def __init__(self, config):
        self.log = logging.getLogger('Hardware')
        self.log.info("Initializing hardware...")

        self.adc = ADC()
        pinMapping = config["pinMapping"]

        self.moisture_pin = pinMapping["MOISTURE"]
        self.light_pin = pinMapping["LIGHT"]
        self.dht11_pin = pinMapping["DHT11"]
        self.relay_pin = pinMapping["RELAY"]

        self.moisture_sensor = GroveMoistureSensor(self.moisture_pin)
        self.dht11_sensor = seeed_dht.DHT("11", self.dht11_pin)
        self.relay = GroveRelay(self.relay_pin)

        self.relay.off()
        self.relay_virtual_state = False 

    def setRelay(self, state):
        self.log.debug(f"setRelay: state={state}")
        if state:
            self.relay.on()
        else:
            self.relay.off()
        self.relay_virtual_state = state

    def toggleRelay(self):
        self.log.debug(f"toggleRelay")
        self.setRelay(not self.relay_virtual_state)

    def readMoisture(self, raw = True):
        self.log.debug(f"readMoisture: raw={raw}")
        moist = self.moisture_sensor.moisture
        if raw:
            return moist
        else:
            if 0 <= moist and moist < 300:
                return -1
            elif 300 <= moist and moist < 600:
                return 0
            else:
                return 1
            
    def readDHT11(self):
        self.log.debug("readDHT11")
        return self.dht11_sensor.read()

    def readLight(self):
        self.log.debug("readLight")
        return self.adc.read(self.light_pin)

    def readAll(self):
        self.log.debug("readAll")
        humi, temp = self.readDHT11()
        moist = self.readMoisture()
        light = self.readLight()
        relayVState = self.relay_virtual_state

        return {
            "moisture": moist,
            "light": light,
            "temperature": temp,
            "humidity": humi,
            "relay": relayVState
        }