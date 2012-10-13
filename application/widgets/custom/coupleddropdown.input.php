<?php

require_once(PHAXSID_FORMCOMPONENTS.DS.'inputdropdown.class.php');

class CoupledDropDownInput extends InputDropDown{

	protected $filter_column;
	protected $related;
	protected $empty_value;

	function coupleWith(FormElement $component, $url, $filter_column = null, $empty_value = ''){
		$this->setAttribute('rel', $component->getId());
		$this->setAttribute('data-url', UrlHelper::get($url));
		$this->filter_column = $filter_column;
		$this->related = $component;
		$this->empty_value = $empty_value;
	}

	function __toString(){
		
		$load = new Loader();
		$html = $load->helper('html');

		$this->beforeHTML = $html->javascript('/widgets/custom/coupleddropdown.js');

		$this->beforeHTML .= $html->inlineJavascript('var coupled_empty_'.$this->getId().' = "'.$this->empty_value.'";');

		$this->afterHTML = $html->img('/widgets/custom/ajax-loader.gif', 'Loading...', 
								     array('style' => 'display:none'));

		$this->setClass('coupleddropdown');

		if($this->filter_column && $this->_data_source instanceof TableReader){
			$this->_data_source->where($this->filter_column, $this->related->getValue());
		}

		if($this->empty_value){
			$this->add('0', $this->empty_value);
		}

		return parent::__toString();

	}


}