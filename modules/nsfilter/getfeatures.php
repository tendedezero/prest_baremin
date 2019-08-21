<?php
include_once('./../../config/config.inc.php');
include_once('./../../init.php');

$id_lang=(int)Context::getContext()->language->id;

$id_feature=intval($_GET['id_feature']);

$categories=FeatureValue::getFeatureValuesWithLang($id_lang, $id_feature, false);

?>

<div class="form-group">
	<select class="form-control" name="answer_point" >
    <option value="">Choose a feature category </option>
	<?php  foreach($categories as $category){	?>
    <option value="<?php echo $category['id_feature_value'] ;?>"><?php echo $category['value'] ;?></option>                                  
    <?php  }?> 							  
    </select>
    <span class="help-block"></span>
</div>
