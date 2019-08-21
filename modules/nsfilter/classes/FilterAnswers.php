<?php



class FilterAnswers extends ObjectModel
{
	/** @var string Name */
	public $id_answer;
	/** @var string Name */
	public $question_id;	
	
	/** @var string Name */
	public $answer_point;
	
	/** @var string Name */
	public $answer_name;
	
    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'nsfilter_answers',
        'primary' => 'id_answer',
        'multilang' => FALSE,
        'fields' => array(            		
            'answer_name'=>array('type' => self::TYPE_STRING, 'validate' => 'isCatalogName', 'required' => true, 'size' => 228),	
            'question_id' => array('type' => self::TYPE_INT, 'validate' => 'isInt', 'required' => TRUE), 
            'answer_point' => array('type' => self::TYPE_INT, 'validate' => 'isInt', 'required' => TRUE),			
			            	
        ),
    );    
	
	
		//get quote details
	
	public static function loadByIdQuestion($question_id){
	
	$sql = 'SELECT  * FROM `'._DB_PREFIX_.'nsfilter_answers` q    
	        WHERE  q.`question_id`='.$question_id;	
    $result= Db::getInstance()->getRow($sql);	
	
	return new FilterAnswers($result['id_answer']);
	
	
	}
	
	//verify duplicated cards number
	
	public static function verifyByName($query)
	{
		return Db::getInstance()->getRow('
			SELECT iv.`id_answer`
			FROM `'._DB_PREFIX_.'nsfilter_answers` iv			
			WHERE iv.`answer_name` LIKE \''.pSQL($query).'\'
		');
	}
	
	
	// get all answers
	
	
    public static function getAnswersByQuestion($id_question)
	{
	    
	    //$id_lang = (int)Context::getContext()->language->id; 
		$sql = 'SELECT  * FROM `'._DB_PREFIX_.'nsfilter_answers` a   
	        WHERE  a.`question_id`='.$id_question.' ORDER BY a.`id_answer`  ASC';	
    $results = Db::getInstance()->ExecuteS($sql);
		
		

		return $results;
		
	}	
	
	

	    public static function getFeatureProducts($id_feature_value){	
		$id_lang = (int)Context::getContext()->language->id;	
		
		$results =Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS(' 
		SELECT * FROM '._DB_PREFIX_.'feature_product pf	                       
		INNER JOIN `'._DB_PREFIX_.'feature_value` f ON (f.`id_feature_value` = pf.`id_feature_value`)							
		INNER JOIN  `'._DB_PREFIX_.'product` p ON p.`id_product`=pf.`id_product`	
		INNER JOIN  `'._DB_PREFIX_.'product_lang` pl ON pl.`id_product`=p.`id_product`	   
		LEFT JOIN `'._DB_PREFIX_.'image` i	ON (i.`id_product` = pl.`id_product`)						                       
		WHERE pf.id_feature_value = '.(int)$id_feature_value.'		
		AND  pl.id_lang='.$id_lang                            
		);		
		return $results;				
		} 
	
	
	
}

