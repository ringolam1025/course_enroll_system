<?
    //print_html($items);
    $child = array();
    if (isset($items['title'])) $title = $items['title'];
    if (isset($items['id'])) $id = $items['id'];
    if (isset($items['row'])) $row = $items['row'];
    if (isset($items['child'])) $child = $items['child'];

    ?>
    <!-- Icon Box -->
    <!-- 
       //bg-success
       //bg-info
       //bg-cyan
       //bg-danger
       //bg-warning
     -->
                <div class="<?= $row ?>">
                    <div class="card" id="<?= $id ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= $title ?></h5>
                            <div class="row">
                                <? for ($j=0;$j<sizeof($child);$j++){ ?>                                    
                                        <div class="<?= $child[$j]['row'] ?>">
                                            <a href="<?= $child[$j]['href'] ?>">
                                                <div class="card card-hover">
                                                    <div class="box <?= $child[$j]['color'] ?> text-center">
                                                        <h1 class="font-light text-white">
                                                            <i class="<?= $child[$j]['icon'] ?>"></i>
                                                        </h1>
                                                        <h6 class="text-white"><?= $child[$j]['title'] ?></h6>
                                                    </div>
                                                </div>
                                             </a>
                                        </div>
                                <? } ?>
                            </div>
                        </div>
                    </div>
                </div>
    <!-- End Icon Box -->