{literal}
<script>
function showUser(str) {   
 if (str == "") {   
 document.getElementById("txtHint").innerHTML ="<p style='color:red;'>No Group Selected!</p>";   
 return;   
 } else {     
 if (window.XMLHttpRequest) {  
 // code for IE7+, Firefox, Chrome, Opera, Safari           
 xmlhttp = new XMLHttpRequest();      
 } else {           
 // code for IE6, IE5     
 xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");    
 }       
 xmlhttp.onreadystatechange = function() {  
 if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {   
 document.getElementById("txtHint").innerHTML = xmlhttp.responseText;   
 }   
 };      
 xmlhttp.open("GET","./../modules/simplequiz/getfeatures.php?id_feature="+str,true);   
 xmlhttp.send();    }}
 </script>
 {/literal}
 <div class="alert alert-warning">
{$new_answer}
</div>
<form id="innova_card_number_form" class="defaultForm  form-horizontal" action="{*$url_submit_add*}" method="post" enctype="multipart/form-data" novalidate="">
	<div class="panel" id="fieldset_0">												
	<div class="panel-heading">
<i class="icon-pencil"></i>	Add a new Answer
</div>	
	<div class="form-group">													
	<label for="category_name" class="control-label col-lg-3 ">
	Answer:
	</label>						
	<div class="col-lg-9 ">	
	{if isset($answerObj) && $answerObj->id_answer!=''}
	<input type="text" name="answer_name" id="answer_name" value="{$answerObj->answer_name}" class="">	
    <input type="hidden" name="id_answer" value="{$answerObj->id_answer}">
    <input type="hidden" name="question_id" value="{$answerObj->question_id}">	 	
	<p class="help-block">
	Update this question.
	</p>			<div class="form-group">	<select class="form-control" name="id_feature" onchange="showUser(this.value);">    <option value="">Choose a Feature</option>	{foreach from=$all_features item=feature}	    <option value="{$feature.id_feature}">{$feature.name}</option>                                      {/foreach}								      </select>    <span class="help-block"></span>	</div>    <label for="category_name" class="control-label col-lg-3 ">	   Answer associated feature:	  </label> 		
	<div id="txtHint">               </div>	  
	
	<p class="help-block">	 Select a feature to display it values here.	</p>
	
	</div>							
	</div>		
	<div class="panel-footer">
	<button type="submit" value="1"  class="btn btn-default pull-right" name="submitUpdateAnswer" >
	<i class="process-icon-save"></i> Update
	</button>
	</div>		
	</div>
	{else}
	<input type="hidden" name="submitAddnewQuestion" value="1">	
    <input type="hidden" name="question_id" value="{$id_question}">	
	<input type="text" name="answer_name" id="answer_name" value="" class="">	   	
	<p class="help-block">
	add new answer to this question.
	</p>  	<div class="form-group">	<select class="form-control" name="id_feature" onchange="showUser(this.value);" >    <option value="">Choose a Feature</option>	{foreach from=$all_features item=feature}	    <option value="{$feature.id_feature}">{$feature.name}</option>                                      {/foreach}								      </select>    <span class="help-block"></span>	</div>	 
     <label for="category_name" class="control-label col-lg-3 ">
	   Answer associated feature:
	  </label> 		        <div id="txtHint">               </div>
	  <p class="help-block">
	 Select a feature to display it values here.
	</p>
	</div>							
	</div>		
	<div class="panel-footer">
	<button type="submit" value="1"  class="btn btn-default pull-right" name="submitAddnewAnswer" >
	<i class="process-icon-save"></i> Save
	</button>
	</div>		
	</div>	
	{/if}	
</form>
	
	
	
