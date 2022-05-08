<?	
	// Init Modal setting array
	$golbalModalArr = $_page['modalSetting'];
	$addModalArr = $golbalModalArr['newModalTitle'];
	$delModalArr = $golbalModalArr['delModalTitle'];
	$successModalArr = $golbalModalArr['successModalTitle'];
	$failModalArr = $golbalModalArr['failModalTitle'];
?>
<!-- Add Modal -->
<div id="addModal" class="modal fade">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form id="newRecord">         
				<div class="modal-header">						
					<h4 class="modal-title">Add <?= $golbalModalArr['defaultTitle']?></h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				</div>
				<div class="modal-body">
					<div class="row">
						<? 
	                        if ($_page['pageType'].'' != 'detail'){
	                        	for ($i=0;$i<sizeof($_tableList);$i++){
		                        	$_item = $_tableList[$i];
		                        	if (isset($_item['show_in_newModal']) && $_item['show_in_newModal']){
		                        		//printNewModalHTML($_item, $_page['colName']);
		                                printHTML($_item, $_page['pkeyCol'], 'newModal_class');
		                        	}	                            
		                		}

	                        }else if ($_page['page_id'].'' == 'enrolled_student'){
	                        	$offerID = $_GET['offerID'];
	                        	$condition = array('enrolled.offerID'=>array('$ne'=>$offerID));
	                        	$select_option = getSearchOption3('tbl_student', 'studentID', 'studentName', $condition);

	                        	if (sizeof($select_option)>0){
									echo "<div class='col-md-12'>";
									echo "<label>Student ID</label>";
									echo "<select class='form-control basic-multiple' id='studentID' name='studentID'>";
									for ($_s=0;$_s<sizeof($select_option);$_s++) {
										echo "<option value='{$select_option[$_s]['option']}'>{$select_option[$_s]['display']}</option>";
									}
									echo "</select>";									
									echo "<input type='hidden' name='offerID' value='{$offerID}'>";
									echo '</div>';
								}
	                        }	                        
	                	?>
					    <!-- 
				  			<label>Display Order</label>									  			
				  			<label>Valid?</label>
				  		-->
				  	</div>							
				</div>
				<div class="modal-footer">
					<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
					<input type="submit" class="btn btn-success" value="Add">
				</div>
			</form>
		</div>
	</div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<form id="delRecord">
				<div class="modal-header">						
					<h4 class="modal-title">Delete <?= $golbalModalArr['defaultTitle']?></h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				</div>
				<div class="modal-body">
					<input type="hidden" name="sample_code" id="deleteItem">
					<p>Are you sure you want to delete these Record(s)?</p>
					<div style="color:red; font-weight:bolder;word-break: break-all; word-wrap: break-word;" id="recordList"></div>
					<p class="text-warning"><small>This action cannot be undone.</small></p>
				</div>
				<div class="modal-footer">
					<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
					<input type="submit" class="btn btn-danger" value="Delete">
				</div>
			</form>
		</div>
	</div>
</div>

<!-- Success Modal -->
<div id="successModal" class="modal fade">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-body">
				<!-- <div > -->
					<div class="o-circle c-container__circle o-circle__sign--success">
						<div class="o-circle__sign"></div>
					</div>
					<div class="head">
						<h4 style="text-align:center;" class="modal-title">Success!</h4>
					</div>
				<!-- </div> -->
				<!-- <div class="checkmark-circle">
					<div class="background"></div>
					<div class="checkmark draw"></div>
				</div> -->
			</div>
		</div>
	</div>
</div>


<!-- Fail Modal -->
<div class="modal fade" id="failModal" tabindex="-1" role="dialog" aria-hidden=""><!-- true -->
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-body">
		      	<!-- <div style="text-align:center;"> -->
					<div class="o-circle c-container__circle o-circle__sign--failure">
			    		<div class="o-circle__sign"></div>  
			  		</div>   
			  		<div class="head">
			  			<h4 style="text-align:center;" class="modal-title" id="failModal_msg"></h4>
			  		</div>	
			  	<!-- </div> -->
      		</div>
    	</div>
  	</div>
</div>


<script type="text/javascript">
    $(document).ready(function() {
        $('.basic-multiple').select2();
    });
</script>