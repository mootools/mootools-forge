#!/bin/sh
#
# Creates SQL queries in the <project>/data/sql directory, then inserts
# them into the database.
# Make sure the connection information and database name specified in
# <project>/config/databases.yml are correct and set up.

DIR="`dirname $0`/.."
SYMFONY="$DIR/symfony"

$SYMFONY propel:build-sql
$SYMFONY propel:insert-sql
