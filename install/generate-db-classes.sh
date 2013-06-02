#!/bin/sh
#
# Generates the required Propel Base-classes, which are extended by the
# different model- filters- and form-classes in the <project>/lib
# directory.


DIR="`dirname $0`/.."
SYMFONY="php -d memory_limit=512M $DIR/symfony"

$SYMFONY propel:build --all-classes
$SYMFONY cache:clear
