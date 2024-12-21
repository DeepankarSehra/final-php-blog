<?php 
require_once 'lib/common.php';

session_start();
session_destroy();
logout();
redirectAndExit('index.php');