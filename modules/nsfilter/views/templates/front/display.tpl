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
function getInfos(int) {

  if (window.XMLHttpRequest) {
    // code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp=new XMLHttpRequest();
  } else {  // code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onreadystatechange=function() {
    if (this.readyState==4 && this.status==200) {
      document.getElementById("moreInfos").innerHTML=this.responseText;
	  var modal = document.getElementById('myModal');
	  // Get the <span> element that closes the modal
     var span = document.getElementsByClassName("close")[0];
	  modal.style.display = "block";  
	  
	  // When the user clicks on <span> (x), close the modal
span.onclick = function() {
    modal.style.display = "none";
}	  
    }
  }  

 
	  
  
  xmlhttp.open("GET","./modules/nsfilter/more_infos.php?id_question="+int,true);
  xmlhttp.send();
}

</script>
{/literal}	


{if $logged}

{if Tools::getValue('category')==''}
<form class="form-signin" action="{$smarty.server.SCRIPT_NAME|escape:'html':'UTF-8'}" method="get">
	<div class="form-group">
	<input type="hidden"  name='id_customer' value="{$id_customer}" class="form-control" />
	<span class="help-block"></span>
	</div>
	<div class="form-group">
	<select class="form-control" name="category" id="manufacturer_list" onclick="autoUrl('manufacturer_list', '');">
    <option value="">Choose your category</option>
	{foreach from=$categories item=category}	
    <option value="{$nka_add_voucher_submit_link|escape:'html':'UTF-8'}&category={$category.id}">{$category.category_name}</option>                                  
    {/foreach}								  
    </select>
    <span class="help-block"></span>
	</div>
	<br>
	<p class="btn btn-success btn-block" >
	   {l s='Choose a category to Start the Quiz' mod='nsfilter'}
	</p>
</form>
{else}
                <h2>
					{l s='Quiz questions:' mod='nsfilter'}
				</h2>
				<hr>
<div id="moreInfos">
</div>
		<div class="container question">
			<div class="col-xs-12 col-sm-8 col-md-8 col-xs-offset-4 col-sm-offset-3 col-md-offset-3">
			
<form class="form-horizontal"  action="{$result_submit_link|escape:'html':'UTF-8'}"   method="post" enctype="multipart/form-data">
		
{*include file="./feature_products.tpl"*}


			{assign var=i value=1}	
              {foreach from=$questions item=result}
			  {assign var=answers value=QuizAnswers::getAnswersByQuestion($result.id_question)}
                    {if isset($i)  && $i==1}         
                    <div id="question{$i}" class='cont'>
    <button  onclick="getInfos(this.value)" value="{$result.id_question}" class='more_infos' type='button' >
	{l s='More information about this question' mod='nsfilter'}
	</button>
                    <p class='questions' id="qname{$i}">{$i}.{$result.question_name}</p>
					{foreach from=$answers item=answer}
                    <input type="radio"  onclick="getVote(this.value)"  value="{$answer.answer_point}" id="radio1_{$answer.answer_point}" name='answer_point'   {if (isset($smarty.post.answer_point)==$answer.answer_point)} checked="checked"{/if}/>{$answer.answer_name}
                   <br/>				   
                    {/foreach}
                    <button id="next{$i}" class='next btn btn-next' type='button'>{l s='Next' mod='nsfilter'}</button>
                    </div>  
                     
					 {elseif $i<1  || $i<count($questions)}

                       <div id="question{$i}" class='cont'>
	<button  onclick="getInfos(this.value)" value="{$result.id_question}" class='more_infos' type='button' >
	{l s='More information about this question' mod='nsfilter'}
	</button>
                    <p class='questions' id="qname{$i}">{$i}.{$result.question_name}</p>
                    {foreach from=$answers item=answer}
                      <input type="radio"  onclick="getVote(this.value)"  value="{$answer.answer_point}" id="radio1_{$answer.answer_point}" name='answer_point'   {if (isset($smarty.post.answer_point)==$answer.answer_point)} checked="checked"{/if}/>{$answer.answer_name}
                   <br/>
				   {assign var=q value=$answer.answer_point}	
                    {/foreach}
                    
                    <button id="pre{$i}" class='previous btn btn-previous' type='button'>{l s='Previous' mod='nsfilter'}</button>  
                <!--<a class="previous btn btn-success" href="javascript:showOrder(1,{$q}, 'index.php?fc=module&module=nsfilter&controller=products', true);" style="color:white;" >-->  
                   <a class="previous btn show_results" href="#poll" onclick="myFunction()" >
				   {l s='Show results' mod='nsfilter'}
				   </a>	

                    <button id="next{$i}" class='next btn btn-next' type='button' >{l s='Next' mod='nsfilter'}</button>
                    </div>

                   {elseif $i==count($questions)}
                    <div id="question{$i}" class='cont'>
	<button  onclick="getInfos(this.value)" value="{$result.id_question}" class='more_infos' type='button' >
	{l s='More information about this question' mod='nsfilter'}
	</button>
                    <p class='questions' id="qname{$i}">{$i}.{$result.question_name}</p>
                    {foreach from=$answers item=answer}
                    <input type="radio"  onclick="getVote(this.value)" value="{$answer.answer_point}" id="radio1_{$answer.answer_point}" name='answer_point'   {if (isset($smarty.post.answer_point)==$answer.answer_point)} checked="checked"{/if}/>{$answer.answer_name}
                   <br/>
				   {assign var="finish"  value={$answer.answer_point}}
                    {/foreach}   

                    <button id="pre{$i}" class='previous btn btn-previous' type='button'>{l s='Previous' mod='nsfilter'}</button>               
                   <input type="hidden"	name="id_fvalue"	 value="{$finish}" >                					
                    <button id="next{$i}" class='next btn btn-finish' type='submit'>{l s='Finish' mod='nsfilter'}</button>
                    </div>
					{/if} 
					
				<p style="display:none;">{$i++} </p>
					{/foreach}
                 
				</form>
			</div>
		</div>

          
{/if}


{else}
</div>

			{l s='You should login before answering any question !' mod='nsfilter'} <br/><a style="color:red;" href="{$link->getPageLink('my-account', true)|escape:'html'}" title="{l s='Log in to your customer account' mod='innovavoucher'}" class="login" rel="nofollow">{l s='Login' mod='innovavoucher'}</a>
{/if}

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






