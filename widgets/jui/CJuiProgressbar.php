<?php
/**
 * CJuiProgressbarWidget class file.
 *
 * @author Sebastian Thierer <sebas@artfos.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2009 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

 /**
 * This is the base class for
 * @author Sebastian Thierer <sebathi@gmail.com>
 * @version $Id: CJuiAccordionWidget.php 16 2009-09-24 12:43:38Z sebathi $
 * @package zii.widgets.jui
 * @since 1.1
 */

Yii::import('zii.widgets.jui.CJuiWidget');

class CJuiProgressbar extends CJuiWidget {
	
	/**
	 * The value of the progressbar.
	 * @var boolean
	 */
	public $value = false;
	
	/**
	 * This event is triggered when the value of the progressbar changes.
	 * @var string
	 */
	public $change = null;

	/**
	 * Sets the progressBar height
	 * @var unknown_type
	 */
	public $height = '20px';
	/**
	 * Gets the JavaScript to be called at page start
	 * @return string 
	 */
	private function getJSCommand($id){
		$params = array();
		if ($this->value!==null)
			$params['value'] = $this->value;
		if ($this->change!==null)
			$params['change'] = $this->change;

		return "$('#$id').progressbar(".CJavaScript::encode($params).");";
	}
	/**
	 * Generates the HTML to display this widget
	 * @param $id
	 * @return unknown_type
	 */
	private function generateHtml($id){
		if (!is_numeric($this->value)){
			throw new CException(Yii::t('zii','{attribute} must be a number.', array('{attribute}'=>'value')));
		}
		$html = '<div id="'.$id.'"' . ($this->height!==null?' style="height:' . $this->height.';"':'').'></div>';
		return $html;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see web/widgets/CWidget#run()
	 */
	public function run(){
		$id = $this->getId();

		echo $this->generateHtml($id);
		
		Yii::app()->getClientScript()->registerScript($id.'_js', $this->getJSCommand($id));
		
	}
	
}