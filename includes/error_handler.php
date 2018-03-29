<?php
//Error Handler

set_error_handler('resAppErrorHandler', E_ALL);

function resAppErrorHandler($number, $message, $file, $line) {
	if (ob_get_length()) ob_clean();

	$err = 	"Line: " . $line . chr(10) . 
			"Number: " . $number . chr(10) . 
			"Message: " . $message . chr(10) . 
			"File: " . $file . chr(10);

	echo $err;
	exit;
}
?>