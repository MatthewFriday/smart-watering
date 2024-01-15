#!/usr/bin/python

from datetime import datetime, timedelta
import atexit
import time
import json
import logging
import sched

import rule
import notify
from hardware import hardware
from database import database

def exit_handler(hw, db):
    hw.setRelay(False)
    del hw
    del db

def readJson(path):
    with open(path, "r") as fin:
        return json.load(fin)

def getNextSchedDt(interval):
    dt = datetime.now()
    seconds = (dt.second // interval) * interval + interval
    if seconds >= 60:
        dt += timedelta(minutes=1)
        seconds = 0
    return dt.replace(second=seconds, microsecond=0).timestamp()

def reloadRules(hw, db, log):
    log.debug("Reloading rules from database...")
    return (rule.getRules(db.readRules(), hw), datetime.now())

def evalRules(rules, sensorData):
    relayState = False
    for rule in rules:
        if rule.eval(sensorData):
            relayState = True
            break
    return relayState

lastResult = None
def pollHardware(hw, db, rules, ruleUpdate, sc, log, interval):
    global lastResult

    log.debug("Polling hardware...")
    start = time.time()
    data = None

    try:
        data = hw.readAll()
    except Exception as ex:
        log.error(f"Hardware readAll failed: {str(ex)}")

    hwTime = time.time() - start

    if (rules is not None):
        try:
            result = evalRules(rules, data)
            hw.setRelay(result)
            if (lastResult != result):
                if (result) and (lastResult != None):
                    notify.push_message("Öntözés elindítva", "A SmartWatering automatikus öntözést indított")
                else:
                    notify.push_message("Öntözés befejezve", "A SmartWatering automatikus öntözés véget ért")
                lastResult = result
                
        except Exception as ex:
            log.error(f"Rule evaluation failed: {str(ex)}")

    ruleTime = time.time() - hwTime - start

    if data is not None:
        try:
            db.insertMeasurements(data)
            if (ruleUpdate is None) or (ruleUpdate < datetime.now()-timedelta(minutes=1)):
                rules, ruleUpdate = reloadRules(hw, db, log)
                print(ruleUpdate)
                print(datetime.now()-timedelta(minutes=1))
                print(ruleUpdate < datetime.now()-timedelta(minutes=1))
        except Exception as ex:
            log.error(f"Database insert failed: {str(ex)}")
    
    dbTime = time.time() - ruleTime - hwTime - start if data is not None else None
    log.debug(f"Polling finished! hwTime: {hwTime} | ruleTime: {ruleTime} | dbTime: {dbTime}")
    sc.enterabs(getNextSchedDt(interval), 1, pollHardware, (hw, db, rules, ruleUpdate, sc, log, interval))

def main():
    # Init
    config = readJson("config.json")
    logging.basicConfig(level=logging.DEBUG,
                        format='[%(asctime)s][%(name)s][%(levelname)s] %(message)s')
    log = logging.getLogger("Controller")
    hw = hardware(config["hardware"])
    db = database(config["database"])
    sc = sched.scheduler(time.time, time.sleep)
    notify.init(db)
    atexit.register(exit_handler, hw, db)

    rules = None
    ruleUpdate = None

    sc.enterabs(getNextSchedDt(config["pollingInterval"]),
                1, pollHardware, (hw, db, rules, ruleUpdate, sc, log, config["pollingInterval"]))
    sc.run()

if __name__ == "__main__":
    main()
