<?php
/**
 * CGridView class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2009 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

Yii::import('zii.widgets.CBaseListView');
Yii::import('zii.widgets.grid.CDataColumn');
Yii::import('zii.widgets.grid.CLinkColumn');
Yii::import('zii.widgets.grid.CRudColumn');
Yii::import('zii.widgets.grid.CCheckBoxColumn');

/**
 * CGridView displays a list of data items in terms of a table.
 *
 * Each row of the table represents the data of a single data item, and a column usually represents
 * an attribute of the item (some columns may correspond to complex expression of attributes or static text).
 *
 * CGridView supports both sorting and pagination of the data items. The sorting
 * and pagination can be done in AJAX mode or normal page request. A benefit of using CGridView is that
 * when the user browser disables JavaScript, the sorting and pagination automatically degenerate
 * to normal page requests and are still functioning as expected.
 *
 * CGridView should be used together with a {@link IDataProvider data provider}, preferrably a
 * {@link CActiveDataProvider}.
 *
 * The minimal code needed to use CGridView is as follows:
 *
 * <pre>
 * $dataProvider=new CActiveDataProvider('Post');
 *
 * $this->widget('zii.widgets.grid.CGridView', array(
 *     'dataProvider'=>$dataProvider,
 * ));
 * </pre>
 *
 * The above code first creates a data provider for the <code>Post</code> ActiveRecord class.
 * It then uses CGridView to display every attribute in every <code>Post</code> instance.
 * The displayed table is equiped with sorting and pagination functionality.
 *
 * In order to selectively display attributes with different formats, we may configure the
 * {@link CGridView::columns} property. For example, we may specify only the <code>title</code>
 * and <code>create_time</code> attributes to be displayed, and the <code>create_time</code>
 * should be properly formatted to show as a time. We may also display the attributes of the related
 * objects using the dot-syntax as shown below:
 *
 * <pre>
 * $this->widget('zii.widgets.grid.CGridView', array(
 *     'dataProvider'=>$dataProvider,
 *     'columns'=>array(
 *         'title',          // display the 'title' attribute
 *         'category.name',  // display the 'name' attribute of the 'category' relation
 *         array(            // display 'create_time' using an expression
 *             'name'=>'create_time',
 *             'value'=>'date("M j, Y", $data->create_time)',
 *         ),
 *         array(            // display 'author.username' using an expression
 *             'name'=>'authorName',
 *             'value'=>'$data->author->username',
 *         ),
 *         array(            // display a column with "view", "update" and "delete" buttons
 *             'class'=>'CRudColumn',
 *         ),
 *     ),
 * ));
 * </pre>
 *
 * Please refer to {@link columns} for more details about how to configure this property.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Id$
 * @package zii.widgets.grid
 * @since 1.1
 */
