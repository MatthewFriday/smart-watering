#!/usr/bin/python

import logging
import mysql.connector
from rule import rule

class database:
    def __init__(self, config):
        self.log = logging.getLogger('Database')
        self.log.info("Pre-initializing database object...")
        self.conn = None
        self.config = config
        self.wasConnected = False

    def __del__(self):
        self.log.info("Closing database connection...")
        if (self.conn is not None):
            self.conn.close()

    def __connect(self):
        self.log.info(f"Connecting to MySQL server: {self.config['user']}@{self.config['host']} database={self.config['database']}")
        self.conn = mysql.connector.connect(
            user = self.config['user'],
            password = self.config['pass'],
            host = self.config['host'],
            database = self.config['database']
        )
        self.wasConnected = True

    def __checkConn(self):
        if (self.conn is None) or (not self.conn.is_connected()):
            if (self.wasConnected):
                self.log.error("MySQL connection lost! Reconnecting...")
            self.__connect()

    def insertMeasurements(self, values):
        try:
            self.log.debug(f"insertMeasurements: values={values}")
            self.__checkConn()
            query = f"INSERT INTO measurements ({', '.join(values.keys())}) VALUES ({', '.join(['%s']*len(values.keys()))})"

            with self.conn.cursor() as cursor:
                cursor.execute(query, list(values.values()))
            self.conn.commit()
        except Exception as e:
            self.log.error(f"Failed to insertMeasurements: {e}")

    def readRules(self):
        try:
            self.log.debug(f"readRules")
            self.__checkConn()
            query = f"SELECT * FROM conditions"

            with self.conn.cursor() as cursor:
                cursor.execute(query)
                return cursor.fetchall()
        except Exception as e:
            self.log.error(f"Failed to readRules: {e}")

    def readConfig(self):
        try:
            self.log.debug(f"readConfig")
            self.__checkConn()
            query = f"SELECT * FROM config"

            with self.conn.cursor() as cursor:
                cursor.execute(query)
                results = cursor.fetchall()
                config = {}
                for conf in results:
                    if conf[0] == "poverEnable":
                        config[conf[0]] = True if conf[1] == "true" else False
                    else:
                        config[conf[0]] = conf[1]
                return config
        except Exception as e:
            self.log.error(f"Failed to readConfig: {e}")
