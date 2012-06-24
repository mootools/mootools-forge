#!/bin/sh
#
# Creates SQL queries in the <project>/data/sql directory, then inserts
# them into the database.
# Enter the right information in `<project>/config/databases.yml`, and
# make sure you have created the database before running this script.
#
# The last part of the script will warn you that it will remove all data
# in this database. This shouldn't be a problem, since you (probably)
# have no data yet. If you do have data, you'll have to apply the
# changes manually. After running the command (and answer 'No' to
# removing your data) you can find the generated queries in
# <project>/data/sql/b.model.schema.sql.

DIR="`dirname $0`/.."
SYMFONY="php -d memory_limit=512M $DIR/symfony"
SQLFILE="$DIR/data/sql/lib.model.schema.sql"

$SYMFONY propel:build-sql
sed -i'.orig'  's/^)Type/) ENGINE/' "$SQLFILE"

$SYMFONY propel:insert-sql
