<?php

require_once 'lib/common.php';

if(!isLoggedIn()){
    redirectAndExit('login.php');
}

?>