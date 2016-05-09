#!/bin/bash
if ps aux | grep 'matchalert_mail.php 3 0' | grep -v grep >>/dev/null
then
        echo '' >>/dev/null
else
        /usr/local/php/bin/php -q /var/www/html/web/jsmail/matchalert_mail.php 3 0 >> /home/developer/logerror.txt &
fi

if ps aux | grep 'matchalert_mail.php 3 1' | grep -v grep >>/dev/null
then
        echo '' >>/dev/null
else
        /usr/local/php/bin/php -q /var/www/html/web/jsmail/matchalert_mail.php 3 1 >> /home/developer/logerror.txt &
fi

if ps aux | grep 'matchalert_mail.php 3 2' | grep -v grep >>/dev/null
then
        echo '' >>/dev/null
else
        /usr/local/php/bin/php -q /var/www/html/web/jsmail/matchalert_mail.php 3 2 >> /home/developer/logerror.txt &
fi
