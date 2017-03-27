#!/bin/bash
cd /home/developer/projects/JsListing

if ps aux | grep 'jslistings-0.1.0.jar' | grep -v grep >>/dev/null
then
        echo '' >>/dev/null
else
        nohup java -jar jslistings-0.1.0.jar > /dev/null &
fi