<div class="card">
    <div class="card-body">        
        <form id="detailForm">
            <div class="row">
                <?
                    for ($_df=0; $_df < sizeof($_headerForm); $_df++) { 
                        $_item = $_headerForm[$_df];
                        printHTML($_item, $_page['pkeyCol'], 'detail_class');
                    }
                ?>
            </div>
            <div class="row">
                <!-- <div class="col-md-8 pt-3">
                    <button type="submit" class="btn btn-outline-primary btn-block"> Update </button>
                </div> -->
                <? if ($_SESSION['user_type'].'' != 'student') { ?>
                    <div class="col-md-12 pt-3">
                        <button type="button" id="btnBack" class="btn btn-outline-secondary btn-block"> Back </button>
                    </div>
                <? } ?>
            </div>
        </form>        
    </div>
</div>