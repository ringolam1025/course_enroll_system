<? 
    global $_page;
    //print_html($_page);
    //print_html($items);
    //print_html(json_encode($items));

    $form_items = $items['field'];
    $buttonArr = $items['field']['buttons'];
    //print_html($buttonArr);
    $tabArr = array();
    $_sqlArr = array();

    if (isset($_GET['pkey']) && $_GET['pkey'] != ''){
        $_pkey = $_GET['pkey'];

        $_sqlArr['SQL_SELECT'][] = $items['table'].".*";
        $_sqlArr['SQL_FROM'][] = $items['table'];
        $_sqlArr['SQL_LEFTJOIN'][] = "";
        $_sqlArr['SQL_WHERE'][] = "pkey = '".$_pkey."'";
        $_sqlArr['SQL_ORDERBY'][] = "";

        
        $fun = "_before_sql_".$items['id'];        
        $has_fun = debug_printFunName($fun);
        if ($has_fun) $fun($_sqlArr);
        $_sql = genListPageSQLStatement($_sqlArr);
        debug_showSQL($_sql);

        $_res = run_query($_sql);
        $_row = fetch_assoc($_res);
        $items['pkey'] = $_GET['pkey'];
        for ($_i=0;$_i<sizeof($form_items['field']);$_i++){
          if ($form_items['field'][$_i]['isvalid']){
            $form_items['field'][$_i]['value'] = $_row[$form_items['field'][$_i]['db_field']];
          }
        }
    }

    

    ?>

    <!-- From -->
    <div class="card">
        <form name="<?= $items['id'] ?>" id="<?= $items['id'] ?>" method="post">
            <input type="hidden" id="_page_id" name="_page_id" value='<?= $_page['page_id'] ?>'>
            <input type="hidden" id="_json" name="_json" value='<?= json_encode($items) ?>'>
            <ul class="nav nav-tabs" role="tablist">
            <?
                for ($tab=0;$tab<sizeof($form_items['tab_list']);$tab++){
                    $tab_child = $form_items['tab_list'][$tab];
                    $tab_active = ($tab_child['is_default'])?'active':'';
                    if ($tab_child['isvalid']){
                        $tabArr[] = array("id"=>$tab_child['id'], "is_default"=>$tab_active);
            ?>        
                        <li class="nav-item">
                            <a class="nav-link <?= $tab_active ?>" data-toggle="tab" href="#<?= $tab_child['id'] ?>" role="tab">
                                <span class="hidden-sm-up"></span>
                                <span class="hidden-xs-down"><?= $tab_child['title'] ?></span>
                            </a>
                        </li>
            <?
                }
            }
            ?>
            </ul>
            <div class="tab-content tabcontent-border">
            <?                
                for ($i=0;$i<sizeof($tabArr);$i++){
                    $printedArr = array();
                    $html = "";
            ?>
                    <div class="tab-pane <?= $tabArr[$i]['is_default'] ?>" id="<?= $tabArr[$i]['id'] ?>" role="tabpanel">
                        <div class="p-20">
                        <?
                            for ($form_field=0;$form_field<sizeof($form_items['field']);$form_field++){                                
                                $form_child = $form_items['field'][$form_field];                                
                                if ($form_child['isvalid']){
                                    if ($tabArr[$i]['id'].'' == $form_child['belong_tab']){                                        
                                        if (isset($form_child['row_id'])){
                                            if (!in_array(($form_child['row_id']), $printedArr)){
                                                $html .= "<div class='row' id='{$form_child['belong_tab']}-{$form_child['row_id']}'></div>";
                                                $printedArr[] = $form_child['row_id'];
                                            }
                                        }
                                    }
                                }
                            }
                            $html = str_get_html($html);

                            for ($form_field=0;$form_field<sizeof($form_items['field']);$form_field++){ 
                                $form_child = $form_items['field'][$form_field];
                                if ($form_child['isvalid']){
                                    if ($tabArr[$i]['id'].'' == $form_child['belong_tab']){
                                        $ret = $html->find('#'.($form_child['belong_tab']."-".$form_child['row_id']), 0);
                                        $ret->innertext .= return_output('field/field_'.trim($form_child['db_type']).'.php', $form_child);
                                        $html->innertext .= $ret->innertext;
                                    }
                                }
                            }
                            //print_html($printedArr);
                            echo $html;
                        ?>
                        </div>
                    </div>
                <? } ?>      
            </div>           

            <div class="border-top">
                <div class="card-body">
                    <? for ($i=0;$i<sizeof($buttonArr);$i++){ ?>
                        <? 
                            $btn = $buttonArr[$i];
                            if ($btn['isvalid']){
                                include('block/_block_button.php');
                            }
                            
                        ?>
                    <? } ?>
                    
                </div>
            </div>
    </form>
    </div>
    <!-- End Form -->