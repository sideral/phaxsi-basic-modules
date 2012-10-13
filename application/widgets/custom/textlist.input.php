<?php

class TextListInput extends FormElementList{

	protected $max_inputs = 0;
	protected $min_inputs = 0;
	protected $link_text = 'Add more';
	protected $on_insert = '';
	protected $ignore_empty = true;
	protected $label = null;
	
	function getLabel($label_text = '', $attributes = array()){
		if(!$this->label || $label_text){
			$this->setLabel($label_text);
		}

		if($this->label){
			$this->label->setAttributes($attributes);
		}

		return $this->label;
	}
	
	function setLabel($label_text, $attributes = array()){
		$label = new HtmlElement('label', null, true);
		$label->setAttribute('class', 'phaxsi_label');
		$label->setAttributes($attributes);
		$label->innerHTML = $label_text;
		$this->label = $label;
	}
	
	function setRawValue($values){
		if(is_array($values)){
			if($this->max_inputs){
				$values = array_slice($values, 0, $this->max_inputs);
			}
			foreach($values as $value){
				if($this->ignore_empty && empty($value)){
					continue;
				}
				$this->add($value);
			}
			$this->_raw_value = $values;
		}
	}

	function setMaxInputs($qty){
		if($qty <= 0){
			$qty = 0;
		}
		$this->max_inputs = (int)$qty;
	}


	function setMinInputs($qty){
		if($qty <= 0){
			$qty = 0;
		}
		$this->min_inputs = (int)$qty;
	}

	function setLinkText($text){
		$this->link_text = (string)$text;
	}

	function setOnInsert($insert){
		$this->on_insert = $insert;
	}

	function ignoreEmpty($yes_or_no){
		$this->ignore_empty = $yes_or_no;
	}

	function add($value = '', $label = ''){
		$input = $this->createAndAdd('text', $value, $label);
	}

	function __toString(){

		if($this->countElements() < $this->min_inputs){
			for($i=0; $i< $this->min_inputs - $this->countElements(); $i++){
				$this->add();
			}
		}

		$html = new HtmlHelper();		
		$output = $html->javascript('/widgets/custom/textlist.js');
		$output .= $html->css('/widgets/custom/textlist.css');
		$output .= '<div class="phaxsi-textlist" data-max="'.$this->max_inputs.'" data-callback="'.$this->on_insert.'" data-name="'.$this->_name.'">';
		
		$output .= '<div>'. $this->toString() .'</div>';
		
		$output .= ($this->max_inputs == 0 || count($this->_elements) < $this->max_inputs) ?  $html->absoluteLink($this->link_text,'javascript:void(0)', ''):'';
		$output .= '</div>';
		
		return $output;
		
	}
	
	function toString($vertical = true){
		
		$html = '';

		foreach($this->_elements as $element){
			$html .= $element->__toString() .  "<br/>\r\n";
		}

		$html .= $this->getClientValidationHtml();

		return $html;

	}


}
