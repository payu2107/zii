<?php
/**
 * CGridView class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2009 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

Yii::import('zii.widgets.grid.CDataColumn');
Yii::import('zii.widgets.grid.CLinkColumn');
Yii::import('zii.widgets.grid.CRudColumn');
Yii::import('zii.widgets.grid.CCheckBoxColumn');

/**
 * CGridView displays a list of data models in terms of a table.
 *
 * Each row of the table represents the data of a single data model, and the cells the attributes
 * of the model. CGridView supports both sorting and pagination of the data models. The sorting
 * and pagination can be done in AJAX mode or normal page request model. Unlike many other JavaScript-based
 * data grids, CGridView nicely degenerates to a normal HTML table with functioning sorting and pagination
 * even if the client does not support JavaScript.
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
 * should be properly formatted to show as a time.
 *
 * <pre>
 * $this->widget('zii.widgets.grid.CGridView', array(
 *     'dataProvider'=>$dataProvider,
 *     'columns'=>array(
 *         'title',
 *         array(
 *             'dataField'=>'create_time',
 *             'dataExpression'=>'date("M j, Y", $data)',
 *         )
 *     ),
 * ));
 * </pre>
 *
 * Please refer to {@link columns} to see how to configure this property.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Id$
 * @package zii.widgets.grid
 * @since 1.1
 */
class CGridView extends CWidget
{
	/**
	 * @var string the tag name for the grid view container. Defaults to 'div'.
	 */
	public $tagName='div';
	/**
	 * @var IDataProvider the data provider for this grid view.
	 */
	public $dataProvider;
	/**
	 * @var array grid column configuration. Each array element represents the configuration
	 * for one particular grid column which can be either a string or an array. If a string,
	 * it means the column is a {@link CDataColumn} whose {@link CDataColumn::dataField dataField}
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
	 * @var array the HTML options for the grid view container tag.
	 */
	public $htmlOptions=array();
	/**
	 * @var boolean whether to enable sorting. Note that if the {@link IDataProvider::sort} property
	 * of {@link dataProvider} is false, this will be treated as false as well. When sorting is enabled,
	 * sortable columns will have their headers clickable to trigger sorting along that column.
	 * Defaults to true.
	 */
	public $enableSorting=true;
	/**
	 * @var boolean whether to enable pagination. Note that if the {@link IDataProvider::pagination} property
	 * of {@link dataProvider} is false, this will be treated as false as well. When pagination is enabled,
	 * a pager will be displayed in the grid view so that it can trigger pagination of the data display.
	 * Defaults to true.
	 */
	public $enablePagination=true;
	/**
	 * @var array the configuration for the pager. Defaults to <code>array('class'=>'CLinkPager')</code>.
	 * @see enablePagination
	 */
	public $pager=array('class'=>'CLinkPager');
	/**
	 * @var string the summary text template for the grid view. These tokens are recognized:
	 * {start}, {end} and {count}. They will be replaced with the starting row number, ending row number
	 * and total number of data records.
	 */
	public $summaryText;
	/**
	 * @var string the message to be displayed when {@link dataProvider} does not have any data.
	 */
	public $emptyText;
	/**
	 * @var string the template to be used to control the layout of various components in the grid view.
	 * These tokens are recognized: {summary}, {table} and {pager}. They will be replaced with the
	 * summary text, the table, and the pager.
	 */
	public $template="{summary}\n{table}\n{pager}";
	/**
	 * @var string the CSS class name for the summary text container. Defaults to 'summary'.
	 */
	public $summaryCssClass='summary';
	/**
	 * @var string the CSS class name for the table. Defaults to 'grid'.
	 */
	public $tableCssClass='grid';
	/**
	 * @var string the CSS class name for the pager container. Defaults to 'pager'.
	 */
	public $pagerCssClass='pager';
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
	 */
	public $beforeAjaxUpdate;
	/**
	 * @var string a javascript function that will be invoked after a successful AJAX response is received.
	 */
	public $afterAjaxUpdate;
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
		if($this->dataProvider===null)
			throw new CException(Yii::t('yii','The "dataProvider" property cannot be empty.'));

		$this->dataProvider->getData();

		$this->htmlOptions['id']=$this->getId();
		if(!isset($this->htmlOptions['class']))
			$this->htmlOptions['class']='grid-view';

