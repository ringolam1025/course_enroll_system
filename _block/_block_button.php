<?
  /* 
    Button Color:
    btn-primary
    btn-secondary
    btn-success
    btn-danger
    btn-warning
    btn-info
    btn-light
    btn-dark
    btn-link
  */
    global $_SHOW_BTN_DEBUG;
    if ($_SHOW_BTN_DEBUG) print_html($btn);
    $link = "";
    $btn_type = "";
    $form_id = "";

    if (isset($btn['id']) && $btn['id'].'' != '') $id = $btn['id'];
    if (isset($btn['link']) && $btn['link'].'' != '') $link = $btn['link'];
    if (isset($btn['action']) && $btn['action'].'' != '') $action = $btn['action'];
    if (isset($items['id']) && $items['id'].'' != '') $form_id = $items['id'];



    if ($link.'' != ''){
      $link = "location.href='".$btn['link']."'";

    }else if ($action.'' == 'save' || $action.'' == 'delete'){
      $link = 'javascript:submit_form(\'' . $form_id . '\',\'' . $action . '\',\'\',\'\',\'' . $id . '\');';

    }else if ($action.'' == 'list_delete'){
        $link = 'javascript:delete_list_page_record(\'' . $form_id . '\',\'' . $action . '\',\'' . $id . '\', \''.$items['table_name'].'\',\''.$_page['page_id'].'\');';

    }else if ($action.'' != ''){
      $link = 'javascript:custom_submit_form(\'' . $form_id . '\',\'' . $action . '\',\'' . $id . '\');';
    }
    
    ?>
    <!-- Button -->
    <button type="button" onclick="<?= $link ?>" id="<?= $btn['id'] ?>" name="<?= $btn['id'] ?>" class="btn btn-<?= $btn['class'] ?> <?= $btn['position'] ?>"><?= $btn['title'] ?></button>
    <!-- End Button -->
