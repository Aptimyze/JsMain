#!/bin/bash
if ps aux | grep 'kundli_mailer_paid.php' | grep -v grep >>/dev/null
then
        echo '' >>/dev/null
else
        /usr/local/php/bin/php -q /var/www/html/web/kundli/kundli_mailer_paid.php >> /var/www/html/web/kundli/kundli_mailer_paid_logerror.txt &
fi

if ps aux | grep 'kundli_mailer_unpaid.php' | grep -v grep >>/dev/null
then
        echo '' >>/dev/null
else
        /usr/local/php/bin/php -q /var/www/html/web/kundli/kundli_mailer_unpaid.php >> /var/www/html/web/kundli/kundli_mailer_unpaid_logerror.txt &
fi
