<?php
/**
 * CGridColumn class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2009 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

/**
 * CGridColumn is the base class for all grid view column classes.
 *
 * A CGridColumn object represents the specification for rendering the cells in
 * a particular grid view column.
 *
 * In a column, there is one header cell, multiple data cells, and an optional footer cell.
 * Child classes may override {@link renderHeaderCellContent}, {@link renderDataCellContent}
 * and {@link renderFooterCellContent} to customize how these cells are rendered.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Id$
 * @package zii.widgets.grid
 * @since 1.1
 */
abstract class CGridColumn extends CComponent
{
	/**
	 * @var string the ID of this column. This value should be unique among all grid view columns.
	 * If this is set, it will be assigned one automatically.
	 */
	public $id;
	/**
	 * @var string the header cell text. Note that it will not be HTML-encoded.
	 */
	public $header;
	/**
	 * @var string the footer cell text. Note that it will not be HTML-encoded.
	 */
	public $footer;
	/**
	 * @var string a PHP expression that is evaluated for every data cell and whose result
	 * is used as the CSS class name for the data cell. In this expression, the variable
	 * <code>$grid</code> stands for the grid view instance; <code>$row</code>
	 * the row number (zero-based); <code>$data</code> the data model for the row;
	 * and <code>$this</code> the column object.
	 */
	public $cssClassExpression;
	/**
	 * @var array the HTML options for the data cell tags.
	 */
	public $htmlOptions=array();
	/**
	 * @var array the HTML options for the header cell tag.
	 */
	public $headerHtmlOptions=array();
	/**
	 * @var array the HTML options for the footer cell tag.
	 */
	public $footerHtmlOptions=array();

	/**
	 * Initializes the column.
	 * This method is invoked by the grid view when it initializes itself before rendering.
	 * You may override this method to prepare the column for rendering.
	 */
	public function init($grid)
	{
	}

	/**
	 * @return boolean whether this column has a footer cell.
	 * This is determined based on whether {@link footer} is set.
	 */
	public function getHasFooter()
	{
		return $this->footer!==null;
	}

	/**
	 * Renders the header cell.
	 * @param CGridView the grid view instance
	 */
	public function renderHeaderCell($grid)
	{
		$this->headerHtmlOptions['id']=$this->id;
		echo CHtml::openTag('th',$this->headerHtmlOptions);
		$this->renderHeaderCellContent($grid);
		echo "</th>";
	}

	/**
	 * Renders a data cell.
	 * @param CGridView the grid view instance
	 * @param integer the row number (zero-based)
	 */
	public function renderDataCell($grid,$row)
	{
		$data=$grid->dataProvider->data[$row];
		$options=$this->htmlOptions;
		if($this->cssClassExpression!==null)
		{
			$class=$this->evaluateExpression($this->cssClassExpression,array('grid'=>$grid,'row'=>$row,'data'=>$data));
			if(isset($options['class']))
				$options['class'].=' '.$class;
			else
				$options['class']=$class;
		}
		echo CHtml::openTag('td',$options);
		$this->renderDataCellContent($grid,$row,$data);
		echo '</td>';
	}

	/**
	 * Renders the footer cell.
	 * @param CGridView the grid view instance
	 */
	public function renderFooterCell($grid)
	{
		echo CHtml::openTag('th',$this->footerHtmlOptions);
		$this->renderFooterCellContent($grid);
		echo '</td>';
	}

	/**
	 * Renders the header cell content.
	 * The default implementation simply renders {@link header}.
	 * This method may be overridden to customize the rendering of the header cell.
	 * @param CGridView the grid view instance
	 */
	protected function renderHeaderCellContent($grid)
	{
		echo trim($this->header)!=='' ? $this->header : '&nbsp;';
	}

	/**
	 * Renders the footer cell content.
	 * The default implementation simply renders {@link footer}.
	 * This method may be overridden to customize the rendering of the footer cell.
	 * @param CGridView the grid view instance
	 */
	protected function renderFooterCellContent($grid)
	{
		echo trim($this->footer)!=='' ? $this->footer : '&nbsp;';
	}

	/**
	 * Renders the data cell content.
	 * This method SHOULD be overridden to customize the rendering of the data cell.
	 * @param CGridView the grid view instance
	 * @param integer the row number (zero-based)
	 * @param mixed the data associated with the row
	 */
	protected function renderDataCellContent($grid,$row,$data)
	{
		echo '&nbsp;';
	}
}
