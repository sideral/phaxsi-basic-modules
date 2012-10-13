<?php

class DateTimePickerInput extends FormInput{
	
	protected $minute_interval = 15;
	
	/**
	 *
	 * @var DatePickerInput 
	 */
	protected $date_picker = null;
	/**
	 *
	 * @var InputDropDown 
	 */
	protected $dropdown = null;
	
	function __construct($value = '', $name = null){

		$load = new Loader();
		$this->date_picker = $load->customFormComponent('/widgets/datepicker');
		$this->dropdown = Loader::formComponent('dropdown');
		
		parent::__construct('hidden', $value, $name);

	}
	
	function setMinuteInterval($interval){
		$this->minute_interval = (int)$interval;
	}
	
	function setName($name, $html_name = null){
		$this->date_picker->setName($name,$name.'[date]');
		$this->dropdown->setName($name,$name.'[time]');
	}
	
	function setRawValue($value){
		$this->date_picker->setRawValue($value['date']);
		$this->dropdown->setRawValue($value['time']);
	}
	
	function setValue($value){
		$parts = explode(' ', $value);
		$time = substr($parts[1], 0, strrpos($parts[1], ':'));
		$this->date_picker->setValue($parts[0]);
		$this->dropdown->setValue($time);
	}
	
	function getRawValue(){
		$date_val = $this->date_picker->getRawValue();
		$hour_val = $this->dropdown->getRawValue();
		return array('date' => $date_val, 'time' =>  $hour_val);
	}
	
	function getValue($filtered = true){
		$date_val = $this->date_picker->getValue($filtered);
		$hour_val = $this->dropdown->getValue($filtered);
		return $date_val .' '.$hour_val.':00';
	}
	
	function __toString(){
		
		$minutes = array();
		$minute = 0;
		while($minute < 60){
			$minutes[] = $minute;
			$minute += $this->minute_interval;
		}
		
		$hours = array();
		foreach(range(0, 23) as $hour){
			foreach($minutes as $minute){
				$hours[str_pad($hour, 2, '0', STR_PAD_LEFT).':'.str_pad($minute, 2, '0', STR_PAD_LEFT)] = $hour.':'.str_pad($minute, 2, '0', STR_PAD_LEFT);
			}
		}
		
		$this->dropdown->setDataSource($hours);
		
		$value = $this->dropdown->getValue();

		if($value){
			$parts = explode(':', $value);
			$min_distance = 60;
			$approx_min = 0;
			foreach($minutes as $minute){
				if($min_distance > abs($parts[1] - $minute)){
					$min_distance = abs($parts[1] - $minute);
					$approx_min = $minute;
				}
			}
			
			if($min_distance > 60 - $parts[1]){
				$approx_min = 0;
				$parts[0] = str_pad(($parts[0]+1)%24, 2, '0', STR_PAD_LEFT);
			}
			
			$this->dropdown->setValue($parts[0].':'.str_pad($approx_min, 2, '0', STR_PAD_LEFT));
		}
		
		return '<div class="form_input_datetime">'.$this->date_picker->__toString().$this->dropdown->__toString().'</div>';
	}

	function validate(){
		return $this->date_picker->validate() && $this->dropdown->validate();
	}
	
	function setErrorCode($code){
		return $this->date_picker->setErrorCode($code);		
	}
	
	function getErrorMessage(){
		return $this->date_picker->getErrorMessage();
	}
	
	function getErrorCode(){
		return $this->date_picker->getErrorCode();		
	}
	
}