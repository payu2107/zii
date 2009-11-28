<?php
/**
 * CRudColumn class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2009 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

Yii::import('zii.widgets.grid.CGridColumn');

/**
 * CRudColumn represents a grid view column that renders "view", "update" and "delete" buttons in each data cell.
 *
 * The name "rud" stands for "read" (view), "update" and "delete".
 *
 * By configuring {@link template}, one may choose not to display all of the three buttons.
 * For example, setting this property to be "{view} {update}" will only show the "view" and "update" buttons.
 *
 * Note that when clicking on a delete button, a confirmation dialog may be displayed. If confirmed,
 * the server side will receive a deletion request. The content of the grid view will be refreshed automatically after
 * the deletion is completed. For this reason, the deletion action should not render anything.
 * In case an error is detected on the server side, it should throw a {@link CHttpException}.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Id$
 * @package zii.widgets.grid
 * @since 1.1
 */
class CRudColumn extends CGridColumn
{
	/**
	 * @var array the HTML options for the data cell tags.
	 */
	public $htmlOptions=array('class'=>'rud-column');
	/**
	 * @var array the HTML options for the header cell tag.
	 */
	public $headerHtmlOptions=array('class'=>'rud-column');
	/**
	 * @var array the HTML options for the footer cell tag.
	 */
	public $footerHtmlOptions=array('class'=>'rud-column');
	/**
	 * @var string the template that is used to render the content in each data cell.
	 * These tokens are recognized: {view}, {update} and {delete}. They will be replaced
	 * with the "view" button, "update" button and "delete" button, respectively.
	 */
	public $template='{view} {update} {delete}';
	/**
	 * @var string the label for the view button. Defaults to "View".
	 * Note that the label will not be HTML-encoded when rendering.
	 */
	public $viewButtonLabel;
	/**
	 * @var string the image URL for the view button. If not set, an integrated image will be used.
	 * You may set this property to be false to render a text link instead.
	 */
	public $viewButtonImageUrl;
	/**
	 * @var string a PHP expression that is evaluated for every view button and whose result is used
	 * as the URL for the view button. In this expression, the variable
	 * <code>$grid</code> stands for the grid view instance; <code>$row</code>
	 * the row number (zero-based); <code>$data</code> the data model for the row;
	 * and <code>$this</code> the column object.
	 */
	public $viewButtonUrl='Yii::app()->controller->createUrl("view",array("id"=>$data->primaryKey))';
	/**
	 * @var array the HTML options for the view button tag.
	 */
	public $viewButtonHtmlOptions=array('class'=>'view');

	/**
	 * @var string the label for the update button. Defaults to "Update".
	 * Note that the label will not be HTML-encoded when rendering.
	 */
	public $updateButtonLabel;
	/**
	 * @var string the image URL for the update button. If not set, an integrated image will be used.
	 * You may set this property to be false to render a text link instead.
	 */
	public $updateButtonImageUrl;
	/**
	 * @var string a PHP expression that is evaluated for every update button and whose result is used
	 * as the URL for the update button. In this expression, the variable
	 * <code>$grid</code> stands for the grid view instance; <code>$row</code>
	 * the row number (zero-based); <code>$data</code> the data model for the row;
	 * and <code>$this</code> the column object.
	 */
	public $updateButtonUrl='Yii::app()->controller->createUrl("update",array("id"=>$data->primaryKey))';
	/**
	 * @var array the HTML options for the update button tag.
	 */
	public $updateButtonHtmlOptions=array('class'=>'update');

	/**
	 * @var string the label for the delete button. Defaults to "Delete".
	 * Note that the label will not be HTML-encoded when rendering.
	 */
	public $deleteButtonLabel;
	/**
	 * @var string the image URL for the delete button. If not set, an integrated image will be used.
	 * You may set this property to be false to render a text link instead.
	 */
	public $deleteButtonImageUrl;
	/**
	 * @var string a PHP expression that is evaluated for every delete button and whose result is used
	 * as the URL for the delete button. In this expression, the variable
	 * <code>$grid</code> stands for the grid view instance; <code>$row</code>
	 * the row number (zero-based); <code>$data</code> the data model for the row;
	 * and <code>$this</code> the column object.
	 */
	public $deleteButtonUrl='Yii::app()->controller->createUrl("delete",array("id"=>$data->primaryKey))';
	/**
	 * @var array the HTML options for the view button tag.
	 */
	public $deleteButtonHtmlOptions=array('class'=>'delete');
	/**
	 * @var string the confirmation message to be displayed when delete button is clicked.
	 * By setting this property to be false, no confirmation message will be displayed.
	 */
	public $deleteConfirmation;

