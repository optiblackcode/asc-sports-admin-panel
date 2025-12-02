<?php
session_start();
include "include/common.php";
session_unset('user_id');
header("Location:".$objUser->loginPageUrl);
?>