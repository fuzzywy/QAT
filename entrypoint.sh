#!/bin/bash

set -x

rm -rf /etc/default/locale
env >> /etc/default/locale
/etc/init.d/cron start

service apache2 start

exec "$@"
tail -f /dev/null