class CGridView extends CBaseListView
{
	/**
	 * @var array grid column configuration. Each array element represents the configuration
	 * for one particular grid column which can be either a string or an array. If a string,
	 * it means the column is a {@link CDataColumn} whose {@link CDataColumn::name name}
	 * is the string value. If an array, it will be used to create a grid column instance, where
	 * the 'class' element specifies the column class name (defaults to {@link CDataColumn} if absent).
	 * Currently, these official column classes are provided: {@link CDataColumn},
	 * {@link CLinkColumn}, {@link CRudColumn} and {@link CCheckBoxColumn}.
	 */
	public $columns=array();
	/**
	 * @var array the CSS class names for the table body rows. If multiple CSS class names are given,
	 * they will be assigned to the rows sequentially and repeatedly. This property is ignored
	 * if {@link rowCssClassExpression} is set. Defaults to <code>array('odd', 'even')</code>.
	 * @see rowCssClassExpression
	 */
	public $rowCssClass=array('odd','even');
	/**
	 * @var string a PHP expression that is evaluated for every table body row and whose result
	 * is used as the CSS class name for the row. In this expression, the variable <code>$row</code>
	 * stands for the row number (zero-based) and <code>$this</code> the column object.
	 * @see rowCssClass
	 */
	public $rowCssClassExpression;
	/**
	 * @var boolean whether to display the table even when there is no data. Defaults to true.
	 * The {@link emptyText} will be displayed to indicate there is no data.
	 */
	public $showTableOnEmpty=true;
	/**
	 * @var mixed the ID of the container whose content may be updated with an AJAX response.
	 * Defaults to null, meaning the container for this grid view instance.
	 * If it is set false, it means sorting and pagination will be performed in normal page requests
	 * instead of AJAX requests. If the sorting and pagination should trigger the update of multiple
	 * containers' content in AJAX fashion, these container IDs may be listed here (separated with comma).
	 */
	public $ajaxUpdate;
	/**
	 * @var string a javascript function that will be invoked before an AJAX update occurs.
	 * The function signature is <code>function(id)</code> where 'id' refers to the ID of the grid view.
	 */
	public $beforeAjaxUpdate;
	/**
	 * @var string a javascript function that will be invoked after a successful AJAX response is received.
	 * The function signature is <code>function(id, data)</code> where 'id' refers to the ID of the grid view
	 * 'data' the received ajax response data.
	 */
	public $afterAjaxUpdate;
	/**
	 * @var string a javascript function that will be invoked after the row selection is changed.
	 * The function signature is <code>function(id)</code> where 'id' refers to the ID of the grid view.
	 * In this function, you may use <code>$.fn.yiiGridView.getSelection(id)</code> to get the key values
	 * of the currently selected rows.
	 * @see selectableRows
	 */
	public $selectionChanged;
	/**
	 * @var integer the number of table body rows that can be selected. If 0, it means rows cannot be selected.
	 * If 1, only one row can be selected. If 2 or any other number, it means multiple rows can be selected.
	 * A selected row will have a CSS class named 'selected'. You may also call the JavaScript function
	 * <code>$.fn.yiiGridView.getSelection(containerID)</code> to retrieve the key values of the selected rows.
	 */
	public $selectableRows=1;
	/**
	 * @var string the base script URL for all grid view resources (e.g. javascript, CSS file, images).
	 * Defaults to null, meaning using the integrated grid view resources (which are published as assets).
	 */
	public $baseScriptUrl;
	/**
	 * @var string the URL of the CSS file used by this grid view. Defaults to null, meaning using the integrated
	 * CSS file. If this is set false, you are responsible to explicitly include the necessary CSS file in your page.
	 */
	public $cssFile;

	/**
	 * Initializes the grid view.
	 * This method will initialize required property values and instantiate {@link columns} objects.
	 */
	public function init()
	{
		parent::init();

		if(!isset($this->htmlOptions['class']))
			$this->htmlOptions['class']='grid-view';

		if($this->baseScriptUrl===null)
			$this->baseScriptUrl=Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('zii.widgets.assets')).'/gridview';

		if($this->cssFile!==false)
		{
			if($this->cssFile===null)
				$this->cssFile=$this->baseScriptUrl.'/styles.css';
			Yii::app()->getClientScript()->registerCssFile($this->cssFile);
		}

