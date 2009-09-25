<?php
/**
 * CJuiProgressbar class file.
 *
 * @author Sebastian Thierer <sebas@artfos.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2009 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

Yii::import('zii.widgets.jui.CJuiWidget');

/**
 * CJuiProgressbar displays a progress bar widget.
 *
 * CJuiProgressbar encapsulates the {@link http://jqueryui.com/demos/progressbar/ JUI 
 * Progressbar} plugin.
 *
 * To use this widget, you may insert the following code in a view:
 * <pre>
 * $this->widget('zii.widgets.jui.CJuiProgressbar', array(
 *     // additional javascript options for the accordion plugin
 *     'options'=>array(
 *         'value'=>27,
 *     ),
 *     'htmlOptions'=>array(
 *         'style'=>'height:20px;'
 *     ),
 * ));
 * </pre>
 *
 * By configuring the {@link options} property, you may specify the options
 * that need to be passed to the JUI progressbar plugin. Please refer to
 * the {@link http://jqueryui.com/demos/progressbar/ JUI Progressbar} documentation
 * for possible options (name-value pairs).
 *
 * @author Sebastian Thierer <sebathi@gmail.com>
 * @version $Id: CJuiAccordion.php 20 2009-09-24 20:40:26Z qiang.xue $
 * @package zii.widgets.jui
 * @since 1.1
 */

Yii::import('zii.widgets.jui.CJuiWidget');

class CJuiProgressbar extends CJuiWidget {

	/**
	 * @var string the name of the container element that contains all panels. Defaults to 'div'.
	 */
	public $tagName = 'div';
	
	/**
	 * Run this widget.
	 * This method registers necessary javascript and renders the needed HTML code.
	 */
	public function run(){
		$id=$this->getId();
		if(!isset($this->htmlOptions['id']))
			$this->htmlOptions['id']=$id;

		echo CHtml::openTag($this->tagName,$this->htmlOptions);
		echo CHtml::closeTag($this->tagName);

		$options=empty($this->options) ? '' : CJavaScript::encode($this->options);
		Yii::app()->getClientScript()->registerScript(__CLASS__.'#'.$id,"jQuery('#{$id}').progressbar($options);");
	}
	
}