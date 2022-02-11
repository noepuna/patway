<?php

	require_once "api/account/registered-account-config.php";

	if( !in_array(\App\Auth\AuthPrevilegeMeta::SYSTEM, $iAuth->getPrevileges()) )
	{
		die(header("HTTP/1.0 403 Forbidden Access"));
	}

?>