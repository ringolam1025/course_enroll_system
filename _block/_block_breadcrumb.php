<?
    //print_html($items);
    $title = $items['page_title'];
    $has_breadcrumb = false;
    if (isset($items['field']) && sizeof($items['field']) > 0) $has_breadcrumb = true;
    
    $breadcrumbArr = $items['field'];
?>
<!-- Bread crumb and right sidebar toggle -->
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title"><?= $title ?></h4>
            <? if ($has_breadcrumb){ ?>
                <div class="ml-auto text-right">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <? 
                                for ($i=0;$i<sizeof($breadcrumbArr);$i++){
                                    $dispaly = '';
                                    $items_title = $breadcrumbArr[$i]['title'];
                                    $items_href = $breadcrumbArr[$i]['href'];

                                    if ($i!=(sizeof($breadcrumbArr)-1)){
                                        echo '<li class="breadcrumb-item" ><a href="'.$items_href.'">'.$items_title.'</a></li>';
                                    }else{
                                        echo '<li class="breadcrumb-item active" aria-current="page">'.$items_title.'</li>';
                                    }
                                }                                    
                            ?>
                        </ol>
                    </nav>
                </div>
            <? } ?>
        </div>
    </div>
</div>
<!-- End Bread crumb and right sidebar toggle -->