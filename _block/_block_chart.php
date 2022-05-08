<? 
    // Non-complete!!!!!!!!!
    global $_page;
    //print_html($items);
    //print_html($_page);
    if (isset($_SESSION['login_user_type'])){
        $usertype = $_SESSION['login_user_type'];
    }else{
        $usertype = 'customer';
    }

    $table_items = $items['child']['column'];
    $buttonArr = $items['child']['buttons'];
    $title = "";
    $_sqlArr = array();
    $_sqlArr['SQL_SELECT'] = array();
    $_sqlArr['SQL_FROM'] = array();
    $_sqlArr['SQL_LEFTJOIN'] = array();
    $_sqlArr['SQL_WHERE'] = array();
    $_sqlArr['SQL_ORDERBY'] = array();
    $_sqlArr['SQL_LIMIT'] = array();
    $_DEBUG_COL = array();

    $_res_per_page = 10;

    if (!isset($_GET['page'])){
        $_GET['page'] = 1;
    }else{
        $_GET['page'] = $_GET['page'];
    }



    $fun = "_before_load_searcharea_".$items['id'];    
    $has_fun = debug_printFunName($fun);
    if ($has_fun) $fun($_POST);


    $_SHOW_SEARCH_AREA = (isset($_POST) && !empty($_POST))?true:false;
    if (isset($items['title']) && $items['title'].'' != '') $title = $items['title'];

    ?>
    <!-- Table -->
    <? if ($usertype.'' != 'customer') {?>
    <div class="table_btn" style="padding: 5px 10px;">
        <div class="row">
            <? 
                for ($i=0;$i<sizeof($buttonArr);$i++){
                    $btn = $buttonArr[$i];
                    if ($btn['isvalid']){
                        include('block/_block_button.php');
                    }                    
                } 
            ?>
            </div>
    </div>
    <? } ?>

    <div class="card">
        <a class="card-header link border-top" data-toggle="collapse" href="#Toggle-2" aria-expanded="<?= ($_SHOW_SEARCH_AREA)?'true':'false' ?>" aria-controls="Toggle-2">
            <i class="fas fa-search" aria-hidden="true"></i>
            <span>Search Area</span>
        </a>
        <div id="Toggle-2" class="collapse <?= ($_SHOW_SEARCH_AREA)?'show':'' ?>" style="">
            <div class="card-body widget-content" style='padding: 10px 15px 0px 15px;'>
                 <form id="<?= $_page['page_id'] ?>_<?= $items['id']?>" action="<?= $_page['page_id'] ?>" method="post">
                    <div>
                            <?
                                $html = "";
                                $printedArr = array();

                                for ($j=0;$j<sizeof($table_items);$j++){                                    
                                    $form_child = $table_items[$j];
                                    if ($form_child['has_search']){
                                        if (isset($_POST[$form_child['id']])){
                                            if ($form_child['db_type'].'' == 'dropdown'){
                                                //$form_child['value'] = $_POST[$form_child['id']][0];
                                                $form_child['value'] = $_POST[$form_child['id']];

                                            }else{
                                                $form_child['value'] = $_POST[$form_child['id']];
                                            }
                                        }
                                        if (isset($form_child['row_id'])){
                                            if (!in_array($form_child['row_id'], $printedArr)){
                                                $html .= "<div class='row' id='{$form_child['row_id']}'></div>";
                                                $printedArr[] = $form_child['row_id'];                                              
                                            }
                                        }
                                    }                                    
                                }
                                $html = str_get_html($html);

                                for ($j=0;$j<sizeof($table_items);$j++){                                    
                                    $form_child = $table_items[$j];
                                    if ($form_child['has_search']){
                                        if ($form_child['db_type'].'' == 'dropdown' && isset($_POST[$form_child['id']])){
                                            //$form_child['value'] = $_POST[$form_child['id']][0];
                                            $form_child['value'] = $_POST[$form_child['id']];

                                        }else if ($form_child['db_type'].'' == 'int'){
                                            $_isPOST_MIN = $form_child['id']."_min";
                                            $_isPOST_MAX = $form_child['id']."_max";
                                            
                                            if (isset($_isPOST_MIN) && !empty($_POST[$_isPOST_MIN])) $form_child['value'] = $_POST[$_isPOST_MIN];
                                            if (isset($_isPOST_MAX) && !empty($_POST[$_isPOST_MAX])) $form_child['value2'] = $_POST[$_isPOST_MAX];

                                        }else if ($form_child['db_type'].'' == 'datetime'){
                                            if (isset($_POST[$form_child['id']]) && !empty($_POST[$form_child['id']])){
                                                $form_child['value'] = $_POST[$form_child['id']];
                                            }else{
                                                $form_child['value'] = "";
                                            }
                                        
                                        }else if (isset($_POST[$form_child['id']])){
                                            $form_child['value'] = $_POST[$form_child['id']];

                                        }

                                        $ret = $html->find('#'.$form_child['row_id'], 0);
                                        $ret->innertext .= return_output('field/field_'.trim($form_child['db_type']).'.php', $form_child);
                                        $html->innertext .= $ret->innertext;
                                        
                                        
                                    }                                    
                                }
                                // print result
                                echo $html;
                            ?>
                        <div style="padding-bottom: 10px;">
                            <input type="submit" class="btn btn-outline-primary btn-sm" value="Search">
                            <button type="button" class="btn btn-outline-warning btn-sm" onclick="clearCondition();" >Reset</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>   

        <? if ($title.'' != ''){ ?>
        <div class="card-body">
            <h5 class="card-title m-b-0"><?= $title ?></h5>
        </div>
        <? } ?>
            
        <div class="table-responsive">
            <table class="table">
                <thead class="thead-light">
                    <tr>
                        <? if ($usertype.'' != 'customer') {?>
                        <th>
                            <label class="customcheckbox m-b-20">
                                <input type="checkbox" id="mainCheckbox" />
                                <span class="checkmark"></span>
                            </label>
                        </th>
                        <? } ?>
                        <?                              
                            for ($i=0;$i<sizeof($table_items);$i++){
                                if ($table_items[$i]['isvalid']){
                                    $_DEBUG_COL[] = "_config_row_".$table_items[$i]['db_field'];                        
                                    if (isset($table_items[$i]['foreign_table']) &&
                                            $table_items[$i]['foreign_table'].'' != ''){
                                        if ($table_items[$i]['in_select']){
                                            $_sqlArr['SQL_SELECT'][] = $table_items[$i]['foreign_table'].".".$table_items[$i]['foreign_display']." as ".
                                            $table_items[$i]['db_field'];

                                            $_sqlArr['SQL_LEFTJOIN'][] = "{$table_items[$i]['foreign_table']} ON ({$table_items[$i]['foreign_table']}.{$table_items[$i]['foreign_option']} = {$items['table_name']}.{$table_items[$i]['db_field']})";  
                                        }
                                                  
                                    }else{
                                        if ($table_items[$i]['in_select']) $_sqlArr['SQL_SELECT'][] = $items['table_name'].".".$table_items[$i]['db_field'];
                                    }

                                    if ($table_items[$i]['isvalid'] && $table_items[$i]['show_in_list']){
                                    
                        ?>
                                <th scope="col"><?= $table_items[$i]['label'] ?></th>
                        <? 
                                }
                             }
                            }  
                        ?>
                    </tr>
                </thead>
                <tbody class="customtable">
                    <?
                    $_sqlArr['SQL_SELECT'][] = $items['table_name'].".pkey";
                    $_sqlArr['SQL_FROM'][] = $items['table_name'];

                    $fun = "_before_sql_".$items['id'];
                    $has_fun = debug_printFunName($fun);
                    if ($has_fun) $fun($_sqlArr);

                    for ($__fun_debug=0;$__fun_debug<sizeof($_DEBUG_COL);$__fun_debug++) { 
                        $has_fun = debug_printFunName($_DEBUG_COL[$__fun_debug]);
                    }

                    $_sql = genListPageSQLStatement($_sqlArr, $items, $_res_per_page);

                    $_count_sql = genListPageSQLStatement($_sqlArr, $items);
                    $_count_res = run_query($_count_sql);
                    $num_of_res = count_row($_count_res);

                    //print_html($_sqlArr);
                    debug_showSQL($_sql);
                    $result = run_query($_sql);
                    $num_of_page = ceil($num_of_res/$_res_per_page);


                    while($row = fetch_assoc($result)) {
                        if ($usertype.'' != 'customer'){
                            $link = "onclick=\"window.location='".$items['details']."?pkey=".$row['pkey']."';\"";
                        }else{
                            $link = "";
                        }
                        
                    ?>

                        <tr>
                            <? if ($usertype.'' != 'customer') {?>
                            <th>
                                <label class="customcheckbox">
                                    <input type="checkbox" class="listCheckbox" value="<?= $row['pkey'] ?>" />
                                    <span class="checkmark"></span>
                                </label>
                            </th>
                            <? } ?>
                            <? for ($i=0;$i<sizeof($table_items);$i++){ ?>
                                <? 
                                    if ($table_items[$i]['isvalid'] && $table_items[$i]['show_in_list']){
                                        if ($table_items[$i]['db_type'].'' == 'datetime'){
                                ?>                                 
                                        <td <?= $link ?> ><?= dateformat($row[$table_items[$i]['db_field']], 'Y-m-d') ?></td>

                                    <? }else if ($table_items[$i]['db_type'].'' == 'int'){ ?>
                                        <td <?= $link ?> ><?= number_format($row[$table_items[$i]['db_field']], 2) ?></td>

                                    <? }else{
                                        $html = $row[$table_items[$i]['db_field']];
                                        $fun = "_config_row_".$table_items[$i]['id'];
                                        //print_html($fun);
                                        if (function_exists($fun)) $fun($row, $html);
                                    ?>
                                        <td <?= $link ?> ><?= $html ?></td>
                                    <? } ?>
                                <? } ?>
                            <? } ?>
                        </tr>
                    <? } ?>
                    <?
                        if (count_row($result)<=0){
                    ?>
                        <tr>
                            <td style="text-align: center;" colspan="<?= (sizeof($table_items)+1) ?>">No Data in this table!</td>
                        </tr>
                    <?
                        }
                    ?>

                </tbody>
            </table>
            <div class="col-md-4">
                <span>
                    Listing: <?=((($_GET['page']-1)*$_res_per_page)+1)?>-<?=($num_of_res<((($_GET['page']-1)*$_res_per_page)+$_res_per_page))?$num_of_res:((($_GET['page']-1)*$_res_per_page)+$_res_per_page)?> (Total: <?=$num_of_res?>)
                </span>
                <ul class="pagination">
                    <li class="page-item <?= ($_GET['page'].'' == 1)?'disabled':'' ?>"> 
                        <a class="page-link" href="?page=<?=($_GET['page']-1)?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                            <span class="sr-only">Previous</span>
                        </a>
                    </li>
                    <?                          
                        for ($_p=0;$_p<$num_of_page;$_p++) {
                            $active = "";
                            if (isset($_GET['page']) && $_GET['page'].'' == ($_p+1)){
                                $active = 'active';
                            }
                    ?>
                            <li class="page-item <?= $active ?>">
                                <a class="page-link" href="?page=<?=($_p+1)?>"><?= ($_p+1) ?></a>
                            </li>
                    <?  } ?>                    

                    <li class="page-item <?= ($_GET['page'].'' == $num_of_page)?'disabled':'' ?>">
                        <a class="page-link" href="?page=<?=($_GET['page']+1)?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                            <span class="sr-only">Next</span>
                        </a>
                    </li>
                </ul>
            </div>            
        </div>
    </div>
    <!-- End Table -->
    <script type="text/javascript">
        function clearCondition(){
            //
            $('#<?= $_page['page_id'] ?>_<?= $items['id']?>').find(':input').each(function() {
                var type = this.type.trim();

                if (type == 'submit' || type == 'button'){
                    //console.log(this.type);
                }else{
                    $(this).val('');
                }
                
            });

        }
    </script>

