<?php
/**
 * CController class file.
 *
 * @author Jonah Turnquist <poppitypop@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2009 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */
 
 /**
 * AutoTimestampBehavior is a work in the makings :)
 * 
 * Localization?
 * 
 * Supports multiple column types (datetime, date, timestamp, int).
 */

class AutoTimestampBehavior extends CActiveRecordBehavior {
	/**
	* The field that stores the creation time
	*/
	public $createdField = 'created';
	/**
	* The field that stores the modification time
	*/
	public $modifiedField = 'modified';
	
	/**
	* Potentially sets the values of the creation and/or modified database fields as configured
	* 
	* @param mixed $on
	*/
	public function beforeValidate($on) {
		if ($this->Owner->getIsNewRecord()) {
			if ($this->createdField) {
				$this->Owner->{$this->createdField} = $this->getDateForField($this->createdField);
			}
		} else {
			if ($this->modifiedField) {
				$this->Owner->{$this->modifiedField} = $this->getDateForField($this->modifiedField);
			}
		}
			
		return true;	
	}
	
	/**
	* Gets the approprate date depending on the column type $field is
	* 
	* @param string $field
	* @return mixed date (eg unix timestamp or a mysql function)
	*/
	protected function getDateForField($field) {
		$columnType = $this->Owner->getTableSchema()->getColumn($field)->dbType;
		$method = $this->column2method($columnType);
		return $this->$method();
	}
	
	/**
	* Returns the internal method to use to generate the date depending on the type
	* of the column that we are generating a date for
	* 
	* @param string $columnName
	* @return mixed method name
	*/
	protected function column2method($columnType) {
		$map = array(
			'datetime'=>'getDate_Datetime',
			'timestamp'=>'getDate_Datetime',
			'date'=>'getDate_Datetime',
			'int'=>'getDate_unix',
			'year'=>'getDate_year',
		);
		return isset($map[$columnType]) ? $map[$columnType] : 'getDate_unix';
	}
	
	
	protected function getDate_Datetime() {
		return new CDbExpression('NOW()');
	}
	protected function getDate_unix() {
		return new CDbExpression('UNIX_TIMESTAMP()');
	}
	protected function getDate_year() {
		return new CDbExpression('YEAR(NOW())');
	}
}