<?php
/**
 * CJuiDatePicker class file.
 *
 * @author Sebastian Thiere <sebathi@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2009 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

Yii::import('zii.widgets.jui.CJuiInputWidget');

/**
 * CJuiDatePicker displays a datepicker.
 *
 * CJuiDatepicker encapsulates the {@link http://jqueryui.com/demos/datepicker/ JUI
 * datepicker} plugin.
 *
 * To use this widget, you may insert the following code in a view:
 * <pre>
 * $this->widget('zii.widgets.jui.CJuiDatePicker', array(
 *     'value'=>$value,
 *     'id'=>'porcentageComplete',
 *     'dateformat'=>'yy-mm-dd',
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
 * that need to be passed to the JUI datepicker plugin. Please refer to
 * the {@link http://jqueryui.com/demos/datepicker/ JUI datepicker} documentation
 * for possible options (name-value pairs).
 *
 * @author Sebastian Thierer <sebathi@gmail.com>
 * @version $Id$
 * @package zii.widgets.jui
 * @since 1.1
 */
class CJuiDatePicker extends CJuiInputWidget
{
	/**
	 * @var string Current value of the datepicker. If a model is used, this value is ignored. 
	 */
	public $value;
	
	/**
	 * @var string the dateFormat of the datepicker. Please refer to 
	 * {@link http://docs.jquery.com/UI/Datepicker/formatDate JUI datepicker formatDate} 
	 * documentation for more information. 
	 */
	public $dateFormat;

	/**
	 * @var boolean If it's true, the button is showed. Default: true.
	 */
	public $showButton = true;
	
	/**
	 * @var boolean If imageButton is true, an imagebutton is showed. Show button must be true also. Default: true
	 */
	public $buttonImage = true;
	
	/**
	 * @var string The image for the button URL. If it's null, the default image is shown.
	 */
	public $buttonImageUrl = null;

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
			echo CHtml::activeTextField($this->model,$this->attribute,$this->htmlOptions);
		else
			echo CHtml::textField($name,$this->value,$this->htmlOptions);

		if ($this->dateFormat!==null){
			$this->options['dateFormat'] = $this->dateFormat;
		}

		if ($this->showButton===true){
			$this->options['showOn'] = 'true';
			if ($this->imageButton===true){
				$this->options['buttonImageOnly'] = 'true';
				if ($this->buttonImageUrl===null){
					$this->options['buttonImage'] = '';// Where to add this asset? in zii
				}else{
					$this->options['buttonImage'] = $this->buttonImageUrl;
				}
			}
		}
		$options=empty($this->options) ? '' : CJavaScript::encode($this->options);

		$js = "jQuery('#{$id}').datepicker($options);\n";
		Yii::app()->getClientScript()->registerScript(__CLASS__.'#'.$id, $js);
	}
	
}