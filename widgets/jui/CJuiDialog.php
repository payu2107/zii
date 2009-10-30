<?php
/**
 * CJuiDialog class file.
 *
 * @author Sebastian Thierer <sebas@artfos.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2009 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

Yii::import('zii.widgets.jui.CJuiWidget');

/**
 * CJuiDialog displays a dialog widget.
 *
 * CJuiDialog encapsulates the {@link http://jqueryui.com/demos/dialog/ JUI Dialog}
 * plugin.
 *
 * To use this widget, you may insert the following code in a view:
 * <pre>
 * $widget = $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
 *     // additional javascript options for the accordion plugin
 *     'options'=>array(
 *         'title'=>'Dialog box 1',
 *     ),
 * ));
 *     echo 'Your content here';
 * $this->endWidget('zii.widgets.jui.CJuiDialog');
 * 
 * echo CHtml::link('open link', '#', array('onclick'=>$widget->getOpenScript());
 * </pre>
 *
 * By configuring the {@link options} property, you may specify the options
 * that need to be passed to the JUI dialog plugin. Please refer to
 * the {@link http://jqueryui.com/demos/dilog/ JUI Dialog} documentation
 * for possible options (name-value pairs).
 *
 * @author Sebastian Thierer <sebathi@gmail.com>
 * @version $Id: CJuiAccordion.php 31 2009-09-25 19:25:06Z qiang.xue $
 * @package zii.widgets.jui
 * @since 1.1
 */

class CJuiDialog extends CJuiWidget{

	/**
	 * @var string the name of the container element that contains all panels. Defaults to 'div'.
	 */
	public $tagName='div';


	public function init(){
		parent::init();
		ob_start();
	}

	public function run(){
		$aux = ob_get_clean();

		$id=$this->getId();
		if(!isset($this->htmlOptions['id']))
			$this->htmlOptions['id']=$id;

		echo CHtml::openTag($this->tagName,$this->htmlOptions)."\n";
		echo $aux;
		echo CHtml::closeTag($this->tagName)."\n";

		$options=empty($this->options) ? '' : CJavaScript::encode($this->options);
		Yii::app()->getClientScript()->registerScript(__CLASS__.'#'.$id,"jQuery('#{$id}').dialog($options);");
	}
	/**
	 * This function returns the open script for this dialog.
	 * @return string
	 */
	public function getOpenScript($options = null){
		$id=$this->getId();
		return "jQuery('#{$id}').open($options);";
	}
}
