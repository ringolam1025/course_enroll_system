<?
	$now = date("Y/m/d H:i:s");
	

	function dateformat($date, $format){
		return date_format(new DateTime($date), $format);		
	}

	function genAutoID($tbl, $col, $prefix){
		global $db;
		
		$res = "";
		$counter = 0;

		$pipeline = [
						['$project' => ['MAX({$col})' => '$MAX({$col})',$col => '$'.$col]],
					 	['$sort' => [$col => -1]],
					 	['$limit' => 1]					   
					];	        

		$collection = $db->$tbl;
		//$record = $collection->find();
		$record = $collection->aggregate($pipeline);
		$_outputArr = iterator_to_array($record);
		$res = preg_replace("/[a-zA-Z]/", "", $_outputArr[0][$col]);

		for ($i=0;$i<strlen($res);$i++) {			
			if (substr($res,0,1) == '0'){
				$res = substr($res,1,strlen($res));
			}
		}

		$res = (($res*1)+1);
		$res = $prefix.addZero($res, 3);		
		return $res;
	}

	function addZero($obj, $len){
		$counter = 0;

		if (strlen($obj) < $len){
			$counter = $len - strlen($obj);
			for ($i=0;$i<$counter;$i++){
				$obj = "0".$obj;
			}
		}
		return $obj;
	}

	function printSQLToTextFile($filename, $sql){
		global $now, $_DEBUG;

		if ($_DEBUG){
			$file = "sql_file.sql";
			$new = "--------{$filename}---{$now}-----------\n";
			$new .= $sql;
			$new .= "\n-------------------------------------------------------------\n";
			
			$current = file_get_contents($file);
			$current = $new."\n".$current;
			file_put_contents($file, $current);
		}
	}

	function getDBHeaderName($tbl, $col){
		global $conn;
		$_DB_name = "";
		$sql = "SELECT * FROM import_setting WHERE table_name = '{$tbl}' AND name_in_excel = '{$col}'";
		$result = mysqli_query($conn, $sql);
		while ($row = mysqli_fetch_assoc($result)) {
			$_DB_name = $row['name_in_db'];
		}
		return $_DB_name;
	}

	function addImportHistory($sourcePath, $SheetNum){
		global $conn, $now;

		$sql .= '"'.$record_sql.'"';

		//$sql = "INSERT INTO import_history (fileName, record_sql, worksheet_progress, import_date ) VALUES ('{$sourcePath}', .\"".$record_sql."\", '{$SheetNum}', '{$now}')";
		$sql = "INSERT INTO import_history (fileName, worksheet_progress, import_date ) VALUES ('{$sourcePath}','{$SheetNum}', '{$now}')";
		mysqli_query($conn, $sql);	
		printSQLToTextFile('global.php:addImportHistory()', $sql);
	}

	function addChangeHistory($change_pkey, $tableName, $colName, $oldValue, $newValue){
		global $conn, $now;
		$sql = "INSERT INTO change_history (change_pkey, tableName, colName, org_val, new_val, change_date ) VALUES ('{$change_pkey}', '{$tableName}', '{$colName}', '{$oldValue}', '{$newValue}', '{$now}')";
		mysqli_query($conn, $sql);
		printSQLToTextFile('global.php:addChangeHistory()', $sql);
	}
?>