		$this->initColumns();
	}

	/**
	 * Creates column objects and initializes them.
	 */
	protected function initColumns()
	{
		if($this->columns===array() && $this->dataProvider instanceof CActiveDataProvider)
			$this->columns=CActiveRecord::model($this->dataProvider->modelClass)->attributeNames();
		$id=$this->getId();
		foreach($this->columns as $i=>$column)
		{
			if(is_string($column))
				$column=array('class'=>'CDataColumn','name'=>$column);
			else if(!isset($column['class']))
				$column['class']='CDataColumn';
			$this->columns[$i]=$column=Yii::createComponent($column, $this);
			if($column->id===null)
				$column->id=$id.'_c'.$i;
		}

		foreach($this->columns as $column)
			$column->init();
	}

	/**
	 * Registers necessary client scripts.
	 */
	public function registerClientScript()
	{
		$id=$this->getId();

		if($this->ajaxUpdate===false)
			$ajaxUpdate=array();
		else
			$ajaxUpdate=array_unique(preg_split('/\s*,\s*/',$this->ajaxUpdate.','.$id,-1,PREG_SPLIT_NO_EMPTY));
		$options=array(
			'ajaxUpdate'=>$ajaxUpdate,
			'pagerClass'=>$this->pagerCssClass,
			'tableClass'=>$this->itemsCssClass,
			'selectableRows'=>$this->selectableRows,
		);
		if($this->beforeAjaxUpdate!==null)
			$options['beforeAjaxUpdate']=(strpos($this->beforeAjaxUpdate,'js:')!==0 ? 'js:' : '').$this->beforeAjaxUpdate;
		if($this->afterAjaxUpdate!==null)
			$options['afterAjaxUpdate']=(strpos($this->afterAjaxUpdate,'js:')!==0 ? 'js:' : '').$this->afterAjaxUpdate;
		if($this->selectionChanged!==null)
			$options['selectionChanged']=(strpos($this->selectionChanged,'js:')!==0 ? 'js:' : '').$this->selectionChanged;

		$options=CJavaScript::encode($options);
		$cs=Yii::app()->getClientScript();
		$cs->registerCoreScript('jquery');
		$cs->registerScriptFile($this->baseScriptUrl.'/jquery.yiigridview.js');
		$cs->registerScript(__CLASS__.'#'.$id,"jQuery('#$id').yiiGridView($options);");
	}

	/**
	 * Renders the data items for the grid view.
	 */
	public function renderItems()
	{
		if($this->dataProvider->getItemCount()>0 || $this->showTableOnEmpty)
		{
			echo "<table class=\"{$this->itemsCssClass}\">\n";
			$this->renderTableHeader();
			$this->renderTableFooter();
			$this->renderTableBody();
			echo "</table>";
		}
		else
			$this->renderEmptyText();
	}

	/**
	 * Renders the table header.
	 */
	public function renderTableHeader()
	{
		echo "<thead>\n<tr>\n";
		foreach($this->columns as $column)
			$column->renderHeaderCell();
		echo "</tr>\n</thead>\n";
	}

	/**
	 * Renders the table footer.
	 */
	public function renderTableFooter()
	{
		if($this->getHasFooter())
		{
			echo "<tfoot>\n<tr>\n";
			foreach($this->columns as $column)
				$column->renderFooterCell();
			echo "</tr>\n</tfoot>\n";
		}
	}

	/**
	 * Renders the table body.
	 */
	public function renderTableBody()
	{
		$data=$this->dataProvider->getData();
		$n=count($data);
		echo "<tbody>\n";
		if($n>0)
		{
			for($row=0;$row<$n;++$row)
				$this->renderTableRow($row);
		}
		else
		{
			echo '<tr><td colspan="'.count($this->columns).'">';
			$this->renderEmptyText();
			echo "</td></tr>\n";
		}
		echo "</tbody>\n";
	}

	/**
	 * Renders a table body row.
	 * @param integer the row number (zero-based).
	 */
	public function renderTableRow($row)
	{
		if($this->rowCssClassExpression!==null)
			echo '<tr class="'.$this->evaluateExpression($this->rowCssClassExpression,array('row'=>$row)).'">';
		else if(is_array($this->rowCssClass) && ($n=count($this->rowCssClass))>0)
			echo '<tr class="'.$this->rowCssClass[$row%$n].'">';
		else
			echo '<tr>';
		foreach($this->columns as $column)
			$column->renderDataCell($row);
		echo "</tr>\n";
	}

	/**
	 * @return boolean whether the table should render a footer.
	 * This is true if any of the {@link columns} has a true {@link CGridColumn::hasFooter} value.
	 */
	public function getHasFooter()
	{
		foreach($this->columns as $column)
			if($column->getHasFooter())
				return true;
		return false;
	}
}
