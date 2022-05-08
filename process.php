<?
	header('Content-Type: application/json');
	require_once("include/_conn.php");
	require_once("include/global.php");
	require_once("_coresetting.php");
	require_once("_corefunction.php");
	
	$res = array();
	$res['flag'] = 'fail';
	$res['msg'] = 'Please try again!';	
	$res['res'] = '';
	$_outputArr = array();

	$data = $_POST;
	if (empty($data)) $data = $_GET;
	$tbl = "";
	if (isset($data['tableName']) && $data['tableName'].'' != '') $tbl = $data['tableName'];

	// if ($data['part'].'' != 'get_data'){
	// 	print_r("<pre>");
	// 	print_r($data);
	// 	print_r("</pre>");
	// 	exit;
	// }

	if ($data['part'].'' == 'get_session'){
		$res['flag'] = 'success';
		$res['msg'] = "";
		$res['data'] = $_SESSION;

	}else if ($data['part'].'' == 'get_data_bak'){
		$collection = $db->$tbl;
		$record = $collection->find();
		$_outputArr = iterator_to_array($record);

		$res['flag'] = 'success';
		$res['msg'] = "";
		$res['data'] = $_outputArr;

	}else if ($data['part'].'' == 'get_data'){
		//print_r($data['pageArr']);
		$_tableListArr = $data['pageArr']['tableList'];
		if (isset($data['pageArr']['orderBy'])) $_sort = $data['pageArr']['orderBy'];
		$_searchArr = array();
		$lookup = array();
		$unwind = array();
		$project = array();
		$match = array();

		genCoreQuery($_tableListArr, $lookup, $unwind, $project);
		setSearchQuery($_searchArr, $match, $lookup, $unwind, $project);

		if (isset($data['pageArr']['orderBy'])) $_searchArr[] = array('$sort'=>array($_sort=>-1));		
		//print_r(json_encode($_searchArr)."\n");
		$collection = $db->$tbl;
		$record = $collection->aggregate($_searchArr);
		$_outputArr = iterator_to_array($record);
		// printSQLToTextFile('process.php:get_data ('.$tbl.')', json_encode($_searchArr));

		//print_r($_outputArr);
		$res['flag'] = 'success';
		$res['msg'] = "";
		$res['data'] = $_outputArr;

	}else if ($data['part'].'' == 'get_detail_data'){
		$colName = $data['colName'];
        $get_pkey = $data['get_pkey'];

		$_searchArr = array($colName=>$get_pkey);
        $collection = $db->$tbl;		
		$record = $collection->find($_searchArr);
		$_outputArr = iterator_to_array($record);
		printSQLToTextFile('process.php:get_detail_data ('.$tbl.')', json_encode($_searchArr));

		$res['flag'] = 'success';
		$res['msg'] = "";
		$res['data'] = $_outputArr;

	}else if ($data['part'].'' == 'add'){
		$_addDBArr = array();
		$_headerStr = "";
		$_valueStr = "";
		$_valList = $data['formData'];
		$_tmpTable = $data['tableName'];

		for ($i=0;$i<sizeof($_valList);$i++) {
			$_tmpArr = array();

			if($_valList[$i]['name'].'' == 'isvalid'){
				if ($_valList[$i]['name'].'' == 'on'){
					$_addDBArr[$_valList[$i]['name']] = 1;
				}
			}else if($_valList[$i]['name'].'' == 'classSize'){
				$_addDBArr[$_valList[$i]['name']] = (Int)($_valList[$i]['value']);
			}else{
				$_addDBArr[$_valList[$i]['name']] = $_valList[$i]['value'];
			}			
		}

		$id = genAutoID($data['tableName'], $data['colName'], $data['prefix']);
		$_addDBArr[$data['colName']] = $id;

		$collection = $db->$tbl;
		$insertOneResult = $collection->insertOne($_addDBArr);
		printSQLToTextFile('process.php:add ('.$tbl.')', json_encode($_addDBArr));

		$res['flag'] = 'success';
		$res['msg'] = "";

	}else if ($data['part'].'' == 'delete'){
		$_needDel = true;
		$_outputArr = array();
		$_valueList = explode(",", $data['formData'][0]['value']);
				
		if ($tbl.'' == 'tbl_dept' || $tbl.'' == 'tbl_course'){
			$collection = $db->tbl_offer;
			$_searchArr[] = array('$match'=>array($data['pkeyVal']=>array('$in'=>$_valueList)));
			$_searchArr[] = array('$group'=>array('_id'=>null,'count'=>array('$sum'=>1)));
			$_searchArr[] = array('$project'=>array('offerID'=>1,'count'=>array('$toInt'=>'$count')));
			$record = $collection->aggregate($_searchArr);
			$_outputArr = iterator_to_array($record);

		}

		if (sizeof($_outputArr) != 0) $_needDel = false;

		if ($_needDel){
			$collection = $db->$tbl;
			$_delDBArr = array($data['pkeyVal']=>array('$in' => $_valueList));
			$result = $collection->deleteMany($_delDBArr);
			printSQLToTextFile('process.php:delete ('.$tbl.')', json_encode($_delDBArr));
			$res['flag'] = 'success';
			$res['msg'] = "";

		}else{
			$res['flag'] = 'fail';
			$res['msg'] = "Not allow to delete!";
		}

	}else if ($data['part'].'' == 'detailPage_delete'){
		$_valueList = explode(",", $data['formData'][0]['value']);

		for ($i=0;$i<sizeof($_valueList);$i++) { 
			if($data['pageArr']['page_id'].'' == 'enrolled_student'){
				$whereKey = $_valueList[$i];
				$get_pkey = $data['get_pkey'];

			}else{ // student_enroll
				$whereKey = $data['get_pkey'];
				$get_pkey = $_valueList[$i];
			}		
			$updateCond = array("studentID"=>$whereKey);
			$collection = $db->tbl_student;
			$collection->updateOne($updateCond, array('$pull'=>array("enrolled"=>array("offerID"=>$get_pkey))));
			printSQLToTextFile('process.php:detailPage_delete ('.$tbl.')', json_encode($updateCond));
		}

		$res['flag'] = 'success';
		$res['msg'] = "";

	}else if ($data['part'].'' == 'update'){
		$updateValue = "";

		if ($data['colName'].'' == 'dob' || $data['colName'].'' == 'enrollDate'){ 
			$updateValue = new \MongoDB\BSON\UTCDateTime(strtotime($data['row'][$data['colName']]) * 1000);
		}else{
			$updateValue = $data['row'][$data['colName']];
		}
		$updateCond = array($data['pkeyVal']=>$data['row'][$data['pkeyVal']]);
		$collection = $db->$tbl;
		$collection->updateOne($updateCond,
							   array('$set' => array($data['colName'] => $updateValue)));
		printSQLToTextFile('process.php:update ('.$tbl.')', json_encode($updateCond));
		$res['flag'] = 'success';
		$res['msg'] = "";

	}else if ($data['part'].'' == 'search'){
		$_searchArr = array();
		$_outputArr = array();
		$lookup = array();
		$unwind = array();
		$project = array();
		$match = array();		

		$_tableListArr = $data['pageArr']['tableList'];	

		genCoreQuery($_tableListArr, $lookup, $unwind, $project);
		genMatchQuery($data['formData'], $match);
		setSearchQuery($_searchArr, $match, $lookup, $unwind, $project);

		//print_r(json_encode($_searchArr)."\n");

    	$collection = $db->$tbl;
    	$record = $collection->aggregate($_searchArr);
    	$_outputArr = iterator_to_array($record);
    	printSQLToTextFile('process.php:search ('.$tbl.')', json_encode($_searchArr));

		$res['flag'] = 'success';
		$res['msg'] = "";
		$res['data'] = $_outputArr;

	}else if ($data['part'].'' == 'searchByCond'){
		$_searchArr = array();
		$_outputArr = array();
		$tmpArr = array();
		$col = $data['col'];
		$val = $data['val'];
		$nestArr = $data['nestArr'];

		$matchArr = array($nestArr.".".$col => array('$eq'=>$val));
		$projectArr = array('enrolled'=>('$'.$nestArr.'.'.$col));

		$_searchArr = array(
							array('$match' => $matchArr),
							array('$project' => $projectArr),
							array('$count'=>'total')
					);
		//print_r($_searchArr);

    	$collection = $db->$tbl;
    	$record = $collection->aggregate($_searchArr);
    	$_outputArr = iterator_to_array($record);
    	printSQLToTextFile('process.php:searchByCond ('.$tbl.')', json_encode($_searchArr));

		$res['flag'] = 'success';
		$res['msg'] = "";
		$res['data'] = $_outputArr;

		// print_r($_outputArr);
		// exit;

	}else if ($data['part'].'' == 'getRegisterCount'){
		$_searchArr = array();
		$_outputArr = array();
		$tmpArr = array();
		$nestArr = $data['nestArr'];
		$col = $data['col'];
		$projectArr = array('enrolled'=>($nestArr.'.'.$col));

		$condition = array('offerID' => '$enrolled.offerID', 'year' => '$enrolled.year');		
		$_searchArr[] = array('$unwind' => ($nestArr));
		$_searchArr[] = array('$group' => array('_id' => $condition, 'count' => array('$sum'=>1)));
				
		//print_r($_searchArr);

    	$collection = $db->$tbl;
    	$record = $collection->aggregate($_searchArr);
    	$_outputArr = iterator_to_array($record);
    	printSQLToTextFile('process.php:getRegisterCount ('.$tbl.')', json_encode($_searchArr));

		$res['flag'] = 'success';
		$res['msg'] = "";
		$res['data'] = $_outputArr;

	}else if ($data['part'].'' == 'custSearch'){
		$_searchArr = array();
		$_outputArr = array();

		//print_r($unwind);
		//print_r($condition);

		$unwind = $data['unwind'];
		$condition = $data['condition'];

		$_searchArr[] = array('$unwind' => $data['unwind']);
		$_searchArr[] = array('$match' => $data['condition']);		

		// print_r($_searchArr);

		$collection = $db->$tbl;
    	$record = $collection->aggregate($_searchArr);
    	$_outputArr = iterator_to_array($record);
    	printSQLToTextFile('process.php:custSearch ('.$tbl.')', json_encode($_searchArr));

		$res['flag'] = 'success';
		$res['msg'] = "";
		$res['data'] = $_outputArr;

	}else if ($data['part'].'' == 'get_student_enroll'){
		$_searchArr = array();

		$colName = $data['colName'];
		$get_pkey = $data['get_pkey'];

		$_searchArr[] = array('$unwind'=>array('path'=>'$enrolled','preserveNullAndEmptyArrays'=> true));
		$_searchArr[] = array('$match'=>array('studentID'=>array('$eq'=>$get_pkey)));
		$_searchArr[] = array('$lookup'=>array('from'=>'tbl_offer',
									            'localField'=>'enrolled.offerID',
									            'foreignField'=>'offerID',
									            'as'=>'offer'
									        ));
		$_searchArr[] = array('$unwind'=>array('path'=>'$offer',
												//'includeArrayIndex'=>'deptName',
												'preserveNullAndEmptyArrays'=> true
											));
		$_searchArr[] = array('$lookup'=>array('from'=>'tbl_dept',
									            'localField'=>'offer.deptID',
									            'foreignField'=>'deptID',
									            'as'=>'dept'
									        ));
		$_searchArr[] = array('$unwind'=>array('path'=>'$dept','preserveNullAndEmptyArrays'=> true));
		$_searchArr[] = array('$lookup'=>array('from'=>'tbl_course',
									            'localField'=>'offer.courseID',
									            'foreignField'=>'courseID',
									            'as'=>'course'
									        ));
		$_searchArr[] = array('$unwind'=>array('path'=>'$course','preserveNullAndEmptyArrays'=> true));
		$_searchArr[] = array('$project'=>array('studentID'=>'$studentID',
										        'studentName'=>'$studentName', 
										        'dob'=>'$dob',       
										        'enrolled.offerID'=>'$enrolled.offerID',
										        'enrolled.deptID'=>'$dept.deptID',
										        'enrolled.deptName'=>'$dept.deptName',
												'enrolled.courseID'=>'$course.courseID',
										        'enrolled.title'=>'$course.title',
										        'enrolled.year'=>'$offer.year',
										        'enrolled.enrollDate'=>'$enrolled.enrollDate'
									        ));
		$_searchArr[] = array('$group'=>array('_id'=>'$_id',
												'studentID'=>array('$first'=>'$studentID'),
												'studentName'=>array('$first'=>'$studentName'),
								            	'dob'=>array('$first'=>'$dob'),
								            	'enrolled'=>array( '$push'=>'$enrolled' )
											));

		// print_r($_searchArr);
		// print_r(json_encode($_searchArr)."\n");

		$collection = $db->$tbl;
     	$record = $collection->aggregate($_searchArr);
     	$_outputArr = iterator_to_array($record);
     	printSQLToTextFile('process.php:get_student_enroll ('.$tbl.')', json_encode($_searchArr));

     	$res['flag'] = 'success';
		$res['msg'] = "";
		$res['data'] = $_outputArr[0];

	}else if ($data['part'].'' == 'enroll_student'){
		$_searchArr = array();
		$formData = array();
		for ($i=0;$i<sizeof($data['formData']);$i++) { 
			$formData[$data['formData'][$i]['name']] = $data['formData'][$i]['value'];
		}

		$availablePlace = calCourseAvailablePlace($formData['offerID']);
		if (($availablePlace['cal']+1) >= $availablePlace['classSize']){
			$offerSearch = array('offerID'=>$formData['offerID']);
			$offerArr = getData('tbl_offer', $offerSearch);

			$updateCond = array("studentID"=>$formData['studentID']);
			$collection = $db->tbl_student;
			$collection->updateOne($updateCond,
									array('$push'=>array("enrolled"=>array(
														 "year"=>$offerArr[0]['year'],
														 "offerID"=>$offerArr[0]['offerID'],
														 "enrollDate"=>new MongoDB\BSON\UTCDateTime((new DateTime($now))->getTimestamp()*1000)))));
			printSQLToTextFile('process.php:enroll_student ('.$tbl.')', json_encode($updateCond));
			$res['flag'] = 'success';
			$res['msg'] = "";
		}else{
			$res['flag'] = 'fail';
			$res['msg'] = "This course is full!";
		}		     	

	}else if ($data['part'].'' == 'getEnrolledStudent'){
		$calResArr = calCourseAvailablePlace($data['offerID']);
		updateAvailablePlace($data['offerID'], $calResArr);

		$_outputArr = getEnrolledStudent($data['offerID']);
		

		$res['flag'] = 'success';
		$res['msg'] = "";
		$res['data'] = $_outputArr;

	}
	echo json_encode($res);

?>
