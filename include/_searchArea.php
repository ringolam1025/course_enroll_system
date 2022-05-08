<? $_SHOW_SEARCH_AREA = true; ?>
<div class="card">
    <a class="card-header link border-top" data-toggle="collapse" href="#Toggle-2" aria-expanded="<?= ($_SHOW_SEARCH_AREA)?'true':'false' ?>" aria-controls="Toggle-2">
        <i class="fas fa-search" aria-hidden="true"></i><span>Search Area</span>
    </a>
    <div id="Toggle-2" class="collapse <?= ($_SHOW_SEARCH_AREA)?'show':'' ?>" style="">
        <div class="card-body widget-content" style='padding: 10px 15px 0px 15px;'>
            <form id="search">
                <div class="modal-body">
                    <div class="row">
                        <? 
                            for ($i=0;$i<sizeof($_page['tableList']);$i++){
                                $_item = $_page['tableList'][$i];
                                if ($_item['show_in_search']){
                                    // printSearchTypeHTML($_item);
                                    printHTML($_item, '', 'searchArea_class'); // Print HTML using $_item                  
                                }
                            }
                         ?>
                    </div>
                    <div class="row">                        
                        <div class="col-md-8 pt-3">
                            <button type="submit" class="btn btn-outline-primary btn-block"> Search </button>
                        </div>
                        <div class="col-md-4 pt-3">
                            <button type="button" id="btnResetSearch" class="btn btn-outline-secondary btn-block"> Reset </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('.basic-multiple').select2();
    });
</script>