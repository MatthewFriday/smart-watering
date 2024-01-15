#!/usr/bin/python

import json
from hardware import hardware

def readJson(path):
    with open(path, "r") as fin:
        return json.load(fin)

def main():
    # Init
    config = readJson("config.json")
    hw = hardware(config["hardware"])
    hw.setRelay(False)

if __name__ == "__main__":
    main()
