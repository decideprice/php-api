# PHP API Overview

The following denotes the PHP API for [Decideprice](https://developers.decideprice.com). Its Provide to Develop Custom Template Developed Using the Decideprice Platform Simple. Any HTML/HTML5 E-commerce Template Can Be Converted Into A Live Selling Platform Using Decideprice.


## Description ##



## Requirements ##
* [PHP 5.2.1 or higher](http://www.php.net/)


## Developer Documentation ##
http://developers.decideprice.com/

## Installation ##

	Copy The Folder decideprice-api-php-theme into the root Directory then please see the how the Authenctication section below.

## Authentication And Basic Sample ##
See the examples/sample1 directory for examples of the key client features.
```PHP
<?php
	
	if (!defined('DP_API_KEY')) {define('DP_API_KEY', 'your-unique-api-key');}

	if (!class_exists('decideprice_theme')){require_once('decideprice/decideprice_theme.php');}

  	$decideprice_theme = new decideprice_theme();

  	$logo = $decideprice_theme->get('logo/');
  
```

Change your-unique-api-key with your Api Key that gets from your developer setting in the shop merchant panel



