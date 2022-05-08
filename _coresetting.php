<?
	session_start();
	
	$now = date("Y-m-d H:i:s");

	$_DEBUG = true;
	$_DEBUG = false;

	$_SHOW_SESSION = true;
	$_SHOW_SESSION = false;

	$_SHOW_BTN_DEBUG = true;
	$_SHOW_BTN_DEBUG = false;

	$_SHOW_POST = true;
	//$_SHOW_POST = false;

	$DB_TYPE = 'mongodb'; // mysql 
	$FAVICON_PATH = 'assets/images/favicon.png';
	$PROJECT_NAME = 'course_enroll_system';
	$TITLE = 'Course Enrollment System';
	$FOOTER_TITLE = 'All Rights Reserved by Course Enrollment System';
	$PATH = '/course_enroll_system/';
	$WEBSITE_HOST = $_SERVER['HTTP_HOST'];
	$WEBSITE_URL = $WEBSITE_HOST.$_SERVER['REQUEST_URI'];
	
