<?php

$urlprefix = "http://reports.police.gatech.edu/public/crimelog.asp";

//sanitize user input
if ($_GET['offset'] == strval(intval($_GET['offset'])))
	{
	    $offsetsuffix = $_GET['offset'];
	}

if ($_GET['OCA'] == strval(intval($_GET['OCA'])))
	{
	    $OCAsuffix = $_GET['OCA'];
	}

// OCA can not be negative, offset = -1 is used to go to last page, any other negative numbers are not used.
if ($offsetsuffix < -1)
	{
		$offsetsuffix = 0;
	}

if ($OCAsuffix < 0)
	{
		$OCAsuffix = 0;
	}

//Create url values to be passed to reports.police.gatech.edu if requested, skipped if values were not passed.

if (isset($offsetsuffix))
	{
		$offsetsuffix = "offset=".$offsetsuffix;
	}


if (isset($OCAsuffix))
	{
		$OCAsuffix = "OCA=".$OCAsuffix;
	}

//create final url. This adds "?" and "&" if needed
$url = $urlprefix;

if ((isset($OCAsuffix)) || (isset($offsetsuffix)))
	{
		$url = $urlprefix."?".$OCAsuffix.$offsetsuffix;
	}

if ((isset($OCAsuffix)) && (isset($offsetsuffix)))
	{
		$url = $urlprefix."?".$OCAsuffix."&".$offsetsuffix;
	}

readfile($url);
?>

