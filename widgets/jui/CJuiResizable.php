<?php
/**
 * CJuiResizable class file.
 *
 * @author Sebastian Thierer <sebathi@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2010 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

Yii::import('zii.widgets.jui.CJuiWidget');

/**
 * CJuiResizable displays a resizable widget.
 *
 * CJuiResizable encapsulates the {@link http://jqueryui.com/demos/resizable/ JUI Resizable}
 * plugin.
 *
 * To use this widget, you may insert the following code in a view:
 * <pre>
 * $this->beginWidget('zii.widgets.jui.CJuiResizable', array(
 *     // additional javascript options for the resizable plugin
 *     'options'=>array(
 *         'minHeight'=>'150',
 *     ),
 * ));
 *     echo 'Your Resizable content here';
 *
 * $this->endWidget();
 *
 * </pre>
 *
 * By configuring the {@link options} property, you may specify the options
 * that need to be passed to the JUI Resizable plugin. Please refer to
 * the {@link http://jqueryui.com/demos/resizable/ JUI Resizable} documentation
 * for possible options (name-value pairs).
 *
 * @author Sebastian Thierer <sebathi@gmail.com>
 * @version $Id$
 * @package zii.widgets.jui
 * @since 1.1
 */
class CJuiResizable extends CJuiWidget
{
	/**
	 * @var string the name of the Resizable element. Defaults to 'div'.
	 */
	public $tagName='div';

	/**
	 * Renders the open tag of the resizable element.
	 * This method also registers the necessary javascript code.
	 */
	public function init()
	{
		parent::init();
		$id=$this->getId();
		$this->htmlOptions['id']=$id;
		$options=empty($this->options) ? '' : CJavaScript::encode($this->options);
		Yii::app()->getClientScript()->registerScript(__CLASS__.'#'.$id,"jQuery('#{$id}').resizable($options);");
		echo CHtml::openTag($this->tagName,$this->htmlOptions)."\n";
	}

	/**
	 * Renders the close tag of the resizable element.
	 */
	public function run()
	{
		echo CHtml::closeTag($this->tagName);
	}

}


