#!/usr/bin/python

import logging
from pushover import init, Client
from database import database

pover_client = None
notify_logger = None

def init(db : database):
    global pover_client
    global notify_logger

    notify_logger = logging.getLogger('Notify')
    config = db.readConfig()

    if (config["poverEnable"]):
        pover_client = Client(config["poverUserKey"], api_token=config["poverToken"])

def push_message(title, text):
    if (pover_client is not None):
        if (notify_logger is not None):
            notify_logger.info(f"Sending push message to Pushover \"{title}\": \"{text}\"")
        pover_client.send_message(text, title=title)