<?
	require 'vendor/autoload.php';
	 
	

	try{
	  	// Connect to mongo database
		$con = new MongoDB\Client("mongodb://127.0.0.1:27017");
		//print_r($con);	
		$db = $con->selectDatabase('ads_assignment');

	}catch (MongoConnectionException $e){
		die('Error connecting to MongoDB server<br>');

	}catch (MongoException $e){
		die('Error: ' . $e->getMessage());
	}