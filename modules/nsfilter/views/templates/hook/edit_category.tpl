<div class="row">
<div class="col-lg-12">
<form id="address_form" class="defaultForm form-horizontal adminaddresses" action="index.php?controller=AdminModules&configure={$mod_name}&id={$category_id}&updatesimplequiz_categories&token={$update_token}" method="post" enctype="multipart/form-data" novalidate="">
<div class="panel" id="fieldset_0">												
<div class="panel-heading">
{l s='Edit Category Name'}
</div>								
<div class="form-wrapper">											
<div class="form-group">
<input type="hidden" name="category_id" value="{$category_id}">				
</div>						
<div class="form-group">			
<label class="control-label col-lg-3 required">
<span class="label-tooltip" data-toggle="tooltip" data-html="true" title="" data-original-title="Caracteres interdits &amp;lt;&amp;gt;;=#{}">
{l s='Category Name:'}
</span>
</label>			
<div class="col-lg-4 ">
<input type="text" name="category_name" id="category_name" value="{$quiz_cat->category_name}" class="" required="required">																	
</div>		
</div>											



																
</div><!-- /.form-wrapper -->					
<div class="panel-footer">
<button type="submit"  name="submitUpdateCategory" class="btn btn-default pull-right">
<i class="process-icon-save"></i>{l s='Save'}
</button>
<a href="{$url_back}" class="btn btn-default" >
<i class="process-icon-back"></i>{l s='Back to list' mod='innovavoucher'}
</a>
</div>							
</div>		
</form>
</div>
</div>