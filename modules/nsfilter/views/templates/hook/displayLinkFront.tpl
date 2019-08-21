<!DOCTYPE html>
<html lang="en">
<head>
  <title>{*$intro_text*}</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
{if $logged}
    {*if $voucher->id_invoucher==''*}
       <div class="alert alert-info">
         <a  href="{$my_voucher_list_link}" style="margin-left:35%;">PLEASE ANSWER THESE QUESTIONS{*$intro_text|upper*}</a>
       </div>
    {*/if*}
{/if}

<h1><a href="{$search_link}">Advanced Search</a></h1>

</body>
</html>


