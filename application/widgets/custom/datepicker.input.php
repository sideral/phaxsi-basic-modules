<?php

class DatePickerInput extends InputText{

	protected $config = array(
		'dateFormat'=>"dd/mm/yy",
		'changeMonth'=>false
	);

	function __construct($value = '', $name = null){
		parent::__construct($value, $name);
		$this->setValidator(array('expression' => '/^(?:[0][1-9]|[12][0-9]|[3][0-1])\/(?:[0][1-9]|[1][012])\/(?:19|20)[0-9]{2}$/'));
	}

	function setOption($key, $value){
		$this->config[$key] = $value;
		return $this;
	}

	function __toString(){
		$json_config = JsonHelper::encode($this->config);
		$this->afterHTML = HtmlHelper::inlineJavascript('jQuery(function(){$("#'.$this->getId().'").datepicker('.$json_config.')});');
		$this->setAttribute('class', 'form_input_text form_ycalendar');
		return parent::__toString();
	}

	function getRawValue(){
		//MySQL date
		if(strpos($this->_value, "-") !== false){
			return implode('/',array_reverse(explode('-',$this->_value)));
		}

		return $this->_value;

	}

	function getValue($filtered = true){
		return implode('-',array_reverse(explode('/', parent::getValue($filtered))));
	}

}