	/**
	 * Initializes the column.
	 * This method registers necessary client script for the RUD column.
	 * @param CGridView the grid view instance
	 */
	public function init($grid)
	{
		if($this->viewButtonLabel===null)
			$this->viewButtonLabel=Yii::t('yii','View');
		if($this->updateButtonLabel===null)
			$this->updateButtonLabel=Yii::t('yii','Update');
		if($this->deleteButtonLabel===null)
			$this->deleteButtonLabel=Yii::t('yii','Delete');
		if($this->viewButtonImageUrl===null)
			$this->viewButtonImageUrl=$grid->baseScriptUrl.'/view.png';
		if($this->updateButtonImageUrl===null)
			$this->updateButtonImageUrl=$grid->baseScriptUrl.'/update.png';
		if($this->deleteButtonImageUrl===null)
			$this->deleteButtonImageUrl=$grid->baseScriptUrl.'/delete.png';
		if($this->deleteConfirmation===null)
			$this->deleteConfirmation=Yii::t('yii','Are you sure to delete this item?');

		$this->registerClientScript($grid);
	}

	/**
	 * Registers the client scripts for the RUD column.
	 * @param CGridView the grid view instance
	 */
	protected function registerClientScript($grid)
	{
		if(is_string($this->deleteConfirmation))
			$confirmation="if(!confirm(".CJavaScript::encode($this->deleteConfirmation).")) return false;";
		else
			$confirmation='';
		Yii::app()->getClientScript()->registerScript(__CLASS__.'#'.$this->id,"
jQuery('a.{$this->deleteButtonHtmlOptions['class']}').live('click',function(){
	$confirmation
	$.ajax({
		type: 'POST',
		url: $(this).attr('href'),
		success: function() {
			$.fn.yiiGridView.update('{$grid->id}');
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			alert(XMLHttpRequest.responseText);
		},
	});
	return false;
});
");
	}

	/**
	 * Renders the data cell content.
	 * This method renders the view, update and delete buttons in the data cell.
	 * @param CGridView the grid view instance
	 * @param integer the row number (zero-based)
	 * @param mixed the data associated with the row
	 */
	protected function renderDataCellContent($grid,$row,$data)
	{
		$tr=array();
		ob_start();
		foreach(array('view','update','delete') as $button)
		{
			if(strpos($this->template,'{'.$button.'}')!==false)
			{
				$method='render'.$button.'Button';
				$this->$method($grid,$row,$data);
				$tr['{'.$button.'}']=ob_get_contents();
				ob_clean();
			}
		}
		ob_end_clean();
		echo strtr($this->template,$tr);
	}

	/**
	 * Renders a link button.
	 * @param string the button label (will not be HTML-encoded)
	 * @param string the button URL
	 * @param string the image URL for the button. If not a string, a text link will be rendered.
	 * @param array the HTML options for the hyperlink
	 */
	protected function renderButton($label,$url,$imageUrl,$htmlOptions)
	{
		if(!isset($htmlOptions['title']))
			$htmlOptions['title']=$label;
		if(is_string($imageUrl))
			echo CHtml::link(CHtml::image($imageUrl,$label),$url,$htmlOptions);
		else
			echo CHtml::link($label,$url,$htmlOptions);
	}

	/**
	 * Renders the "view" button.
	 * @param CGridView the grid view instance
	 * @param integer the row number (zero-based)
	 * @param mixed the data object associated with the row
	 */
	protected function renderViewButton($grid,$row,$data)
	{
		$url=$this->evaluateExpression($this->viewButtonUrl,array('data'=>$data,'grid'=>$grid,'row'=>$row));
		$this->renderButton($this->viewButtonLabel,$url,$this->viewButtonImageUrl,$this->viewButtonHtmlOptions);
	}

	/**
	 * Renders the "update" button.
	 * @param CGridView the grid view instance
	 * @param integer the row number (zero-based)
	 * @param mixed the data object associated with the row
	 */
	protected function renderUpdateButton($grid,$row,$data)
	{
		$url=$this->evaluateExpression($this->updateButtonUrl,array('data'=>$data,'grid'=>$grid,'row'=>$row));
		$this->renderButton($this->updateButtonLabel,$url,$this->updateButtonImageUrl,$this->updateButtonHtmlOptions);
	}

	/**
	 * Renders the "delete" button.
	 * @param CGridView the grid view instance
	 * @param integer the row number (zero-based)
	 * @param mixed the data object associated with the row
	 */
	protected function renderDeleteButton($grid,$row,$data)
	{
		$url=$this->evaluateExpression($this->deleteButtonUrl,array('data'=>$data,'grid'=>$grid,'row'=>$row));
		$this->renderButton($this->deleteButtonLabel,$url,$this->deleteButtonImageUrl,$this->deleteButtonHtmlOptions);
	}
}
