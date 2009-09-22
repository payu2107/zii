<?php
/**
 * CJuiWidget class file.
 *
 * @author Sebastian Thierer <sebas@artfos.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2009 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

 /**
 * This is the base class for
 * @author Sebastian Thierer <sebas@artfos.com>
 * @version $Id$
 * @package zii.widgets.jui
 * @since 1.1
 */
abstract class CJuiWidget extends CWidget
{
	/**
	 * @var string the root URL that contains all JUI-related assets.
	 * This is mainly used to determine {@link scriptUrl} and {@link themeUrl} if they are not explicitly specified.
	 * If this property is not set, it means that the JUI package in the zii release will be published and used.
	 */
	public $baseUrl;
	/**
	 * @var string the root URL that contains all JUI JavaScript files.
	 * If this property is not set, it will default to "{@link baseUrl}/js".
	 */
	public $scriptUrl;
	/**
	 * @var string the main JUI JavaScript file. Defaults to 'jquery-ui-1.7.1.custom.min.js'.
	 */
	public $juiFile='jquery-ui-1.7.1.custom.min.js';
	/**
	 * @var string the root URL that contains all JUI theme folders.
	 * If this property is not set, it will default to "{@link baseUrl}/css".
	 */
	public $themeUrl;
	/**
	 * @var string the JUI theme name. Defaults to 'redmond'.
	 */
	public $theme='redmond';
	/**
	 * @var string the theme CSS file name. Defaults to 'jquery-ui-1.7.1.custom.css'.
	 */
	public $themeFile='jquery-ui-1.7.1.custom.css';
	/**
	 * @var array the JavaScript options that should be passed to the JUI widget.
	 */
	public $options=array();
	/**
	 * @var array the HTML attributes that should be rendered for the HTML tag representing the JUI widget.
	 */
	public $htmlOptions=array();

	/**
	 * Initializes the widget.
	 * This method will publish JUI assets if necessary.
	 * It will also register jquery and JUI JavaScript files and the theme CSS file.
	 * If you override this method, make sure you call the parent implementation first.
	 */
	public function init()
	{
		parent::init();
		if($this->baseUrl===null)
		{
			$basePath=Yii::getPathOfAlias('zii.widgets.jui.assets');
			$this->baseUrl=Yii::app()->getAssetManager()->publish($basePath);
		}
		if($this->scriptUrl===null)
			$this->scriptUrl=$this->baseUrl.'/js';
		if($this->themeUrl===null)
			$this->themeUrl=$this->baseUrl.'/css';
		$this->registerCoreScripts();
	}

	/**
	 * Registers the core script files.
	 * This method registers jquery and JUI JavaScript files and the theme CSS file.
	 */
	protected function registerCoreScripts()
	{
		$cs=Yii::app()->getClientScript();
		$cs->registerCoreScript('jquery');
		$cs->registerScriptFile($this->scriptUrl.'/'.$this->juiFile);
		$cs->registerCssFile($this->themeUrl.'/'.$this->theme.'/'.$this->themeFile);
	}
}
