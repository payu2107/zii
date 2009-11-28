<?php
/**
 * CCheckBoxColumn class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2009 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

Yii::import('zii.widgets.grid.CGridColumn');

/**
 * CCheckBoxColumn represents a grid view column of checkboxes.
 *
 * By default, the checkboxes rendered in data cells will have the key values associated with
 * the data models in the corresponding rows. One may change this by setting either {@link dataField}
 * {@link dataExpression}.
 *
 * CCheckBoxColumn supports single selection and multiple selection. The mode is determined according
 * to {@link CGridView::selectableRows}. When in multiple selection mode, the header cell will display
 * an additional checkbox, clicking on which will check or uncheck all of the checkboxes in the data cells.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Id$
 * @package zii.widgets.grid
 * @since 1.1
 */
class CCheckBoxColumn extends CGridColumn
{
	/**
	 * @var string the attribute name of the data model. The corresponding attribute value will be rendered
	 * in each data cell as the checkbox value. Note that if {@link dataExpression} is specified, this property will be ignored.
	 * @see dataExpression
	 */
	public $dataField;
	/**
	 * @var string a PHP expression that will be evaluated for every data cell and whose result will be rendered
	 * in each data cell as the checkbox value. In this expression, the variable
	 * <code>$grid</code> stands for the grid view instance; <code>$row</code>
	 * the row number (zero-based); <code>$data</code> the data model for the row;
	 * and <code>$this</code> the column object.
	 */
	public $dataExpression;
	/**
	 * @var array the HTML options for the data cell tags.
	 */
	public $htmlOptions=array('class'=>'checkbox-column');
	/**
	 * @var array the HTML options for the header cell tag.
	 */
	public $headerHtmlOptions=array('class'=>'checkbox-column');
	/**
	 * @var array the HTML options for the footer cell tag.
	 */
	public $footerHtmlOptions=array('class'=>'checkbox-column');
	/**
	 * @var array the HTML options for the checkboxes.
	 */
	public $checkBoxHtmlOptions=array();

	/**
	 * Initializes the column.
	 * This method registers necessary client script for the checkbox column.
	 * @param CGridView the grid view instance
	 */
	public function init($grid)
	{
		$name="{$this->id}\\[\\]";
		if($grid->selectableRows==1)
			$one="\n\tjQuery(\"input:not(#\"+$(this).attr('id')+\")[name='$name']\").attr('checked',false);";
		else
			$one='';
		$js=<<<EOD
jQuery('#{$this->id}_all').live('click',function(){
	var checked=this.checked;
	jQuery("input[name='$name']").each(function() {
		this.checked=checked;
	});
});
jQuery("input[name='$name']").click(function() {
	jQuery('#{$this->id}_all').attr('checked', jQuery("input[name='$name']").length==jQuery("input[name='$name'][checked=true]").length);{$one}
});
EOD;
		Yii::app()->getClientScript()->registerScript(__CLASS__.'#'.$this->id,$js);
	}

	/**
	 * Renders the header cell content.
	 * This method will render a checkbox in the header when {@link CGridView::selectableRows} is greater than 1.
	 * @param CGridView the grid view instance
	 */
	protected function renderHeaderCellContent($grid)
	{
		if($grid->selectableRows>1)
			echo CHtml::checkBox($this->id.'_all',false);
		else
			parent::renderHeaderCellContent($grid);
	}

	/**
	 * Renders the data cell content.
	 * This method renders a checkbox in the data cell.
	 * @param CGridView the grid view instance
	 * @param integer the row number (zero-based)
	 * @param mixed the data associated with the row
	 */
	protected function renderDataCellContent($grid,$row,$data)
	{
		if($this->dataExpression!==null)
			$value=$this->evaluateExpression($this->dataExpression,array('data'=>$data,'grid'=>$grid,'row'=>$row));
		else if($this->dataField!==null)
			$value=CHtml::value($data,$this->dataField);
		else
			$value=$grid->dataProvider->keys[$row];
		$options=$this->checkBoxHtmlOptions;
		$options['value']=$value;
		$options['id']=$this->id.'_'.$row;
		echo CHtml::checkBox($this->id.'[]',false,$options);
	}
}
