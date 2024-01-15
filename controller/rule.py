#!/usr/bin/python

from datetime import datetime
from hardware import hardware

def getRules(data, hardware : hardware):
    rules = {}
    for cond in data:
        if cond[1] in rules.keys():
            rules[cond[1]].append(cond)
        else:
            rules[cond[1]] = [cond]
    
    objRules = []
    for ruleID, ruleData in rules.items():
        objRules.append(rule(ruleData, hardware))

    return objRules

class rule:
    def __init__(self, data, hardware : hardware):
        self.hw = hardware
        self.ruleID = data[0][1]
        self.conditions = []
        for condData in data:
            self.conditions.append(condition(condData))

    def eval(self, sensorData = None):
        result = True
        if (sensorData is None):
            sensorData = self.hw.readAll()
        for cond in self.conditions:
            if not cond.eval(sensorData):
                result = False
        return result

    def __repr__(self):
        return str(self.conditions)

class condition:
    def __init__(self, data):
        expr = data[3].strip().split(" ")
        
        self.condID = data[0]
        self.ruleID = data[1]
        self.value = data[2]
        self.op = expr[0]
        self.val1 = None
        self.val2 = None
        
        try:
            if (self.value == "time"):
                if (len(expr) > 1):
                    self.val1 = datetime.strptime(expr[1], "%H:%M").time()
                if (len(expr) > 2):
                    self.val2 = datetime.strptime(expr[2], "%H:%M").time()
            else:
                if (len(expr) > 1):
                    self.val1 = int(expr[1])
                if (len(expr) > 2):
                    self.val2 = int(expr[2])
        except Exception:
            pass

    def eval(self, sensorData):
        if not ((self.value in sensorData) or (self.value == "time")):
            return False
        val = None

        if (self.value == "time"):
            val = datetime.now().time()
        else:
            val = sensorData[self.value]
            
        if (self.op == "<"):
            return val < self.val1
        elif (self.op == "<="):
            return val <= self.val1
        elif (self.op == "=="):
            return val == self.val1
        elif (self.op == "!="):
            return not (val == self.val1)
        elif (self.op == ">="):
            return val >= self.val1
        elif (self.op == ">"):
            return val > self.val1
        elif (self.op == "BETWEEN"):
            return (val > self.val1) and (val < self.val2)

    def __repr__(self):
        return str({"condID": self.condID, "ruleID": self.ruleID, "value": self.value, "op": self.op, "val1": self.val1, "val2": self.val2})