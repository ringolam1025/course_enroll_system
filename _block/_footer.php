<?
	global $_page;
?>
<!-- ============================================================== -->
<!-- Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
<footer class="footer text-center">
	<?= $FOOTER_TITLE ?>
</footer>

<!-- ============================================================== -->
<!-- End Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
<!-- All Jquery -->
<!-- ============================================================== -->
<script src="assets/libs/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap tether Core JavaScript -->
<script src="assets/libs/popper.js/dist/umd/popper.min.js"></script>
<script src="assets/libs/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- slimscrollbar scrollbar JavaScript -->
<script src="assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script>
<script src="assets/extra-libs/sparkline/sparkline.js"></script>
<!--Wave Effects -->
<script src="dist/js/waves.js"></script>
<!--Menu sidebar -->
<script src="dist/js/sidebarmenu.js"></script>
<!--Custom JavaScript -->
<script src="dist/js/custom.min.js"></script>

<!--This page JavaScript -->
<!-- <script src="dist/js/pages/dashboards/dashboard.js"></script> -->

<!-- Charts js Files -->
<script src="assets/libs/flot/excanvas.js"></script>
<script src="assets/libs/flot/jquery.flot.js"></script>
<script src="assets/libs/flot/jquery.flot.pie.js"></script>
<script src="assets/libs/flot/jquery.flot.time.js"></script>
<script src="assets/libs/flot/jquery.flot.stack.js"></script>
<script src="assets/libs/flot/jquery.flot.crosshair.js"></script>
<script src="assets/libs/flot.tooltip/js/jquery.flot.tooltip.min.js"></script>
<script src="dist/js/pages/chart/chart-page-init.js"></script>



<!-- Form jQuery -->
<script src="assets/libs/inputmask/dist/min/jquery.inputmask.bundle.min.js"></script>
<script src="dist/js/pages/mask/mask.init.js"></script>
<script src="assets/libs/select2/dist/js/select2.full.min.js"></script>
<script src="assets/libs/select2/dist/js/select2.min.js"></script>
<script src="assets/libs/jquery-asColor/dist/jquery-asColor.min.js"></script>
<script src="assets/libs/jquery-asGradient/dist/jquery-asGradient.js"></script>
<script src="assets/libs/jquery-asColorPicker/dist/jquery-asColorPicker.min.js"></script>
<script src="assets/libs/jquery-minicolors/jquery.minicolors.min.js"></script>
<script src="assets/libs/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script src="assets/libs/quill/dist/quill.min.js"></script>

<!-- Data Table -->
<script src="assets/extra-libs/multicheck/datatable-checkbox-init.js"></script>
<script src="assets/extra-libs/multicheck/jquery.multicheck.js"></script>
<script src="assets/extra-libs/DataTables/datatables.min.js"></script>

<!-- toastr -->
<script src="assets/libs/toastr/build/toastr.min.js"></script>

<!-- Form Wizard -->
<script src="assets/libs/jquery-steps/build/jquery.steps.min.js"></script>
<script src="assets/libs/jquery-validation/dist/jquery.validate.min.js"></script>

<!-- Autocomplete -->
<!-- <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script> -->



<script type="text/javascript">
	// $( document ).ready(function() {
	// 	console.log( "ready in footer!" );
	// 	//$('#zero_config').DataTable();
	// 	<?
	// 	if (isset($_page['_footer'])){
	// 	    for ($_y=0;$_y<sizeof($_page['_footer']);$_y++){
	// 	    	if ($_page['_footer'][$_y]['type'].'' == 'datetime'){
	// 	?>
	// 				$('#<?= $_page['_footer'][$_y]['id']?>').datepicker({
	// 					autoclose: true,
	// 	            	todayHighlight: true,
	// 	            	format: '<?= $_page['_footer'][$_y]['formart'] ?>'
	// 	    		});
	// 	<?
	// 	    	}else if ($_page['_footer'][$_y]['type'].'' == 'form_wizard'){
	// 	?>    		
	// 				var form = $("#<?= $_page['_footer'][$_y]['id']?>");
	// 				form.validate({
	//                 errorPlacement: function errorPlacement(error, element) { element.before(error); },
	// 	                // rules: {
	// 	                //     confirm: {
	// 	                //         equalTo: "#password"
	// 	                //     }
	// 	                // }
	// 	            });
	// 	            form.children("div").steps({
	// 	                headerTag: "h3",
	// 	                bodyTag: "section",
	// 	                transitionEffect: "slide",
	// 	                transitionEffectSpeed: 350,
	// 	                onStepChanging: function(event, currentIndex, newIndex) {
	// 	                	if (newIndex > currentIndex){
	// 	                		form.validate().settings.ignore = ":disabled,:hidden";
	// 	                    	return form.valid();
	// 	                	}else{
	// 	                		return true;
	// 	                	}
		                    
	// 	                },
	// 	                onFinishing: function(event, currentIndex) {
	// 	                    form.validate().settings.ignore = ":disabled";
	// 	                    return form.valid();
	// 	      //               if (form.valid()){		                    	
	// 							// return true;
	// 	      //               }else{
	// 	      //               	return false;
	// 	      //               }
	// 	                },
	// 	                onFinished: function(event, currentIndex) {
	// 	                	submit_form_wizard(<?= $_page['_footer'][$_y]['id']?>);

	// 	                }
	// 	            });
	// 	<?
	// 	    	}else if ($_page['_footer'][$_y]['type'].'' == 'autocomplete'){
	// 	    		?>
	// 	    			// $( "#customer_id" ).autocomplete({
	// 	    			// 	source: "autocomplete.php",
	//   						// minLength: 1
	// 		      //       });
	// 	    		<?

	// 	    	}
	// 	    }
	// 	}
	// 	?>
	// });	
</script>


<?
	// $fun = "_after_page_".$_page['page_id'];        
	// $has_fun = debug_printFunName($fun);
	// if ($has_fun) $fun();
?>