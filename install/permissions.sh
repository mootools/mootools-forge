#!/bin/sh
#
# Changes permissions of directories to which the webserver process
# has to be able to write.

DIR="`dirname $0`/.."
CHMOD="chmod go+wX"

$CHMOD "$DIR/cache"
$CHMOD "$DIR/git"
$CHMOD "$DIR/log"
$CHMOD "$DIR/web/uploads" "$DIR/web/uploads/"*

# In case there's cache already.
$CHMOD "$DIR/cache/"* 2>/dev/null
