#!/bin/bash
TZ=Asia/Calcutta date >>../uploads/SearchLogs/server_status.txt
ps aux | grep 'php' | grep -v grep >>/home/bhavana/other/server_status.txt
