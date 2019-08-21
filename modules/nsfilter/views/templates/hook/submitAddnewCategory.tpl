<div class="alert alert-warning">
{$message_alert}
</div>
<form id="innova_card_number_form" class="defaultForm  form-horizontal" action="{$url_submit_add}" method="post" enctype="multipart/form-data" novalidate="">
<input type="hidden" name="submitAddnewCategory" value="1">		
	<div class="panel" id="fieldset_0">												
	<div class="panel-heading">
<i class="icon-pencil"></i>	Add a new Category
</div>	
	<div class="form-group">													
	<label for="category_name" class="control-label col-lg-3 ">
	Category name:
	</label>						
	<div class="col-lg-9 ">	
	<input type="text" name="category_name" id="category_name" value="" class="">								
	<p class="help-block">
	add new category to the list.
	</p>																	
	</div>							
	</div>		
	<div class="panel-footer">
	<button type="submit" value="1"  class="btn btn-default pull-right" name="submitAddnewCategory" >
	<i class="process-icon-save"></i> Save
	</button>
	</div>		
	</div>	
	</form>