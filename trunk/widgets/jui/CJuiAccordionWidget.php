<?php
/**
 * CJuiAccordionWidget class file.
 *
 * @author Sebastian Thierer <sebas@artfos.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2009 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

 /**
 * This is the base class for
 * @author Sebastian Thierer <sebathi@gmail.com>
 * @version $Id: CJuiWidget.php 13 2009-09-22 16:37:45Z qiang.xue $
 * @package zii.widgets.jui
 * @since 1.1
 */

Yii::import('zii.widgets.jui.CJuiWidget');

class CJuiAccordionWidget extends CJuiWidget {
	
	/**
	 * Selector for the active element. Set to false to display none at start. Needs collapsible: true.
	 * @var string
	 */
	public $active = null;
	/**
	 * Choose your favorite animation, or disable them (set to false). In addition to the default, 
	 * 'bounceslide' and all defined easing methods are supported ('bounceslide' requires UI Effects Core).
	 * @var unknown_type
	 */
	public $animated = null;
	
	/**
	 * If set, the highest content part is used as height reference for all other parts. 
	 * Provides more consistent animations.
	 * @var boolean
	 */
	public $autoHeight = false;
	
	/**
	 * If set, clears height and overflow styles after finishing animations. 
	 * This enables accordions to work with dynamic content. Won't work together with autoHeight.
	 * @var boolean
	 */
	public $clearStyle = false;
	
	/**
	 * Whether all the sections can be closed at once. Allows collapsing the active section by the triggering event (click is the default).
	 * @var boolean
	 */
	public $collapsible = false;
	
	/**
	 * The event on which to trigger the accordion.
	 * @var string
	 */
	public $event = 'click';
	
	/**
	 * If set, the accordion completely fills the height of the parent element. Overrides autoheight.
	 * @var boolean
	 */
	public $fillSpace = false;
	
	/**
	 * Selector for the header element. Default 'H3'
	 * @var string
	 */
	public $header = 'H3';
	
	/**
	 * Icons to use for headers. Icons may be specified for 'header' and 'headerSelected', and we recommend using the icons 
	 * native to the jQuery UI CSS Framework manipulated by jQuery UI ThemeRoller. 
	 * For example: 
	 * 		$('.selector').accordion({ icons: { 'header': 'ui-icon-plus', 'headerSelected': 'ui-icon-minus' } });
	 * @var string
	 */
	public $icons;
	
	/**
	 * If set, looks for the anchor that matches location.href and activates it. Great for href-based state-saving.
	 * Use navigationFilter to implement your own matcher.
	 * @var boolean
	 */
	public $navigation;
	
	/**
	 * Overwrite the default location.href-matching with your own matcher.
	 * @var unknown_type
	 */
	public $navigationFilter;
	
	/**
	 * This event is triggered every time the accordion changes. If the accordion is animated, the event will be triggered upon completion of the animation; otherwise, it is triggered immediately.
	 * $('.ui-accordion').bind('accordionchange', function(event, ui) {
	 *   ui.newHeader // jQuery object, activated header
	 *   ui.oldHeader // jQuery object, previous header
	 *   ui.newContent // jQuery object, activated content
	 *   ui.oldContent // jQuery object, previous content
	 * });
	 * @var string
	 */
	public $change = null;
	
	/**
	 * This event is triggered every time the accordion starts to change. 
	 * $('.ui-accordion').bind('accordionchangestart', function(event, ui) {
	 *   ui.newHeader // jQuery object, activated header
	 *   ui.oldHeader // jQuery object, previous header
	 *   ui.newContent // jQuery object, activated content
	 *   ui.oldContent // jQuery object, previous content
	 * });
	 * @var string
	 */
	public $changeStart = null;
	

	/**
	 * An array with accordion titles and items:
	 * example:
	 * array(
	 *   'header 1'=>'<B>HTML Content 1</b>',
	 *   'header 2'=>'<B>HTML Content 2</b>',
	 *   'header 3'=>'<B>HTML Content 3</b>',
	 * )
	 * @var array
	 */
	public $items = null;

	/**
	 * Gets the JavaScript to be called at page start
	 * @return string 
	 */
	private function getJSCommand($id){
		$params = array();
		if ($this->active!==null)
			$params['active'] = $this->active;
		if ($this->animated!==null)
			$params['animated'] = $this->animated;
		if ($this->autoHeight!==null)
			$params['autoHeight'] = $this->autoHeight;
		if ($this->clearStyle!==null)
			$params['clearStyle'] = $this->clearStyle;
		if ($this->event!==null)
			$params['event'] = $this->event;
		if ($this->fillSpace!==null)
			$params['fillSpace'] = $this->fillSpace;
		if ($this->icons!==null)
			$params['icons'] = $this->icons;
		if ($this->navigation!==null)
			$params['clearStyle'] = $this->clearStyle;
		if ($this->navigationFilter!==null)
			$params['navigationFilter'] = $this->navigationFilter;
		if ($this->change!==null)
			$params['change'] = $this->change;
		if ($this->changeStart!==null)
			$params['changeStart'] = $this->changeStart;
			
		return "$('#$id').accordion(".CJavaScript::encode($params).");";
	}
	/**
	 * Generates the HTML to display this widget
	 * @param $id
	 * @return unknown_type
	 */
	private function generateHtml($id){
		if (is_null($this->items) || !is_array($this->items)){
			throw new CException('zii', 'Property {property} could not be null', array('property'=>'items'));
		}
		$html = '<div id="'.$id.'">';
		foreach ($this->items as $title=>$content){
			$html.="<" . $this->header . '><a href="#">' . $title . '</a><' . $this->header . '>';
			$html.='<div>'.$content.'</div>';
		}
		$html .= '</div>';
		return $html;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see web/widgets/CWidget#run()
	 */
	public function run(){
		$id = 'zii_widgets_jui_accordion_'.$this->getId();

		echo $this->generateHtml($id);
		
		Yii::app()->getClientScript()->registerScript($id.'_js', $this->getJSCommand($id));
		
	}
	
}




























