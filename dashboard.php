<head>
    <?  
        require_once('_coresetting.php');
        require_once('include/header.php');
        require_once('_corefunction.php');
        require_once('include/_config.php');
        global $_DEBUG;

        //$_basename = str_replace(".php", "", basename($_SERVER['PHP_SELF']));
        //$_page = json_decode(file_get_contents("_json/{$_basename}.json"), true);
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
                        <h4 class="page-title"><?= $_page['page_title']?></h4>            
                    </div>
                </div>
            </div>
            <!-- Container fluid  -->
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Pie Chart</h5>
                                <div class="pie" style="height: 400px;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Line Chart</h5>
                                <div class="bars" style="height: 400px;"></div>
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
  <script src="assets/libs/chart/matrix.interface.js"></script>
    <script src="assets/libs/chart/excanvas.min.js"></script>
    <script src="assets/libs/flot/jquery.flot.js"></script>
    <script src="assets/libs/flot/jquery.flot.pie.js"></script>
    <script src="assets/libs/flot/jquery.flot.time.js"></script>
    <script src="assets/libs/flot/jquery.flot.stack.js"></script>
    <script src="assets/libs/flot/jquery.flot.crosshair.js"></script>
    <script src="assets/libs/chart/jquery.peity.min.js"></script>
    

    <!-- <script src="assets/libs/chart/matrix.charts.js"></script> -->

    <!-- <script src="assets/libs/chart/jquery.flot.pie.min.js"></script> -->
    <!-- <script src="assets/libs/flot.tooltip/js/jquery.flot.tooltip.min.js"></script> -->
    <!-- <script src="assets/libs/chart/turning-series.js"></script> -->
    <!-- <script src="dist/js/pages/chart/chart-page-init.js"></script> -->
  <script type="text/javascript">
    var data = [];
    var series = Math.floor(Math.random()*10)+1;
    for( var i = 0; i<series; i++)
    {
        data[i] = { label: "Series"+(i+1), data: Math.floor(Math.random()*100)+1 }
    }
    
    var pie = $.plot($(".pie"), data,{
        series: {
            pie: {
                show: true,
                radius: 3/4,
                label: {
                    show: true,
                    radius: 3/4,
                    formatter: function(label, series){
                        return '<div style="font-size:8pt;text-align:center;padding:2px;color:white;">'+label+'<br/>'+Math.round(series.percent)+'%</div>';
                    },
                    background: {
                        opacity: 0.5,
                        color: '#000'
                    }
                },
                innerRadius: 0.2
            },
            legend: {
                show: false
            }
        }
    });
    $(function() {
        $(".preloader").fadeOut();
    });
  </script>
<script src="assets/js/custom.js"></script>
