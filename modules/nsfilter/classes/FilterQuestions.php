<?php



class FilterQuestions extends ObjectModel
{
	/** @var string Name */
	public $id_question;
	/** @var string Name */
	public $category_id;
	
	/** @var string Name */
	public $question_name;
	
	public $more_infos;
	
    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'nsfilter_questions',
        'primary' => 'id_question',
        'multilang' => FALSE,
        'fields' => array(            		
            'question_name'=>array('type' => self::TYPE_STRING, 'validate' => 'isCatalogName', 'required' => true, 'size' => 228),	
            'category_id' => array('type' => self::TYPE_INT, 'validate' => 'isInt', 'required' => TRUE), 
            'more_infos' => array('type' => self::TYPE_HTML, 'validate' => 'isString'),			
			            	
        ),
    );    
	
	
		//get quote details
	
	public static function loadByIdCategory($category_id){
	
	$sql = 'SELECT  * FROM `'._DB_PREFIX_.'nsfilter_questions` q    
	        WHERE  q.`category_id`='.$category_id;	
    $result= Db::getInstance()->getRow($sql);	
	
	return new FilterQuestions($result['id_question']);
	
	
	}
	
	public function getAnswers($id_question)
	{
	    
	    //$id_lang = (int)Context::getContext()->language->id; 
		$sql = 'SELECT  * FROM `'._DB_PREFIX_.'nsfilter_answers` a   
	        WHERE  a.`question_id`='.$id_question.' ORDER BY a.`id_answer`  ASC';	
    $results = Db::getInstance()->ExecuteS($sql);
		
		

		return $results;
		
	}	
	
	
	
	
	
	//verify duplicated cards number
	
	public static function verifyByName($query)
	{
		return Db::getInstance()->getRow('
			SELECT iv.`id_question`
			FROM `'._DB_PREFIX_.'nsfilter_questions` iv			
			WHERE iv.`question_name` LIKE \''.pSQL($query).'\'
		');
	}

	
	

	
	
	
	
}

