<?php
/**
 * CTimestampBehavior class file.
 *
 * @author Jonah Turnquist <poppitypop@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2009 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */
 
 /**
 * documentation here todo
 * @author Jonah Turnquist <poppitypop@gmail.com>
 * @version $Id$
 * @package zii.behaviors
 * @since 1.1
 */

class CTimestampBehavior extends CActiveRecordBehavior {
	/**
	* @var mixed The name of the attribute to store the creation time.  Set to null to not
	* use a timstamp for the creation attribute.
	*/
	public $createAttribute = null;
	/**
	* @var mixed The name of the attribute to store the modification time  Set to null to not
	* use a timstamp for the update attribute.
	*/
	public $updateAttribute = null;
	
	/**
	* @var bool whether to set the update attribute to the creation timestamp upon creation.
	* Otherwise it will be left alone.  Defaults to false.
	*/
	public $setUpdateOnCreate = false;
	
	/**
	* @var mixed The expression to use to generate the timestamp.  e.g. 'time()'.
	* Defaults to null meaning that we will attempt to figure out the appropriate timestamp
	* automatically.  If we fail at finding the appropriate timestamp, then it will
	* fall back to using the current UNIX timestamp
	*/
	public $timestampExpression=null;
	
	/**
	* Responds to {@link CModel::onBeforeValidate} event.
	* Sets the values of the creation or modified attributes as configured
	* 
	* @param CModelEvent event parameter
	*/
	public function beforeValidate($event) {
		if ($this->getOwner()->getIsNewRecord() && ($this->createAttribute !== null)) {
			$this->getOwner()->{$this->createAttribute} = $this->getTimestampByAttribute($this->createAttribute);
		}
		if ((!$this->getOwner()->getIsNewRecord() || $this->setUpdateOnCreate) && ($this->updateAttribute !== null)) {
			$this->getOwner()->{$this->updateAttribute} = $this->getTimestampByAttribute($this->updateAttribute);
		}
			
		return true;	
	}
	
	/**
	* Gets the approprate timestamp depending on the column type $attribute is
	* 
	* @param string $attribute
	* @return mixed timestamp (eg unix timestamp or a mysql function)
	*/
	protected function getTimestampByAttribute($attribute) {
		if ($this->timestampExpression !== null)
			return @eval('return '.$this->timestampExpression.';');
			
		$columnType = $this->getOwner()->getTableSchema()->getColumn($attribute)->dbType;
		return $this->getTimestampByColumnType($columnType);
	}
	
	/**
	* Returns the approprate timestamp depending on $columnType
	* 
	* @param string $columnType
	* @return mixed timestamp (eg unix timestamp or a mysql function)
	*/
	protected function getTimestampByColumnType($columnType) {
		$map = array(
			'datetime'=>'NOW()',
			'timestamp'=>'NOW()',
			'date'=>'NOW()',
		);
		return isset($map[$columnType]) ? new CDbExpression($map[$columnType]) : time();
	}
}