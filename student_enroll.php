<head>
    <?  
        require_once('_coresetting.php');
        require_once('include/header.php');
        require_once('_corefunction.php');
        require_once('include/_config.php');
        global $_DEBUG;

        checkSessionLogin();
        
        $_basename = str_replace(".php", "", basename($_SERVER['PHP_SELF']));
        $_page = json_decode(file_get_contents("_json/{$_basename}.json"), true);
        $_tableList = showInListForTable($_page['tableList']);
        $_headerForm = showInListForTable($_page['headerForm']);
    ?>
    <title><?= $TITLE ?></title>
    <style>
      .select,
      #locale {
        width: 100%;
      }

      #table{
          -webkit-user-select: none; /* Chrome all / Safari all */
          -moz-user-select: none;   /* Firefox all */
          -ms-user-select: none;  /* IE 10+ */
          user-select: none;  /* Likely future */ 
        }
    </style>
</head>
<body>
    <? if (!$_DEBUG){ ?>
        <div class="preloader">
            <div class="lds-ripple">
                <div class="lds-pos"></div>
                <div class="lds-pos"></div>
            </div>
        </div>
    <? } ?>
    <div id="main-wrapper"> 
        <? include('_block/_header.php'); ?>
        <? include('_block/_sidemenu.php'); ?>       
        
        <div class="page-wrapper">
            <?  if ($_SHOW_SESSION) print_html($_SESSION); ?>
            <div class="page-breadcrumb">
                <div class="row">
                    <div class="col-12 d-flex no-block align-items-center">
                        <h4 class="page-title" id="page-title"><?= $_page['page_title']?></h4>            
                    </div>
                </div>
            </div>
            <!-- Container fluid  -->
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <? require_once('include/_detailsForm.php'); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <? 
                                if ($_page['pageType'].'' != 'detail') require_once('include/_searchArea.php');
                            ?>
                            <div class="pl-3 pr-3">
                                <? require_once ("include/_dataTable.php") ?>
                                <? require_once ("include/_model.php"); ?>
                            </div>
                        </div>                        
                    </div>
                </div>
            </div>
            <!-- footer -->
            <? //include('_block/_footer.php');?>
        </div>
    </div>

</body>
</html>
<script>
    var tableColArr = <?= json_encode($_tableList) ?>;
    var detailArr = <?= json_encode($_headerForm) ?>;
    var pageArr = <?= json_encode($_page); ?>;

    var pageType = '<?= $_page['pageType'] ?>';
    var tableName = '<?= $_page['tableName'] ?>';
    var pkeyCol = '<?= $_page['pkeyCol'] ?>';
    var prefix = '<?= $_page['prefix'] ?>';
    var get_pkey = '<?= $_GET['studentID'] ?>';


    $(function() {
        getStudentEnroll(pageArr);
        setSelectedButtonStatus('disable');
        $(".preloader").fadeOut();
    });
</script>

<script src="assets/js/custom.js"></script>
