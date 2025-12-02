<?php
require "class/zoho_methods.class.php";
$objZohoOauth=new ZOHO_METHODS();
$grantToken="1000.c9ec2a6bb96f38faa9c9f811a7ded7e0.7d3a7b97d3ef1d86ae141ad32b1b590d";
$res=$objZohoOauth->generateAccessTokenFromGrantToken($grantToken);
if($res)
{
	echo "Access code generated successfully.";
}
else
{
	echo "Error in generating access code.";
}
?>