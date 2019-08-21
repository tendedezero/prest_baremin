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
        xmlhttp.open("GET","./../modules/simplequiz/getproducts.php?q="+str,true);
        xmlhttp.send();
    }
}
</script>
{/literal}

<!----show-->
<form action="#" id="af_form">		
<div class="apage_filter clearfix" data-trigger="t" data-url="localita">
<select id="selector-t" class="af-select form-control" name="id_fvalue"  onchange="showUser(this.value);" style="width: 150px;">
<option value="0"  >{l s='Select' mod='simplequiz'}</option>
<option value="21" >ok</option>	
<option value="11" >ok 2</option>																																																																																																																																																																																										
</select>	
</div>						
</form>
<!--results-->
<div id="txtHint">
<p class="alert alert-warning">Select a Location to display its products here.</p>
	
</div>







