<div class="alert alert-warning">
{$new_question}
</div>
<form id="innova_card_number_form" class="defaultForm  form-horizontal" action="{*$url_submit_add*}" method="post" enctype="multipart/form-data" novalidate="">
<input type="hidden" name="submitAddnewQuestion" value="1">	
<input type="hidden" name="category_id" value="{$category_id}">		
	<div class="panel" id="fieldset_0">												
	<div class="panel-heading">
<i class="icon-pencil"></i>	Add a new Question
</div>	
	<div class="form-group">													
	<label for="category_name" class="control-label col-lg-3 ">
	Question:
	</label>						
	<div class="col-lg-9 ">	
	<input type="text" name="question_name" id="question_name" value="" class="">								
	<p class="help-block">
	add new question to this category.
	</p>																	
	</div>							
	</div>	


   <div class="form-group">													
	<label for="more_infos" class="control-label col-lg-3 ">
	More Informations:
	</label>						
	<div class="col-lg-9 ">	
	<textarea name="more_infos" id="more_infos"  class="">
    </textarea>	
	<p class="help-block">
	detailed information for customers.
	</p>																	
	</div>							
	</div>	

	
	<div class="panel-footer">
	<button type="submit" value="1"  class="btn btn-default pull-right" name="submitAddnewQuestion" >
	<i class="process-icon-save"></i> Save
	</button>
	</div>		
	</div>	
	</form>
	
	
	
