<?
    global $_page;

    //print_html($items);
    $form_wizard_title = ""; 
    $form_wizard_sub_title = "";
    $form_wizard_id = "";
    $form_wizard_row = "";
    $form_wizard_row_id = "";
    $form_wizard_step_child = array();
    $rowArr = array();

    if (isset($items['title'])) $form_wizard_title = $items['title'];
    if (isset($items['id'])) $form_wizard_id = $items['id'];
    if (isset($items['row'])) $form_wizard_row = $items['row'];
    if (isset($items['row_id'])) $form_wizard_row_id = $items['row_id'];    
    
    $form_wizard_step_child = $items['field']['step_child'];    
    $wizard_items = $items['field'];
    //print_html($wizard_items);

    if (isset($_GET['str'])){
        $getArr = explode('&', base64_decode($_GET['str']));
        for ($_g=0; $_g<sizeof($getArr);$_g++) { 
            $tmp = explode('=', $getArr[$_g]);
            $strArr[$tmp[0]] = $tmp[1];
        }

        for ($_v=0; $_v<sizeof($wizard_items['field']);$_v++) {
            if (array_key_exists($wizard_items['field'][$_v]['id'], $strArr)){
                $wizard_items['field'][$_v]['value'] = $strArr[$wizard_items['field'][$_v]['id']];
            }
        }
    }
    //print_html($wizard_items['field']);
?>
    <div class="card">
<?
    $form_items = $wizard_items['field'];

    $fun = "_block_setting_".$items['id'];
    $has_fun = debug_printFunName($fun);
    if ($has_fun) $fun($form_items);
    
    $html = "";
    for ($_p=0;$_p<sizeof($form_wizard_step_child);$_p++){
        $printedArr = array();

        $html .= "<h3>".$form_wizard_step_child[$_p]['display']."</h3>";
        $html .= "<section>";
        for ($form_field=0;$form_field<sizeof($form_items);$form_field++){
            $form_child = $form_items[$form_field];
            if ($form_child['isvalid']){
                if ($form_wizard_step_child[$_p]['id'].'' == $form_child['belong_tab']){
                    if (isset($form_child['row_id'])){
                        if (!in_array($form_child['row_id'], $printedArr)){
                            $html .= "<div class='row' id='{$form_child['belong_tab']}-{$form_child['row_id']}'></div>";
                            $printedArr[] = $form_child['row_id'];
                        }
                    }
                }
            }
        }
        $html = str_get_html($html);

        for ($form_field=0;$form_field<sizeof($form_items);$form_field++){            
            $form_child = $form_items[$form_field];
            if ($form_child['isvalid']){
                if ($form_wizard_step_child[$_p]['id'].'' == $form_child['belong_tab']){
                    $ret = $html->find('#'.($form_child['belong_tab']."-".$form_child['row_id']), 0);
                    $ret->innertext .= return_output('field/field_'.trim($form_child['db_type']).'.php', $form_child);
                    $html->innertext .= $ret->innertext;
                }
            }
        }
        $html .= "</section>";
    }
?>
    <!-- Form Wizard -->    
        <div class="card-body wizard-content">
            <h4 class="card-title"><?= $form_wizard_title ?></h4>
            <h6 class="card-subtitle"><?= $form_wizard_sub_title ?></h6>
                <form id="<?= $form_wizard_id ?>" method="post" class="m-t-10">  
                    <input type="hidden" id="_page_id" name="_page_id" value='<?= $_page['page_id'] ?>'>
                    <input type="hidden" id="_json" name="_json" value='<?= json_encode($items) ?>'>                          
                    <div>
                        <?= $html; ?>
                    </div>
                </form>
            </div>
        </div>

    <?
        $_page['_footer'][] = array("id"=>$form_wizard_id,"type"=>"form_wizard");
    ?>
    <!-- End Form Wizard -->