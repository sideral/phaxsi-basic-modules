<?php

class EditableImageInput extends InputFileImage{

	protected $current_filename = "";
	protected $base_path = '';
	protected $input_hidden = '';

	function __construct($value = "", $name = null){
		parent::__construct($value, $name);
		$input_name = Loader::formComponent('hidden');
		$this->input_hidden = new $input_name($value);
		$this->setValidator(array());
	}

	function setValidator($options, $messages = array()){
		$options = array_merge($options, array('client_side_validable' => false));
		parent::setValidator($options, $messages);
	}

	function setName($name, $html_name = null){
		parent::setName($name, $html_name);
		$this->input_hidden->setName($name, $name .'[current]');
	}

	function setRawValue($value){

		if($value){
			if(!is_array($value)){
				$this->current_filename = $value;
				$this->input_hidden->setRawValue($value);
			}
			else if(isset($value['current'])){
				$this->current_filename = $value['current'];
			}
		}

		if(isset($_FILES[$this->_name])){
			$values = array();
			foreach($_FILES[$this->_name] as $key => $val){
				if(!is_array($val)|| !isset($val['file'])){
					$values = array();
					break;
				}
				$values[$key] = $val['file'];
			}
			$this->_value = $values;
		}
		else{
			$this->_value = array('name' => '', 'type' => '',
								  'tmp_name' => '', 'error' => UPLOAD_ERR_NO_FILE,
								  'size' => 0);
		}

	}

	function validate(){

		if($this->hasFile() || $this->current_filename == ''){
			return parent::validate();
		}

		return true;

	}

	function saveFileAs($target, $chmod = 0644, &$substitutions = array()){

		if($this->hasFile() || $this->current_filename == ''){
			return parent::saveFileAs($target, $chmod, $substitutions);
		}

		//??

	}

	function __toString(){

		if(!$this->current_filename){
			return $this->toString();
		}

		$id = $this->getAttribute('id');

		return '<div id="editablebox_'.$id.'">'.
				HtmlHelper::absoluteImg($this->base_path . '/' .$this->current_filename).
				$this->toString().'</div>';

	}

	private function toString(){

		$this->_html_name = $this->_name.'[file]';

		if($this->current_filename == ''){
			return parent::__toString();
		}

		$html = new HtmlHelper();

		$id = $this->getAttribute('id');
		$button = '<div>'.$this->current_filename.' ';
		$button .= $html->absoluteLink('[X]', 'javascript:void(0)', array('id' => 'delete_'.$id));
		$button .= $html->inlineJavascript('Phaxsi.Event.addEvent(document.getElementById("delete_'.$id.'"), "click", deleteFile.createDelegate(this, ["'.$id.'", "'.$this->input_hidden->getAttribute('id').'"]));');
		$button .= '</div>';
		
		$this->beforeHTML = $html->javascript('/widgets/custom/editablefile.js')
							 .$button. $this->input_hidden->__toString();

		return parent::__toString();

	}

	function getValue($filtered = true){

		$value = parent::getValue($filtered);

		if(!$value){
			return $this->current_filename;
		}

		return $value;

	}

	function setBasePath($path){
		$this->base_path = $path;
	}

}
