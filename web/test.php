<?php

require_once(dirname(__FILE__) . '/../lib/GitRepository.class.php');

$a = new GitRepository('git://github.com/guille/textboxlist.git', dirname(__FILE__) . '/../git/', '/usr/bin/git');
$a->update();

