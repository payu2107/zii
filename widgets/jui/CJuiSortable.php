<?php
/**
 * CJuiSortable class file.
 *
 * @author Sebastian Thierer <sebas@artfos.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2009 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

Yii::import('zii.widgets.jui.CJuiWidget');

/**
 * CJuiSortable displays an accordion widget.
 *
 * CJuiSortable encapsulates the {@link http://jqueryui.com/demos/sortable/ JUI Sortable}
 * plugin.
 *
 * To use this widget, you may insert the following code in a view:
 * <pre>
 * $this->widget('zii.widgets.jui.CJuiSortable', array(
 *     'items'=>array(
 *         'id1'=>'Item 1',
 *         'id2'=>'Item 2',
 *         'id3'=>'Item 3',
 *     ),
 *     // additional javascript options for the accordion plugin
 *     'options'=>array(
 *         'delay'=>'300',
 *     ),
 * ));
 * </pre>
 *
 * By configuring the {@link options} property, you may specify the options
 * that need to be passed to the JUI Sortable plugin. Please refer to
 * the {@link http://jqueryui.com/demos/sortable/ JUI Sortable} documentation
 * for possible options (name-value pairs).
 *
 * @author Sebastian Thierer <sebathi@gmail.com>
 * @version $Id$
 * @package zii.widgets.jui
 * @since 1.1
 */
class CJuiSortable extends CJuiWidget
{
	/**
	 * @var array list of panels (panel title=>panel content).
	 * Note that neither panel title nor panel content will be HTML-encoded.
	 */
	public $items=array();
	/**
	 * @var string the name of the container element that contains all items. Defaults to 'ul'.
	 */
	public $tagName='ul';
	/**
	 * @var string the template that is used to generated every panel content.
	 * The token "{content}" in the template will be replaced with the panel content.
	 */
	public $contentTemplate='<li id="{id}">{content}</li>';

	/**
	 * Run this widget.
	 * This method registers necessary javascript and renders the needed HTML code.
	 */
	public function run()
	{
		$id=$this->getId();
		if(!isset($this->htmlOptions['id']))
			$this->htmlOptions['id']=$id;

		echo CHtml::openTag($this->tagName,$this->htmlOptions)."\n";
		foreach($this->items as $iditem=>$content)
		{
			echo strtr($this->contentTemplate,array('{id}'=>$iditem,'{content}'=>$content))."\n";
		}
		echo CHtml::closeTag($this->tagName);

		$options=empty($this->options) ? '' : CJavaScript::encode($this->options);
		Yii::app()->getClientScript()->registerScript(__CLASS__.'#'.$id,"jQuery('#{$id}').sortable({$options});");
	}
}


