#!/usr/bin/python

import sys
import json

import rule
import notify
from hardware import hardware
from database import database

def readJson(path):
    with open(path, "r") as fin:
        return json.load(fin)

def main():
    # Init
    config = readJson("config.json")
    hw = hardware(config["hardware"])
    db = database(config["database"])

    if ("relay" in sys.argv):
        while True:
            hw.toggleRelay()
            input("Press [Enter] to toggle ")
    elif ("sensor" in sys.argv):
        while True:
            print(hw.readAll())
            input("Press [Enter] to read ")
    elif ("rules" in sys.argv):
        sensorData = hw.readAll()
        rules = rule.getRules(db.readRules(), hw)
        for r in rules:
            print(f"Rule #{r.ruleID} ({r.eval()}):")
            for cond in r.conditions:
                print(f"   Cond {cond} | Eval: {cond.eval(sensorData)}")
    elif ("notify" in sys.argv):
        notify.init(db)
        notify.push_message("TestTitle", "TestMessage")
    else:
        print(f"Usage: {sys.argv[0]} <relay | sensor | rules | notify>")

if __name__ == "__main__":
    main()
