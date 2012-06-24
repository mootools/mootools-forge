#!/bin/sh
#
# Creates SQL queries in the <project>/data/sql directory, then inserts
# them into the database.
# Make sure the connection information and database name specified in
# <project>/config/databases.yml are correct and set up.

DIR="`dirname $0`/.."
SYMFONY="php -d memory_limit=512M $DIR/symfony"
SQLFILE="$DIR/data/sql/lib.model.schema.sql"

$SYMFONY propel:build-sql
sed -i'.orig'  's/^)Type/) ENGINE/' "$SQLFILE"

$SYMFONY propel:insert-sql
