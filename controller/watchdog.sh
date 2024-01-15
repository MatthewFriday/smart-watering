#!/bin/bash

while true; do
    if pgrep -f "app.py" > /dev/null; then
        echo "Controller is running"
    else
        echo "Controller is not running. Calling setRelay()"

        python3 fail.py
    fi
    sleep 5
done