#!/bin/bash
cd /home/developer/htdocs;
if ps aux | grep 'cronDuplication NEW 3 0' | grep -v grep >>/dev/null
then
        echo 'Hiii' >>/dev/null
else
        /usr/local/php/bin/php symfony cron:cronDuplication NEW 3 0 >>/home/developer/cronDup1.txt &
fi
if ps aux | grep 'cronDuplication NEW 3 1' | grep -v grep >>/home/developer/cronDup1.txt
then
        echo 'Hiii' >>/dev/null
else
        /usr/local/php/bin/php symfony cron:cronDuplication NEW 3 1 >>/home/developer/cronDup2.txt &
fi
if ps aux | grep 'cronDuplication NEW 3 2' | grep -v grep >>/home/developer/cronDup2.txt
then
        echo 'Hiii' >>/dev/null
else
        /usr/local/php/bin/php symfony cron:cronDuplication NEW 3 2 >>/home/developer/cronDup3.txt &
fi

if ps aux | grep 'cronDuplication EDIT 3 0' | grep -v grep >>/dev/null
then
        echo 'Hiii' >>/dev/null
else
        /usr/local/php/bin/php symfony cron:cronDuplication EDIT 3 0 >>/home/developer/cronDup4.txt &
fi
if ps aux | grep 'cronDuplication EDIT 3 1' | grep -v grep >>/dev/null
then
        echo 'Hiii' >>/dev/null
else
        /usr/local/php/bin/php symfony cron:cronDuplication EDIT 3 1 >>/home/developer/cronDup5.txt &
fi
if ps aux | grep 'cronDuplication EDIT 3 2' | grep -v grep >>/dev/null
then
        echo 'Hiii' >>/dev/null
else
        /usr/local/php/bin/php symfony cron:cronDuplication EDIT 3 2 >>/home/developer/cronDup6.txt &
fi
