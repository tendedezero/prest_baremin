{*
* 2007-2016 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2016 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

{literal}
<script>
function getVote(int) {
  if (window.XMLHttpRequest) {
    // code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp=new XMLHttpRequest();
  } else {  // code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onreadystatechange=function() {
    if (this.readyState==4 && this.status==200) {
      document.getElementById("poll").innerHTML=this.responseText;
    }
  }
  xmlhttp.open("GET","./modules/nsfilter/poll_vote.php?id_order="+int,true);
  xmlhttp.send();
}
//second function
function getCategoryId(int) {

  if (window.XMLHttpRequest) {
    // code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp=new XMLHttpRequest();
  } else {  // code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onreadystatechange=function() {
    if (this.readyState==4 && this.status==200) {
      document.getElementById("moreInfos").innerHTML=this.responseText;
	  
    }
  }  

 
	  
  
  xmlhttp.open("GET","./modules/nsfilter/more_infos.php?id_category="+int,true);
  xmlhttp.send();
}

//type Id
function getTypeId(int) {

  if (window.XMLHttpRequest) {
    // code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp=new XMLHttpRequest();
  } else {  // code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onreadystatechange=function() {
    if (this.readyState==4 && this.status==200) {
      document.getElementById("moreInfos").innerHTML=this.responseText;
	  
    }
  }  

 
	  
  
  xmlhttp.open("GET","./modules/nsfilter/more_infos.php?id_type="+int,true);
  xmlhttp.send();
}



//get manufacturer Id

function getManuId(int) {

  if (window.XMLHttpRequest) {
    // code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp=new XMLHttpRequest();
  } else {  // code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onreadystatechange=function() {
    if (this.readyState==4 && this.status==200) {
      document.getElementById("moreInfos").innerHTML=this.responseText;
	  
    }
  }  

 
	  
  
  xmlhttp.open("GET","./modules/nsfilter/more_infos.php?id_manufacturer="+int,true);
  xmlhttp.send();
}

//supplier



function getSupplierId(int) {

  if (window.XMLHttpRequest) {
    // code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp=new XMLHttpRequest();
  } else {  // code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onreadystatechange=function() {
    if (this.readyState==4 && this.status==200) {
      document.getElementById("moreInfos").innerHTML=this.responseText;
	  
    }
  }  

 
	  
  
  xmlhttp.open("GET","./modules/nsfilter/more_infos.php?id_supplier="+int,true);
  xmlhttp.send();
}

</script>
{/literal}	

<div class="row">
<div class="col-sm-4">

<form class="form-signin" action="{$smarty.server.SCRIPT_NAME|escape:'html':'UTF-8'}" method="get">
	<div class="form-group">
	<p class="btn btn-success btn-block" >
	   {l s='Choose a category to Start the Filter' mod='nsfilter'}
	</p>
	<span class="help-block"></span>
	</div>
	<div class="form-group">
	<select class="form-control" name="id_category" id="id_category" onchange="getCategoryId(this.value)">
    <option value="">Choose your category</option>
	{foreach from=$categories item=category}	
    <option value="{$nka_add_voucher_submit_link|escape:'html':'UTF-8'}&id_category={$category.id_category}">{$category.name}</option>                                  
    {/foreach}								  
    </select>
    <span class="help-block"></span>
	</div>
	<br>
	
</form>


<!--- filter by New/Best seller/ etc--->

<form class="form-signin" action="{$smarty.server.SCRIPT_NAME|escape:'html':'UTF-8'}" method="get">
	<div class="form-group">
	<p class="btn btn-success btn-block" >
	{l s='Filter by condition' mod='nsfilter'}
	</p>
	<span class="help-block"></span>
	</div>
	<div class="form-group">
	<select class="form-control" name="id_type" id="id_type" onchange="getTypeId(this.value)">
    <option value="">Choose your category</option>		
    <option value="{$nka_add_voucher_submit_link|escape:'html':'UTF-8'}&id_type=1">New Products</option> 
    <option value="{$nka_add_voucher_submit_link|escape:'html':'UTF-8'}&id_type=2">Best Sellers</option>    
    <option value="{$nka_add_voucher_submit_link|escape:'html':'UTF-8'}&id_type=3">Specials</option>    
    <option value="{$nka_add_voucher_submit_link|escape:'html':'UTF-8'}&id_type=4">In stock</option>    	
    							  
    </select>
    <span class="help-block"></span>
	</div>
	<br>
	
</form>

<!-- filter by Manufacturer-->

<form action="{$smarty.server.SCRIPT_NAME|escape:'html':'UTF-8'}" method="get">
     <div class="form-group">
	 <p class="btn btn-success btn-block" >
	   {l s='Filter by Manufacturer' mod='nsfilter'}
	</p>
		<span class="help-block"></span>
     </div>
	<div class="form-group">		
				<select class="form-control" id="id_manufacturer" name="id_manufacturer" onchange="getManuId(this.value)">
					<option value="0">{l s='All manufacturers' mod='blockmanufacturer'}</option>
				{foreach from=$manufacturers item=manufacturer}
					<option value="{$nka_add_voucher_submit_link|escape:'html':'UTF-8'}&id_manufacturer={$manufacturer.id_manufacturer|escape:'html':'UTF-8'}">{$manufacturer.name|escape:'html':'UTF-8'}</option>
				{/foreach}
				</select>
	</div>		
</form>


<!-- filter by supplier-->

<form action="{$smarty.server.SCRIPT_NAME|escape:'html':'UTF-8'}" method="get">
			
			<div class="form-group">
	 <p class="btn btn-success btn-block" >
	   {l s='Filter by Supplier' mod='nsfilter'}
	</p>
		<span class="help-block"></span>
     </div>
			
			<div class="form-group">
				<select class="form-control" id="supplier_list"  name="id_supplier" onchange="getSupplierId(this.value)">
					<option value="0">{l s='All suppliers' mod='blocksupplier'}</option>
				{foreach from=$suppliers item=supplier}
					<option value="{$nka_add_voucher_submit_link|escape:'html':'UTF-8'}&id_supplier={$manufacturer.id_supplier|escape:'html':'UTF-8'}">{$supplier.name|escape:'html':'UTF-8'}</option>
				{/foreach}
				</select>
			</div>
</form>
</div><!-- Left-->

<div class="col-sm-8">

<div id="moreInfos">
</div> 

</div><!--right-->
     
</div>	 


<div class="panel">
<a href="http://prestatuts.com/en/">
      <button class="btn btn-default">Download more free modules</button>
 </a>
</div>


{literal}

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
		<script src="./modules/nsfilter/js/jquery-1.10.2.min.js"></script>
		<!-- Include all compiled plugins (below), or include individual files as needed -->
		<script src="./modules/nsfilter/js/bootstrap.min.js"></script>
		<script src="./modules/nsfilter/js/jquery.validate.min.js"></script>

		<script>
		$('.cont').addClass('hide');
		count=$('.questions').length;
		 $('#question'+1).removeClass('hide');

		 $(document).on('click','.next',function(){
		     element=$(this).attr('id');
		     last = parseInt(element.substr(element.length - 1));
		     nex=last+1;
		     $('#question'+last).addClass('hide');

		     $('#question'+nex).removeClass('hide');
		 });

		 $(document).on('click','.previous',function(){
             element=$(this).attr('id');
             last = parseInt(element.substr(element.length - 1));
             pre=last-1;
             $('#question'+last).addClass('hide');

             $('#question'+pre).removeClass('hide');
         });

		</script>
		
		<script type="text/javascript">
		//$('#productscategory_list').trigger('goto', [{$middlePosition}-3]);
	</script>
	
<script>
function myFunction() {
    var x = document.getElementById('poll');
    if (x.style.display === 'none') {
        x.style.display = 'block';
    } else {
        x.style.display = 'none';
    }
}
</script>
{/literal}		

<div class="block-center" id="block-history">
<div id="block-order-detail" class="unvisible">&nbsp;</div>
</div>
<br/><br/>
<hr>
<div id="poll" style="display:none;"></div>






