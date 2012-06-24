#!/bin/sh
#
# Loads some base data (from the <project>/data/fixtures directory)
# into the database.
# Make sure you have created both the database tables and database
# classes at this point.


DIR="`dirname $0`/.."
SYMFONY="php -d memory_limit=512M $DIR/symfony"

$SYMFONY propel:data-load
