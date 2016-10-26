InterviewApp PHP bindings [![Build Status](https://travis-ci.org/itwapp/itwapp-php.svg)](https://travis-ci.org/itwapp/itwapp-php) [![Coverage Status](https://coveralls.io/repos/itwapp/itwapp-php/badge.png?branch=master)](https://coveralls.io/r/itwapp/itwapp-php?branch=master)
====

You can sign up for an InterviewApp account at http://itwapp.io.

Requirements
----

PHP 5.6 and later.

Composer
----

You can install the bindings via [Composer](http://getcomposer.org/). Add this to your `composer.json`:

    {
      "require": {
        "itwapp/itwapp-php" : "1.1.0"
      }
    }

Then install via:

    composer.phar install

To use the bindings, either user Composer's [autoload](https://getcomposer.org/doc/00-intro.md#autoloading):

    require_once('vendor/autoload.php');

Or manually:

    require_once('/path/to/vendor/itwapp/itwapp-php/lib/Itwapp.php');

Manual Installation
----

Obtain the latest version of the Itwapp PHP bindings with:

    git clone https://github.com/itwapp/itwapp-php

To use the bindings, add the following to your PHP script:

    require_once("/path/to/itwapp-php/lib/Itwapp.php");

Getting Started
----

Simple usage looks like:

    Itwapp::setApiKey("627c8047c69c25b7e1db3064b61917e0");
    Itwapp::setApiSecretKey("a6e38238874cb44f4efd6bc462853cd8fd39da62");
    
    $interviews = Interview::findAll(array());

Documentation
----

Please see http://api.itwapp.io/ for up-to-date documentation.

