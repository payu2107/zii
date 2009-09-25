<?php
/**
 * CJuiTabs class file.
 *
 * @author Sebastian Thierer <sebas@artfos.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2009 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

Yii::import('zii.widgets.jui.CJuiWidget');

/**
 * CJuiTabs displays a tabs widget.
 *
 * CJuiTabs encapsulates the {@link http://jqueryui.com/demos/tabs/ JUI Accordion}
 * plugin.
 *
 * To use this widget, you may insert the following code in a view:
 * <pre>
 * $this->widget('zii.widgets.jui.CJuiTabs', array(
 *     'tabs'=>array(
 *         'StaticTab 1'=>'Content for tab 1',
 *         'StaticTab 2'=>array('content'=>'Content for tab 2', 'idtab'=>'tab2'),
 *         // panel 3 contains the content rendered by a partial view
 *         'AjaxTab'=>array('ajax'=>$ajaxUrl),
 *     ),
 *     // additional javascript options for the accordion plugin
 *     'options'=>array(
 *         'collapsible'=>true,
 *     ),
 * ));
 * </pre>
 *
 * By configuring the {@link options} property, you may specify the options
 * that need to be passed to the JUI tabs plugin. Please refer to
 * the {@link http://jqueryui.com/demos/tabs/ JUI Accordion} documentation
 * for possible options (name-value pairs).
 *
 * @author Sebastian Thierer <sebathi@gmail.com>
 * @version $Id$
 * @package zii.widgets.jui
 * @since 1.1
 */
class CJuiTabs extends CJuiWidget
{
	/**
	 * @var array list of tabs (panel title=>tab content). 
	 * tab content is a string or an array with two possible subitems 'ajax' or 'content' 
	 * (if 'content' is present 'ajax' will be ignored).
	 * Note that neither tab title nor tab content will be HTML-encoded.
	 */
	public $tabs=array();
	/**
	 * @var string the name of the container element that contains all panels. Defaults to 'div'.
	 */
	public $tagName='div';
	/**
	 * @var string the template that is used to generated every panel title.
	 * The token "{title}" in the template will be replaced with the panel title and 
	 * the token "{url}" will be replaced with "#{tabId}" or with the url of the ajax request.
	 * Note that if you make change to this template, you may also need to adjust
	 * the 'header' setting in {@link options}.
	 */
	public $headerTemplate='<li><a href="{url}">{title}</a></li>';
	/**
	 * @var string the template that is used to generated every tab content.
	 * The token "{content}" in the template will be replaced with the panel content
	 * and the token "{tabId}" with the tab id.
	 */
	public $contentTemplate='<div id="{tabId}">{content}</div>';

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
		
		$tabsOut = "";
		$contentOut = "";
		$tabCount = 0;
		
		foreach($this->tabs as $title=>$content)
		{
			$tabId = (is_array($content) && isset($content['idtab']))?$content['idtab']:$id.'_tab_'.$tabCount++;
			
			if (!is_array($content)){
				$tabsOut .= strtr($this->headerTemplate, array('{title}'=>$title, '{url}'=>'#'.$tabId))."\n";
				$contentOut .= strtr($this->contentTemplate, array('{content}'=>$content,'{tabId}'=>$tabId))."\n";

			}elseif (isset($content['content'])){
				$tabsOut .= strtr($this->headerTemplate, array('{title}'=>$title, '{url}'=>'#'.$tabId))."\n";
				$contentOut .= strtr($this->contentTemplate, array('{content}'=>$content['content'],'{tabId}'=>$tabId))."\n";
			
			}elseif (isset($content['ajax'])){
				$tabsOut .= strtr($this->headerTemplate,array('{title}'=>$title, '{url}'=>$content['ajax']))."\n";
			}
		}
		echo "<ul>\n" . $tabsOut . "</ul>\n";
		echo $contentOut;

		echo CHtml::closeTag($this->tagName)."\n";

		$options=empty($this->options) ? '' : CJavaScript::encode($this->options);
		Yii::app()->getClientScript()->registerScript(__CLASS__.'#'.$id,"jQuery('#{$id}').tabs($options);");
	}
}
