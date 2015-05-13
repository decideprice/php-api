# PHP API Overview

The following denotes the PHP API for [Decideprice](https://developers.decideprice.com). Its Provide to Develop Custom Template Developed Using the Decideprice Platform Simple. Any HTML/HTML5 E-commerce Template Can Be Converted Into A Live Selling Platform Using Decideprice.


## Description ##



## Requirements ##
* [PHP 5.2.1 or higher](http://www.php.net/)
* [PHP JSON extension](http://php.net/manual/en/book.json.php)



## Developer Documentation ##
http://developers.google.com/api-client-library/php

## Installation ##

For the latest installation and setup instructions, see [the documentation](https://developers.google.com/api-client-library/php/start/installation).

## Basic Example ##
See the examples/ directory for examples of the key client features.
```PHP
<?php

  require_once 'google-api-php-client/src/Google/autoload.php'; // or wherever autoload.php is located
  
  $client = new Google_Client();
  $client->setApplicationName("Client_Library_Examples");
  $client->setDeveloperKey("YOUR_APP_KEY");
  
  $service = new Google_Service_Books($client);
  $optParams = array('filter' => 'free-ebooks');
  $results = $service->volumes->listVolumes('Henry David Thoreau', $optParams);

  foreach ($results as $item) {
    echo $item['volumeInfo']['title'], "<br /> \n";
  }
  
```


