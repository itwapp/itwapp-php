<?php

// Itwapp singleton
require(dirname(__FILE__) . '/Itwapp/Itwapp.php');

// Errors
require(dirname(__FILE__) . '/Itwapp/exception/InvalidRequestError.php');
require(dirname(__FILE__) . '/Itwapp/exception/ResourceNotFoundException.php');
require(dirname(__FILE__) . '/Itwapp/exception/ServiceException.php');
require(dirname(__FILE__) . '/Itwapp/exception/UnauthorizedException.php');

// Utilities
require(dirname(__FILE__) . '/Itwapp/rest/ApiRequest.php');
require(dirname(__FILE__) . '/Itwapp/rest/Sign.php');

// Itwapp API Resources
require(dirname(__FILE__) . '/Itwapp/object/Applicant.php');
require(dirname(__FILE__) . '/Itwapp/object/Interview.php');
require(dirname(__FILE__) . '/Itwapp/object/Question.php');
require(dirname(__FILE__) . '/Itwapp/object/Response.php');

