<?
  require_once("include/_conn.php");

  function checkSessionLogin($_session_page_id=''){

    if (!isset($_SESSION['login_tbl_user_pkey'])){
      $msg['type'] = "info";
      $msg['title'] = "Login required";
      $msg['msg'] = "Please login first";
      setNotication($msg);
      exit(header("Location: index.php"));

      //exit;
    }
  }

  function run_query($_sql, $coll=''){
    global $db, $DB_TYPE;

    if ($DB_TYPE.'' == 'mysql'){
      $res = $db->query($_sql) or die(mysqli_error($db));
      return $res;

    }else{
      $collection = $db->$coll;
      $record = $collection->find();
      return $record;
    }
    
  }

  function fetch_assoc($result){
    return $result->fetch_assoc();
  }

  function count_row($result){
    return $result->num_rows;
  }

  function updaterow($tbl, $colArr, $where_cond='', $where=''){
    global $db;
    $col = "";
    for ($i=0;$i<sizeof($colArr);$i++){
      $col .= ($col.'' == '')?'':', ';
      $col .= $colArr[$i]." = '".$colArr[($i+1)]."'";
      $i++;
    }
    //$_sql = "UPDATE ".$tbl." SET ".$col." where ".$where_cond." = '".$where."'";
    $_sql = "UPDATE {$tbl} SET {$col} WHERE {$where_cond} = '{$where}'";
    run_query($_sql);
  }

  function newrow($tbl, $colArr){
    global $db;
    $col = "";
    $data = "";

    for ($i=0;$i<sizeof($colArr);$i++){
      $col .= ($col.'' == '')?'':', ';
      $col .= $colArr[$i];
      $i++;
    }
    for ($i=1;$i<sizeof($colArr);$i++){
      $data .= ($data.'' == '')?'':', ';
      $data .= "'".$colArr[$i]."'";
      $i++;
    }

    $_sql = "INSERT INTO {$tbl} ({$col}) VALUES ({$data})";
    //print_html($_sql);
    run_query($_sql);
    return mysqli_insert_id($db);
  }

  function deleterow($tbl, $con){
    global $db;

    $_sql = "DELETE FROM {$tbl} WHERE {$con}";
    //print_html($_sql);
    run_query($_sql);
  }

  function print_html($arr){
    echo "<pre style='margin:0 auto; width:100%; background-color: #EBECE4; padding:10px;word-wrap: break-word;white-space: pre-wrap;'>";
    print_r($arr);
    echo "</pre>";
  }

  function lang_tran(){
    return "";
  }

  function genDropdown($tbl, $option, $display, $cond, $title=""){
     global $db;

     $dropdownArr = array();

     $_sql = "SELECT * FROM ".$tbl;     
     if ($cond.'' != '') $_sql .= " WHERE ".$cond;
     $dropdownArr = array("title"=>$title);

     $_res = run_query($_sql);
     while ($_row = fetch_assoc($_res)){
      $tmp = array("option" => $_row[$option], "display" => $_row[$display]);
      $dropdownArr['options'][] = $tmp;
     }
     //print_html($dropdownArr);
     return $dropdownArr;
  }

  function genCheckBox($tbl, $option, $display, $cond){
     global $db;

     $checkboxArr = array();

     $_sql = "SELECT * FROM ".$tbl;     
     if ($cond.'' != '') $_sql .= " WHERE ".$cond;   
     //print_html($_sql);    

     $_res = run_query($_sql);
     while ($_row = fetch_assoc($_res)){
      $tmp = array("id" => $_row[$option], "option" => $_row[$option], "display" => $_row[$display]);
      $checkboxArr['options'][] = $tmp;
     }
     //print_html($checkboxArr);
     return $checkboxArr;
  }

  // Data Handling
  function debug_checkFunExist($fun){
    global $_DEBUG, $WEBSITE_HOST;

    if ($_DEBUG && ($WEBSITE_HOST.'' == '127.0.0.1' || $WEBSITE_HOST.'' == '192.168.0.120')){
      if (function_exists($fun)) {
        echo "<p style='color:red;padding: 5px 15px 0 15px;'>".$fun."</p>";

      } else {
        echo "<p style='padding: 5px 15px 0 15px;'>".$fun."</p>";
      }
    }
  }

  function debug_showSQL($res_sql){
    global $_DEBUG, $WEBSITE_HOST;

    if ($_DEBUG && ($WEBSITE_HOST.'' == '127.0.0.1' || $WEBSITE_HOST.'' == '192.168.0.120')){
      $res_sql = str_replace("FROM","<br>FROM",$res_sql);
      $res_sql = str_replace("LEFT JOIN","<br>LEFT JOIN",$res_sql);
      $res_sql = str_replace("WHERE","<br>WHERE",$res_sql);
      $res_sql = str_replace("ORDER BY","<br>ORDER BY",$res_sql);
      $res_sql = str_replace("LIMIT","<br>LIMIT",$res_sql);

      echo "<p style='background-color:#EBECE4; padding: 15px 15px;'> <span style='font-size:12px;' class='badge badge-info'>SQL Statement:</span><br><span style='color:red;'>".$res_sql."</span></p>";
    }
  }

  function debug_printFunName($funName){
    global $_DEBUG, $WEBSITE_HOST;
    $is_exist = false;
    
    if (function_exists($funName)) {
      if ($_DEBUG && ($WEBSITE_HOST.'' == '127.0.0.1' || $WEBSITE_HOST.'' == '192.168.0.120')) echo "<div style='color:red;padding: 5px 15px 0 15px;'>".$funName."()</div>";
        $is_exist = true;
    }else{
      if ($_DEBUG && ($WEBSITE_HOST.'' == '127.0.0.1' || $WEBSITE_HOST.'' == '192.168.0.120')) echo "<div style='padding: 5px 15px 0 15px;'>".$funName."()</div>";
        $is_exist = false;
    }
    return $is_exist;
  }

  function getDataBypkey($_pkey, $_table, &$_form_field){
    global $db, $_DEBUG, $WEBSITE_HOST;

    $_select_sql = 'select * from '.$_table." where pkey = '".$_pkey."'";
    if ($_DEBUG && ($WEBSITE_HOST.'' == '127.0.0.1' || $WEBSITE_HOST.'' == '192.168.0.120')) debug_showSQL($_select_sql);
    $_res = run_query($_select_sql);
    $_row = fetch_assoc($_res);

    for ($_i=0;$_i<sizeof($_form_field);$_i++){
      if ($_form_field[$_i]['isvalid']){
        $_form_field[$_i]['value'] = $_row[$_form_field[$_i]['db_field']];
      }
    }
  }

  function genListPageSQLStatementMongoDB($_sqlArr, $items=''){
    global $db, $_DEBUG, $WEBSITE_HOST;

    $_tmp = $_sqlArr['SQL_FROM'][0];
    $_outputArr = array();
    $tmp = array();

    $collection = $db->$_tmp;
    $record = $collection->find();
    foreach ($record as $doc) {
      var_dump($doc);
  }

    if ($record){
      //$_outputArr = $record->jsonSerialize();
      $_outputArr = iterator_to_array($record);      
    }

    return $_outputArr[0];
  }

  function genListPageSQLStatement($_sqlArr, $items='', $_res_per_page=''){
    global $_SHOW_POST;
    $res_sql = '';   

    if ($_SHOW_POST) print_html($_POST);
   
    if (!empty($items)){
      $_searchArr = $items['child']['column'];
      //print_html($_searchArr);
      for ($_seacrh=0; $_seacrh<sizeof($_searchArr);$_seacrh++){
        if (!empty($_POST)){
          if ($_searchArr[$_seacrh]['db_type'].'' == 'dropdown' && isset($_POST[$_searchArr[$_seacrh]['id']])){
            if (is_array($_POST[$_searchArr[$_seacrh]['id']]) && $_POST[$_searchArr[$_seacrh]['id']][0].'' != ''){
              $_sqlArr['SQL_WHERE'][] = "{$_searchArr[$_seacrh]['id']} = '{$_POST[$_searchArr[$_seacrh]['id']][0]}'";

            }else if (!is_array($_POST[$_searchArr[$_seacrh]['id']]) && $_POST[$_searchArr[$_seacrh]['id']].'' != ''){
              $_sqlArr['SQL_WHERE'][] = "{$_searchArr[$_seacrh]['id']} = '{$_POST[$_searchArr[$_seacrh]['id']]}'";  
            }

          }else if ($_searchArr[$_seacrh]['db_type'].'' == 'int'){
            $_isPOST_MIN = $_searchArr[$_seacrh]['id']."_min";
            $_isPOST_MAX = $_searchArr[$_seacrh]['id']."_max";
            
            if ((isset($_isPOST_MIN) && !empty($_POST[$_isPOST_MIN])) || (isset($_isPOST_MAX) && !empty($_POST[$_isPOST_MAX]))){
              $min = $_POST[$_searchArr[$_seacrh]['id']."_min"];
              $max = $_POST[$_searchArr[$_seacrh]['id']."_max"];

              if (isset($_isPOST_MIN) && !empty($_POST[$_isPOST_MIN])){
                $_sqlArr['SQL_WHERE'][] = "{$_searchArr[$_seacrh]['id']} >= {$min}";
              }

              if (isset($_isPOST_MAX) && !empty($_POST[$_isPOST_MAX])){
                $_sqlArr['SQL_WHERE'][] = "{$_searchArr[$_seacrh]['id']} <= {$max}";
              }
            }
          
          }else if (isset($_POST[$_searchArr[$_seacrh]['id']]) && $_POST[$_searchArr[$_seacrh]['id']].'' != ''){
            $_sqlArr['SQL_WHERE'][] = "{$_searchArr[$_seacrh]['id']} LIKE '%{$_POST[$_searchArr[$_seacrh]['id']]}%'";

          }
        }      
      }    
    }
    $_part_select = "";
    $_part_from = "";
    $_part_leftjoin = "";
    $_part_where = "";
    $_part_orderby = "";
    $_part_limit = "";

    for ($_part_sql_counter=0;$_part_sql_counter<sizeof($_sqlArr['SQL_SELECT']);$_part_sql_counter++){
      $_part_select .= (($_part_select.'' != '')?', ':'').$_sqlArr['SQL_SELECT'][$_part_sql_counter];
    }

    for ($_part_sql_counter=0;$_part_sql_counter<sizeof($_sqlArr['SQL_FROM']);$_part_sql_counter++){
      $_part_from .= (($_part_from.'' != '')?', ':'').$_sqlArr['SQL_FROM'][$_part_sql_counter];
    }

    for ($_part_sql_counter=0;$_part_sql_counter<sizeof($_sqlArr['SQL_LEFTJOIN']);$_part_sql_counter++){
      //if(!strpos($_part_leftjoin, $_sqlArr['SQL_LEFTJOIN'][$_part_sql_counter])){
      if (in_array($_sqlArr['SQL_LEFTJOIN'][$_part_sql_counter], $_sqlArr['SQL_LEFTJOIN'])){
        $_part_leftjoin .= (($_part_leftjoin.'' != '')?' LEFT JOIN ':'').$_sqlArr['SQL_LEFTJOIN'][$_part_sql_counter];
      }
    }

    for ($_part_sql_counter=0;$_part_sql_counter<sizeof($_sqlArr['SQL_WHERE']);$_part_sql_counter++){
      //if (!strpos($_part_where, $_sqlArr['SQL_WHERE'][$_part_sql_counter])){
      //if (in_array($_sqlArr['SQL_WHERE'][$_part_sql_counter], $_sqlArr['SQL_WHERE'])){
        $_part_where .= (($_part_where.'' != '')?' AND ':'').$_sqlArr['SQL_WHERE'][$_part_sql_counter];
      //}      
    }

    for ($_part_sql_counter=0;$_part_sql_counter<sizeof($_sqlArr['SQL_ORDERBY']);$_part_sql_counter++){
      $_part_orderby .= (($_part_orderby.'' != '')?', ':'').$_sqlArr['SQL_ORDERBY'][$_part_sql_counter];
    }


    $res_sql = "SELECT ".$_part_select;
    $res_sql .= " FROM ".$_part_from;
    $res_sql .= ($_part_leftjoin.'' == '')?'':' LEFT JOIN '.$_part_leftjoin;    
    $res_sql .= (($_part_where.'' == '')?'':' WHERE ').$_part_where;
    $res_sql .= (($_part_orderby.'' == '')?'':' ORDER BY ').$_part_orderby;
    
    
    if ((isset($_res_per_page) && $_res_per_page.'' != '')){
      if (isset($_GET['page']) && isset($_res_per_page)){
        $tmp = ($_GET['page']-1)*$_res_per_page;
        $res_sql .= " LIMIT {$tmp},{$_res_per_page}";

      }else if (isset($_GET['page']) && isset($_res_per_page)){
        $res_sql .= "LIMIT 0,{$_res_per_page}";

      }
    }

    $res_sql .= ";";

    return $res_sql;
  }

  function redirect($url){
    header('Location: '.$url);
  }

  function setNotication($msg){
    $_SESSION['toastr'][] = $msg;
  }

  function getNotication(){
    if (isset($_SESSION['toastr'])){
      for ($t=0;$t<sizeof($_SESSION['toastr']);$t++){
        $position = "toast-top-right";
        $type = "info";
        $title = "";
        $msg = "";

        if (isset($_SESSION['toastr'][$t]['position']) && $_SESSION['toastr'][$t]['position'].'' != '') $position = $_SESSION['toastr'][$t]['position'];      
        if (isset($_SESSION['toastr'][$t]['type']) && $_SESSION['toastr'][$t]['type'].'' != '') $type = $_SESSION['toastr'][$t]['type'];
        if (isset($_SESSION['toastr'][$t]['title']) && $_SESSION['toastr'][$t]['title'].'' != '') $title = $_SESSION['toastr'][$t]['title'];
        if (isset($_SESSION['toastr'][$t]['msg']) && $_SESSION['toastr'][$t]['msg'].'' != '') $msg = $_SESSION['toastr'][$t]['msg'];

        ?>
          <script type="text/javascript">
            showtoastr('<?= $position ?>','<?= $type ?>','<?= $title ?>', '<?=$msg ?>');
          </script>
        <?        
      }
      unset($_SESSION['toastr']);
    }
  }

  // function dateformat($date, $format){
  //   $date = date_create($date);
  //   return date_format($date, $format);
  // }

  function return_output($file, $form_child){
    if (file_exists($file)){
      ob_start();
      include $file;
      return ob_get_clean();
    }else{
      return 'field_'.$form_child['db_type'].'.php not found!'.'<br>';
    }
  }

  function genNewID($tbl, $prefix, $len){
     global $db;
     $new_id = "";

     $newID_sql = "SELECT pkey as max FROM {$tbl} ORDER BY pkey desc limit 1";
     $res = run_query($newID_sql);
     while ($row = fetch_assoc($res)){
       $new_id = (($row['max']*1)+1);
     }

     $new_id = addZero($new_id, $len);
     
     $new_id = $prefix.$new_id;
     return $new_id;
  }

  // function addZero($str, $tar){
  //   for ($_c=1;$_c<$tar;$_c++) { 
  //     $str = '0'.$str;
  //    }
  //    return $str;
  // }

  function showInListForTable($arr){
    $tmp = array();

    for ($i=0;$i<sizeof($arr);$i++) {
      $item = $arr[$i];
      if ((isset($item['show_in_list']) && $item['show_in_list']) || (isset($item['isvalid']) && $item['isvalid'])){
        $tmp[] = $item;
      }
    }    
    //print_r($item);
    return $tmp;
  }

  function searchAreaSetDefaultVal($db_type){
    if ($db_type.'' != 'checkbox'){
      $_default = "value='".$_item['default']."'"; // Set default, NULL if unset
    }else{
      $_default = "checked";
    }
  }

  function printHTML($_item, $pkey_colName='', $printType){
    $html = '';

    $db_type = $_item['db_type'];
    $field = $_item['field'];
    $title = $_item['title'];
    $inputFieldClass = $_item[$printType];
    $_default = '';
    $key_field = '';
    $required = '';

    $multiple = (isset($_item['multiple'])  && $_item['multiple'] && $printType != 'newModal_class')?'multiple':'';
    $multipleSelect = '';

    $disable = ($printType.'' == 'detail_class')?'disabled':'';

    // Get 
    $select_option = array();
    if (isset($_item['select_option'])){
      $select_option = $_item['select_option'];
    }else{
      if (isset($_item['select_table'])){
        $select_option = getSearchOption($_item['select_table'], $_item['select_table_option'], $_item['select_table_display']);
      }
    }

    if ($printType.'' == 'newModal_class'){
      if (isset($_item['default']) && $_item['default'].''!='') $_default = searchAreaSetDefaultVal($db_type);
      if ($_item['required']) $required = 'required';
      if ($pkey_colName.'' != '' && ($_item['field'].'' == $pkey_colName)){
        $key_field = 'value="AUTO" disabled';
      }
      // $multipleSelect = "basic-multiple";

    }else if ($printType.'' == 'searchArea_class' ){
      $multipleSelect = "basic-multiple";

    }
    
    // Start print HTML
    $html .= "<div class='{$inputFieldClass}'>";
    $html .= "<label>{$title}</label>";

    switch ($db_type) {
      case 'select':
        $html .= "<select class='form-control {$multipleSelect}' id='{$field}' name='{$field}' {$multiple}>";
        for ($_s=0;$_s<sizeof($select_option);$_s++) { 
          $html .= "<option value='{$select_option[$_s]['option']}'>{$select_option[$_s]['display']}</option>";
        }        
        $html .= "</select>";
        break;
      
      case 'date':
        $html .= "<input type='date' class='form-control' id='{$field}' name='{$field}' {$key_field} {$required} {$_default} {$disable}>";
        break;

      default:
        $html .= "<input type='{$db_type}' class='form-control' id='{$field}' name='{$field}' {$key_field} {$required} {$_default} {$disable}>";
        break;
    }
    $html .= '</div>';


    echo $html;
  }

  function printAddTypeHTML($_item, $pkey_colName){
    $html = '';

    $db_type = $_item['db_type'];
    $field = $_item['field'];
    $title = $_item['title'];
    $newModal_class = $_item['newModal_class'];

    // if (isset($_item['default']) && $_item['default'].''!='') $_default = searchAreaSetDefaultVal($db_type);
    // if ($_item['required']) $required = 'required';
    // if ($_item['field'].'' == $pkey_colName) $key_field = 'value="AUTO" disabled';

    // Start print HTML
    $html .= "<div class='{$newModal_class}'>";
    $html .= "<label>{$title}</label>";

    switch ($db_type) {
      case 'select':
        $html .= "<select class='form-control' name='{$field}'>";
        $html .= "<option>1</option>";
        $html .= "</select>";
        break;
      
      default:
        $html .= "<input type='{$db_type}' class='form-control' name='{$field}' {$key_field} {$required} {$_default}>";
        break;
    }
    $html .= '</div>';
    /*
      
    */
    echo $html;
  }
  function printNewModalHTML($_item, $pkey_colName){
    $html = '';
    $db_type = $_item['db_type'];
    $field = $_item['field'];
    $newModal_class = $_item['newModal_class'];
    $_default = '';
    $key_field = '';
    $multiple = (isset($_item['multiple'])  && $_item['multiple'])?'multiple':'';
    
    $select_option = array();
    if (isset($_item['select_option'])){
      $select_option = $_item['select_option'];
    }else{
      if (isset($_item['select_table'])){
        $select_option = getSearchOption($_item['select_table'], $_item['select_table_option'], $_item['select_table_display']);
      }
    }

    // if (isset($_item['default']) && $_item['default'].''!='') $_default = searchAreaSetDefaultVal($db_type);

    // if ($_item['required']) $required = 'required';
    // if ($_item['field'].'' == $pkey_colName) $key_field = 'value="AUTO" disabled';


    // Start print HTML
    $html .= "<div class='{$newModal_class}'>";
    $html .= "<label>{$_item['title']}</label>";

    switch ($db_type) {
      case 'select':
        $html .= "<select class='form-control' name='{$field}'>";
        for ($_s=0;$_s<sizeof($select_option);$_s++) { 
          $html .= "<option value='{$select_option[$_s]['option']}'>{$select_option[$_s]['display']}</option>";
        }        
        $html .= "</select>";
        break;
      
      default:
        $html .= "<input type='{$db_type}' class='form-control' name='{$field}' $key_field $required $_default>";
        break;
    }
    $html .= '</div>';

    echo $html;
  }
  function printSearchTypeHTML($_item){
    $html = '';
    $db_type = $_item['db_type'];
    $field = $_item['field'];
    $title = $_item['title'];
    $searchArea_class = $_item['searchArea_class'];
    $multiple = (isset($_item['multiple'])  && $_item['multiple'])?'multiple':'';

    $select_option = array();
    if (isset($_item['select_option'])){
      $select_option = $_item['select_option'];
    }else{
      if (isset($_item['select_table'])){
        $select_option = getSearchOption($_item['select_table'], $_item['select_table_option'], $_item['select_table_display']);
      }
    }

    // Start print HTML
    $html .= "<div class='{$searchArea_class}'>";
    $html .= "<label>{$title}</label>";

    switch ($db_type) {
      case 'select':
        $html .= "<select class='form-control basic-multiple' name='{$field}' {$multiple}>";
        for ($_s=0;$_s<sizeof($select_option);$_s++) { 
          $html .= "<option value='{$select_option[$_s]['option']}'>{$select_option[$_s]['display']}</option>";
        }
        
        $html .= "</select>";
        break;
      
      default:
        $html .= "<input type='{$db_type}' class='form-control' name='{$field}'>";
        break;
    }
    $html .= '</div>';

    echo $html;
  }

  function getSearchOption($tbl, $option, $display){
    global $db;

    $_outputArr = array();
    $condition = array(); //"isvalid" => 1
    $collection = $db->$tbl;
    $record = $collection->find($condition);
    $res = iterator_to_array($record);

    for ($_o=0;$_o<sizeof($res);$_o++) { 
      $tmp = array();
      $tmp['option'] = $res[$_o][$option];
      $tmp['display'] = $res[$_o][$display];

      $_outputArr[] = $tmp;
    }
    return $_outputArr;
  }

  function getSearchOption2($tbl, $option, $display, $condition){
    global $db;

    $_outputArr = array();
    $collection = $db->$tbl;
    $record = $collection->find();
    $res = iterator_to_array($record);

    for ($_o=0;$_o<sizeof($res);$_o++) { 
      $tmp = array();
      $tmp['option'] = $res[$_o][$option];
      $tmp['display'] = $res[$_o][$display];

      $_outputArr[] = $tmp;
    }
    return $_outputArr;
  }

  function getSearchOption3($tbl, $option, $display, $condition){
    global $db;

    $_outputArr = array();
    $collection = $db->$tbl;
    $record = $collection->find($condition);
    $res = iterator_to_array($record);

    for ($_o=0;$_o<sizeof($res);$_o++) { 
      $tmp = array();
      $tmp['option'] = $res[$_o][$option];
      $tmp['display'] = $res[$_o][$display];

      $_outputArr[] = $tmp;
    }
    return $_outputArr;
  }

  function getData($tbl, $condition){
    global $db;

    $_outputArr = array();

    $collection = $db->$tbl;
    $record = $collection->find($condition);
    $_outputArr = iterator_to_array($record);
    
    return $_outputArr;
  }

  function genMatchQuery($formData, &$match){
    $_andArr = array();
    $_orArr = array();
    //$_output = array();

    for ($i=0;$i<sizeof($formData);$i++) {
      if ($formData[$i]['value'].'' != ''){       
        $tmpArr[$formData[$i]['name']][] = $formData[$i]['value'];
      }     
    }

    foreach ($tmpArr as $key => $value){
      $tmp = array();
      if (sizeof($tmpArr[$key])>1){
        for ($k=0;$k<sizeof($tmpArr[$key]);$k++){
          $tmp[$key] = $tmpArr[$key][$k];
          $_orArr[] = $tmp;
        }       
      }else{
        for ($k=0;$k<sizeof($tmpArr[$key]);$k++){
          //  new MongoRegex("/^$tmpArr[$key][$k]/i"),
          $tmp[$key] = array('$regex'=>$tmpArr[$key][$k]);
          $_andArr[] = $tmp;
        }
      }
    }

    if (sizeof($_orArr)>0) $match['$or'] = $_orArr;
    if (sizeof($_andArr)>0) $match['$and'] = $_andArr;

    //return $_output;
  }

  function genCoreQuery($tableListArr, &$lookup, &$unwind, &$project, $unwindVal='info'){    
    //$_output = array();
    for ($_p=0;$_p<sizeof($tableListArr);$_p++) { 
      $_processField = $tableListArr[$_p];
      $_counter = sizeof($unwind);

      if (isset($_processField['show_in_list']) && $_processField['show_in_list']){
        // Find join field
        if (isset($_processField['select_table'])
          && isset($_processField['select_table_display'])
          && isset($_processField['select_table_option'])
        ){
          // Have foreign table
          $project[$tableListArr[$_p]['field']] = ("\${$unwindVal}".(($_counter>0)?$_counter:'').".".$_processField['select_table_display']);

          // Setup $lookup
          $tmp_lookup = array();
          $tmp_lookup['from'] = $_processField['select_table'];
          $tmp_lookup['localField'] = $_processField['localField'];
          $tmp_lookup['foreignField'] = $_processField['foreignField'];
          $tmp_lookup['as'] = "{$unwindVal}".(($_counter>0)?$_counter:'');
          $lookup[] = $tmp_lookup;

          // Setup $unwind
          $unwind[] = "\${$unwindVal}".(($_counter>0)?$_counter:'');

        }else{
          $project[$tableListArr[$_p]['field']] = 1;
        }
      }
    }
  }

  function setSearchQuery(&$_searchArr, $match, $lookup, $unwind, $project){
    // If have $match
    if (!empty($match)) $_searchArr[] = array('$match' => $match);

    // If have foreign table
    for ($_lookup=0; $_lookup < sizeof($lookup); $_lookup++) { 
      $_searchArr[] = array('$lookup' => $lookup[$_lookup]);
    }   
    // If have foreign table
    for ($_unwind=0; $_unwind < sizeof($unwind); $_unwind++) { 
      $_searchArr[] = array('$unwind' => $unwind[$_unwind]);
    }   
    // If have project, should add to search normally
    if (!empty($project)) $_searchArr[] = array('$project' => $project);
  } 


  function getEnrolledStudent($offerID){
    global $db;

    $_outputArr = array();
    $_searchArr = array();

    $_searchArr[] = array('$match'=>array("offerID"=>array('$eq'=>$offerID)));
    $_searchArr[] = array('$lookup'=>array('from'=>'tbl_student','localField'=>'offerID','foreignField'=>'enrolled.offerID','as'=>'student'));
    $_searchArr[] = array('$lookup'=>array('from'=>'tbl_course','localField'=>'courseID','foreignField'=>'courseID','as'=>'course'));
    $_searchArr[] = array('$unwind'=>array('path'=>'$course','preserveNullAndEmptyArrays'=> true));
    $_searchArr[] = array('$unwind'=>array('path'=>'$student','preserveNullAndEmptyArrays'=> true));
    $_searchArr[] = array('$project'=>array('offerID'=>'$offerID','classSize'=>'$classSize','availablePlace'=>'$availablePlace','deptID'=>'$deptID','dob'=>'$student.dob','studentID'=>'$student.studentID','studentName'=>'$student.studentName','courseID'=>'$courseID','year'=>'$year','title'=>'$course.title', 'student'=>'$student'));
    $_searchArr[] = array('$group'=>array(
                                    '_id'=>'$_id',
                                    'offerID'=>array('$first'=>'$offerID'),
                                    'classSize'=>array('$first'=>'$classSize'),                                    
                                    'availablePlace'=>array('$first'=>'$availablePlace'),
                                    'deptID'=>array('$first'=>'$deptID'),
                                    'courseID'=>array('$first'=>'$courseID'),
                                    'year'=>array('$first'=>'$year'),
                                    'title'=>array('$first'=>'$title'),
                                    'detailPage'=>array('$push'=>array('studentID'=>'$studentID', 'studentName'=>'$studentName','dob'=>'$dob'))
                                  )
                    );

    $collection = $db->tbl_offer;
    $record = $collection->aggregate($_searchArr);
    $_outputArr = iterator_to_array($record);
    printSQLToTextFile('process.php:getEnrolledStudent (tbl_offer)', json_encode($_searchArr));

    return $_outputArr[0];
  }

  function calCourseAvailablePlace($offerID=''){
    global $db;

    $_outputArr = array();
    $_searchArr = array();

    if ($offerID.'' != ''){
      $_searchArr[] = array('$match'=>array("offerID"=>array('$eq'=>$offerID)));
    }

    $_searchArr[] = array('$lookup'=>array('from'=>'tbl_student','localField'=>'offerID','foreignField'=>'enrolled.offerID','as'=>'student'));

    $_searchArr[] = array('$unwind'=>array('path'=>'$student','preserveNullAndEmptyArrays'=> true));

    $_searchArr[] = array('$group' => array('_id'=>'$offerID', 'classSize'=>array('$first'=>array('$toInt'=>'$classSize')), 'count'=>array('$sum'=>1)));

    $_searchArr[] = array('$project'=>array('offerID'=>1,'classSize'=>'$classSize','count'=>array('$toInt'=>'$count'),'cal'=>array('$subtract'=>['$classSize','$count'])));

    //print_r(json_encode($_searchArr)."\n");
    $collection = $db->tbl_offer;
    $record = $collection->aggregate($_searchArr);
    $_outputArr = iterator_to_array($record);

    return $_outputArr[0];
  }

  function updateAvailablePlace($offerID='', $calResArr){
    global $db;

    $_outputArr = array();
    $_searchArr = array();
    $updateCond = array("offerID"=>$offerID);
    $collection = $db->tbl_offer;
    $collection->updateOne($updateCond,
                 array('$set' => array("availablePlace" => $calResArr['cal'])));
  }