		if($this->enableSorting && $this->dataProvider->getSort()===false)
			$this->enableSorting=false;
		if($this->enablePagination && $this->dataProvider->getPagination()===false)
			$this->enablePagination=false;

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
				$column=array('class'=>'CDataColumn','dataField'=>$column);
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
	 * Renders the grid view.
	 * This is the main entry of the whole grid view rendering.
	 */
	public function run()
	{
		$this->registerClientScript();

		echo CHtml::openTag($this->tagName,$this->htmlOptions)."\n";

		$this->renderKeys();
		$this->renderGrid();

		echo CHtml::closeTag($this->tagName);
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
		if($this->beforeAjaxUpdate!==null && strpos($this->beforeAjaxUpdate,'js:')!==0)
			$this->beforeAjaxUpdate='js:'.$this->beforeAjaxUpdate;
		if($this->afterAjaxUpdate!==null && strpos($this->afterAjaxUpdate,'js:')!==0)
			$this->afterAjaxUpdate='js:'.$this->afterAjaxUpdate;

		$options=CJavaScript::encode(array(
			'ajaxUpdate'=>$ajaxUpdate,
			'beforeUpdate'=>$this->beforeAjaxUpdate,
			'afterUpdate'=>$this->afterAjaxUpdate,
			'pagerClass'=>$this->pagerCssClass,
			'tableClass'=>$this->tableCssClass,
			'selectableRows'=>$this->selectableRows,
		));
		$cs=Yii::app()->clientScript;
		$cs->registerCoreScript('jquery');
		$cs->registerScriptFile($this->baseScriptUrl.'/jquery.yiigridview.js');
		$cs->registerScript(__CLASS__.'#'.$id,"jQuery('#$id').yiiGridView($options);");
	}

	/**
	 * Renders the grid component.
	 * It includes rendering the summary text, the table and the pager
	 */
	public function renderGrid()
	{
		if($this->dataProvider->getItemCount()>0)
		{
			ob_start();
			foreach(array('summary','table','pager') as $section)
			{
				if(strpos($this->template,'{'.$section.'}')!==false)
				{
					$method='render'.$section;
					$this->$method();
					$tr['{'.$section.'}']=ob_get_contents();
					ob_clean();
				}
			}
			ob_end_clean();
			echo strtr($this->template,$tr);
		}
		else
			$this->renderEmptyText();
	}

	/**
	 * Renders the empty message when there is no data.
	 */
	public function renderEmptyText()
	{
		if($this->emptyText===null)
			echo Yii::t('yii','No results found.');
		else
			echo $this->emptyText;
	}

	/**
	 * Renders the key values of the data in a hidden tag.
	 */
	public function renderKeys()
	{
		echo CHtml::openTag('div',array(
			'class'=>'keys',
			'style'=>'display:none',
			'title'=>Yii::app()->getRequest()->getUrl(),
		));
		foreach($this->dataProvider->getKeys() as $key)
			echo "<span>$key</span>";
		echo "</div>\n";
	}

	/**
	 * Renders the summary text.
	 */
	public function renderSummary()
	{
		echo '<div class="'.$this->summaryCssClass.'">';
		$count=count($this->dataProvider->getData());
		if($this->enablePagination)
		{
			if(($summaryText=$this->summaryText)===null)
				$summaryText=Yii::t('yii','Displaying {start}-{end} of {count} result(s).');
			$pagination=$this->dataProvider->getPagination();
			$start=$pagination->currentPage*$pagination->pageSize+1;
			echo strtr($summaryText,array(
				'{start}'=>$start,
				'{end}'=>$start+$count-1,
				'{count}'=>$this->dataProvider->getTotalItemCount(),
			));
		}
		else
		{
			if(($summaryText=$this->summaryText)===null)
				$summaryText=Yii::t('yii','Total {count} result(s).');
			echo strtr($summaryText,array('{count}'=>$count));
		}
		echo '</div>';
	}

	/**
	 * Renders the pager.
	 */
	public function renderPager()
	{
		if($this->enablePagination)
		{
			$pager=array();
			$class='CLinkPager';
			if(is_string($this->pager))
				$class=$this->pager;
			else if(is_array($this->pager))
			{
				$pager=$this->pager;
				if(isset($pager['class']))
				{
					$class=$pager['class'];
					unset($pager);
				}
			}
			$pager['pages']=$this->dataProvider->getPagination();
			echo '<div class="'.$this->pagerCssClass.'">';
			$this->widget($class,$pager);
			echo '</div>';
		}
	}

	/**
	 * Renders the table.
	 */
	public function renderTable()
	{
		echo "<table class=\"{$this->tableCssClass}\">\n";
		$this->renderTableHeader();
		$this->renderTableFooter();
		$this->renderTableBody();
		echo "</table>";
	}

	/**
	 * Renders the table header.
	 */
	public function renderTableHeader()
	{
		echo "<thead>\n<tr>\n";
		foreach($this->columns as $column)
			$column->renderHeaderCell($this);
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
				$column->renderFooterCell($this);
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
		for($row=0;$row<$n;++$row)
			$this->renderTableRow($row);
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
			$column->renderDataCell($this,$row);
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
