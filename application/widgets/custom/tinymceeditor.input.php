<?php

	class TinyMCEEditorInput extends FormElement {

		static $instances_included = 0;
		
		public $trim = true;
		public $filter_html = true;

		private $config = array();
        
		function __construct($initial_value = '', $name = null){
			
			parent::__construct('textarea', $initial_value, $name, true);
			$this->setValidator(array());

			$this->config = array(
				'mode' => 'exact',
				'elements' => $this->getId(),
				'theme' => 'advanced',
				'theme_advanced_buttons1' => 'bold,italic,underline,strikethrough,separator,justifyleft,justifycenter,justifyright,justifyfull,separator,link,unlink,image,markettoimages,media,separator,bullist,formatselect,forecolor,backcolor,emotions,code',
				'theme_advanced_buttons2' => '',
				'theme_advanced_buttons3' => '',
				'relative_urls' => false,
				'height' => '250',
				'verify_html' => true,
				'cleanup' => true,
				'document_base_url' => UrlHelper::get('/'),
				'plugins' => 'advimage,media,emotions,paste',
				'content_css' => UrlHelper::resource('/phaxsi/extensions/tinycss.css'),
				'theme_advanced_statusbar_location' => 'bottom',
				'theme_advanced_resizing' => true,
				'theme_advanced_resize_horizontal' => false,
				'theme_advanced_toolbar_location' => 'top'
			);
		}
		
		function setValue($value){
			$this->_value = $value;
		}
		
		function getValue($filtered = true){
			if($this->trim){
				$this->_value = trim($this->_value);
			}
			return parent::getValue($filtered);
		}
		
		function setValidator($options, $messages = array()){
			$options = array_merge($options, array('client_side_validable' => false));
			if(!$this->_validator){
				$this->_validator = new HtmlValidator($options, $messages);
				if($this->_error_code){
					$this->_validator->setErrorCode($this->_error_code);
				}
			}
			else{
				parent::setValidator($options, $messages);
			}
		}
		
		function validate(){
			if($this->filter_html){
				$this->_value = $this->_validator->filterHtml($this->_value);
			}			
			return parent::validate();
		}

		function setConfigOption($key, $value){
			$this->config[$key] = $value;
		}

		function getConfigOption($key){
			return $this->config[$key];
		}

		function setConfig($options){
			$this->config = array_merge($this->config, $options);
		}
		
		function __toString(){
			
			$this->setAttribute('class', 'tinymce_textarea');
			$this->innerHTML = HtmlHelper::escape($this->_value);

			$config = JsonHelper::encode($this->config);
			$script = HtmlHelper::inlineJavascript(
				'tinyMCE.init('.$config.');'
			);

			$this->afterHTML = $script;

			self::$instances_included++;
			
			return parent::__toString();
		}
		
				
	}

?>
