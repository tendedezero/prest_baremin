<div class="alert alert-warning">
{$new_question}
</div>
<form id="innova_card_number_form" class="defaultForm  form-horizontal" action="{*$url_submit_add*}" method="post" enctype="multipart/form-data" novalidate="">
<input type="hidden" name="submitUpdateQuestion" value="1">	
<input type="hidden" name="category_id" value="{$category_id}">	
<input type="hidden" name="id_question" value="{$id_question}">		
	<div class="panel" id="fieldset_0">												
	<div class="panel-heading">
<i class="icon-pencil"></i>	Update Question
</div>	
	<div class="form-group">													
	<label for="question_name" class="control-label col-lg-3 ">
	Question:
	</label>						
	<div class="col-lg-9 ">	
	<input type="text" name="question_name" id="question_name" value="{$question->question_name}" class="">								
	<p class="help-block">
	updating this question 
	</p>																	
	</div>							
	</div>	
    <div class="form-group">													
	<label for="more_infos" class="control-label col-lg-3 ">
	More Informations:
	</label>						
	<div class="col-lg-9 ">	
	<textarea name="more_infos" id="more_infos"  class="">
	{$question->more_infos}
    </textarea>	
	<p class="help-block">
	detailed information for customers.
	</p>																	
	</div>							
	</div>	 
     
	
	<div class="panel-footer">
	<button type="submit" value="1"  class="btn btn-default pull-right" name="submitUpdateQuestion" >
	<i class="process-icon-save"></i> Save
	</button>
	<a href="{$url_back_questions}&id={$category_id}&viewsimplequiz_categories" class="btn btn-default" >
    <i class="process-icon-back"></i>{l s='Back to list' mod='innovavoucher'}
    </a>
	</div>		
	</div>	
	</form>
	
	
	
