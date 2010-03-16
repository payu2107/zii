<?php
/**
 * CJuiAutoComplete class file.
 *
 * @author Sebastian Thiere <sebathi@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2010 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

Yii::import('zii.widgets.jui.CJuiInputWidget');

/**
 * CJuiAutoComplete displays an autocomplete field.
 *
 * CJuiAutoComplete encapsulates the {@link http://jqueryui.com/demos/autocomplete/ JUI
 * autocomplete} plugin.
 *
 * To use this widget, you may insert the following code in a view:
 * <pre>
 * $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
 *     'name'=>'city',
 *     'source'=>array('ac1', 'ac2', 'ac3'),
 *     // additional javascript options for the autocomplete plugin
 *     'options'=>array(
 *         'showAnim'=>'fold',
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
 * By configuring the {@link source} property, you may specify where to search
 * the autocomplete options for each item. If source is an array, the list is 
 * used for autocomplete. If it's an URL (string), the component will do an 
 * ajax request to this page.
 *
 * @author Sebastian Thierer <sebathi@gmail.com>
 * @version $Id
 * @package zii.widgets.jui
 * @since 1.1
 */
class CJuiAutoComplete extends CJuiInputWidget
{
	/**
	 * @var array the source of the autoComplete. List of items for the autocomplete.
	 */
	public $source = array();

	/**
	 * @var string the remote source URL for ajax loading. If this is set, the {link source} 
	 * property is ignored.
	 */
	public $remoteSource;
	/**
	 * Run this widget.
	 * This method registers necessary javascript and renders the needed HTML code.
	 */
	public function run()
	{
		list($name,$id)=$this->resolveNameID();

		if(isset($this->htmlOptions['id']))
			$id=$this->htmlOptions['id'];
		else
			$this->htmlOptions['id']=$id;

		if(isset($this->htmlOptions['name']))
			$name=$this->htmlOptions['name'];
		else
			$this->htmlOptions['name']=$name;

		if($this->hasModel())
			echo CHtml::activeTextField($this->model,$this->attribute,$this->htmlOptions);
		else
			echo CHtml::textField($name,$this->value,$this->htmlOptions);

		
		if(isset($this->remoteSource)){
			if (is_array($this->remoteSource)){
				$route=isset($this->remoteSource[0]) ? $this->remoteSource[0] : '';
				$this->options['source']=$this->controller->createUrl($route,array_splice($this->remoteSource,1));
			}else{
				$this->options['source']=$this->remoteSource;
			}
		}else{
			$this->options['source'] = $this->source;
		}	

		$options=CJavaScript::encode($this->options);

		$js = "jQuery('#{$id}').autocomplete($options);";

		$cs = Yii::app()->getClientScript();
		$cs->registerScript(__CLASS__.'#'.$id, $js);
	}
}
