#!/bin/sh
#
# Loads some base data (from the <project>/data/fixtures directory)
# into the database.
# Make sure you have created both the database tables and database
# classes at this point.


DIR="`dirname $0`/.."
SYMFONY="$DIR/symfony"

$SYMFONY propel:data-load
