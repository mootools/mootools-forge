<?php

class SearchForm extends BaseForm
{
	
	public function __construct($defaults = array(), $options = array(), $CSRFSecret = null){
		parent::__construct($defaults, $options, false);
	}
	
	public function configure(){
		$this->setWidgets(array(
			'q' => new sfWidgetFormInputText()
		));
		$this->setValidators(array(
			'q' => new sfValidatorString(array('max_length' => 100, 'required' => true))
		));
	}
	
}