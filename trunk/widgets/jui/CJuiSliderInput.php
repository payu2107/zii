<?php
/**
 * CJuiSliderInput class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2009 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

Yii::import('zii.widgets.jui.CJuiInputWidget');

/**
 * CJuiSliderInput displays a slider.
 *
 * CJuiSlider encapsulates the {@link http://jqueryui.com/demos/slider/ JUI
 * slider} plugin.
 *
 * To use this widget, you may insert the following code in a view:
 * <pre>
 * $this->widget('zii.widgets.jui.CJuiSliderInput', array(
 *     'value'=>37,
 *     'id'=>'porcentageComplete',
 *     // additional javascript options for the slider plugin
 *     'options'=>array(
 *         'min'=>10,
 *         'max'=>50,
 *     ),
 *     'htmlOptions'=>array(
 *         'style'=>'height:20px;'
 *     ),
 * ));
 * </pre>
 *
 * By configuring the {@link options} property, you may specify the options
 * that need to be passed to the JUI slider plugin. Please refer to
 * the {@link http://jqueryui.com/demos/slider/ JUI slider} documentation
 * for possible options (name-value pairs).
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Id: CJuiSliderInput 30 2009-09-25 19:22:50Z qiang.xue $
 * @package zii.widgets.jui
 * @since 1.1
 */
class CJuiSliderInput extends CJuiInputWidget
{
	/**
	 * @var string the name of the container element that contains the slider. Defaults to 'div'.
	 */
	public $tagName = 'div';
	/**
	 * @var integer determines the value of the slider, if there's only one handle. If there is more than one handle, determines the value of the first handle.
	 */
	public $value;
	
	/**
	 * @var string the name of the event where the input will be attached to the slider
	 */
	public $event = 'slide';

	/**
	 * Run this widget.
	 * This method registers necessary javascript and renders the needed HTML code.
	 */
	public function run()
	{
		
		list($name,$id)=$this->resolveNameID();

		if(!isset($this->htmlOptions['id']))
			$this->htmlOptions['id']=$id;
		if(!isset($this->htmlOptions['name']))
			$this->htmlOptions['name']=$name;

		if($this->hasModel()===false && $this->value!==null)
			$this->options['value']=$this->value;

		if($this->hasModel())
			echo CHtml::activeHiddenField($this->model,$this->attribute,$this->htmlOptions);
		else
			echo CHtml::hiddenField($name,$this->value,$this->htmlOptions);

		
		$idHidden = $this->htmlOptions['id'];
		$nameHidden = $this->htmlOptions['name'];
		
		$this->htmlOptions['id']=$idHidden.'_slider';
		$this->htmlOptions['name']=$nameHidden.'_slider';
		
		echo CHtml::openTag($this->tagName,$this->htmlOptions);
		echo CHtml::closeTag($this->tagName);


		//TODO: We have to check if the user has attached an event to onStop
		$this->options[$this->event]= 'js:function(event, ui) { jQuery(\'#'. $idHidden .'\').val(ui.value); }';
		
		
		$options=empty($this->options) ? '' : CJavaScript::encode($this->options);

		$js = "jQuery('#{$id}_slider').slider($options);\n";
		Yii::app()->getClientScript()->registerScript(__CLASS__.'#'.$id, $js);
	}
	
}