<?php
/**
* Validate input data
*/
class Validate
{
	private $_passed = false,
			$_errors = array(),
			$_db = null;
	
	public function __construct()
	{
		$this->_db = PIModel::getInstance();
	}

	public function check($source="", $items = array())
	{
		foreach ($items as $item => $rules) {
			foreach ($rules as $rule => $rule_value) {

				$itemStack = explode("|", $item);
				$value = ($source === "") ? escape($itemStack[0]) : trim($source[$itemStack[0]]);
				$field = (isset($itemStack[1])) ? escape($itemStack[1]) : trim($source[$itemStack[0]]);
				$tableColumn = (isset($itemStack[2])) ? escape($itemStack[2]) : "";
				//$value = $value = ($source === "") ? trim($item) : trim($source[$item]);
				//$item = escape($item);

				if ($rule === 'required' && empty($value) || $rule === 'required' && $value == "") {
					$this->addError("{$field} is required");
				} else if (!empty($value)) {
					switch ($rule) {
						case 'min':
							if (strlen($value) < $rule_value) {
								$this->addError("{$field} must be a minimum of {$rule_value} characters.");
							}
							break;

						case 'max':
							if (strlen($value) > $rule_value) {
								$this->addError("{$field} must be a maximum of {$rule_value} characters.");
							}
							break;

						case 'minValue':
							if ($value < $rule_value) {
								$this->addError("{$field} must be a minimum of {$rule_value}.");
							}
							break;

						case 'maxValue':
							if ($value > $rule_value) {
								$this->addError("{$field} must be a maximum of {$rule_value}.");
							}
							break;

						case 'numeric':
							if ( !is_numeric($value) ) {
								$this->addError("{$field} must be numeric.");
							}
							break;

						case 'matches':
							if($source === "") {
								if ($value != $rule_value)
								{
									$this->addError("{$field} Mis-match");
								}
							}
							else
							{
								if ($value != $source[$rule_value]) {
									$this->addError("{$field} Mis-match");
								}
							}
							break;

						case 'unique':
							$check = $this->_db->get($rule_value,  array($tableColumn.' = '), array($value));
							if($check->count()) {
								$this->addError("{$value} already exist");
							}
							break;

						case 'email':
							if ( ! filter_var($value, FILTER_VALIDATE_EMAIL))
							{
								$this->addError("{$value} in not a valid E-mail");
							}
							break;

						case 'alpnum':
							if (  preg_match('#[^0-9A-Za-z_]#i', $value) )
							{
								$this->addError("{$value} must be a one word alphanumeric character.");
							}
							break;
					}
				}
			}
		}

		if (empty($this->_errors)) {
			$this->_passed = true;
		}

		return $this;
	}

	private function addError($error)
	{
		$this->_errors[] = $error;
	}

	public function errors()
	{
		return $this->_errors;
	}

	public function passed()
	{
		return $this->_passed;
	}

	public function test() {
		return $this->test;
	}
}