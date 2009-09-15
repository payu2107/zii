<?php
/**
 * CJuiBase class file.
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

abstract class CJuiBase extends CInputWidget{

	/**
	 * Initialized CClientScript
	 * @var CClientScript
	 */
	private $_csinitialized = false;

	/**
	 * Gets the Yii application client script
	 * @return CClientScript
	 */
	public function getClientScript(){
		if ($this->_csinitialized===false){
			$this->_csinitialized = Yii::app()->getClientScript();
			$this->_csinitialized->registerCoreScript('jquery');
		}
		return $this->_csinitialized;
	}
	

	/**
	 * List of available themes
	 * @var Array
	 */
	static $availableThemes = array(
									'base',
									'redmond',
							  );
	
	/**
	 * User selected theme. Default set to redmond
	 * @var string 
	 */
	public $theme = 'redmond';
	/**
	 * Sets the current theme to $value. It will check if $value is in the list of available themes. 
	 * If not it will throw an exception.
	 * If external theme is set, this property will be ignore.
	 * 
	 * @var string $value
	 */
	public function checkTheme(){
		if ($this->externalTheme==null && $this->theme!=null){
			foreach (self::$availableThemes as $theme){
				if (strcasecmpcasecmp($theme, $value)==0){
					$this->_theme = $theme;
					return true;
				}
			}
			throw new CException(Yii::t('zii', 'The current theme is not available.'));
		}else if($this->theme==null){
			Throw new CException (Yii::t('zii', 'The external theme selected needs a theme name'));
		}
	}
	/**
	 * Gets the current selected theme.
	 * 
	 * @return string
	 */
	public function getTheme(){
		return $this->theme;
	}
	
	/**
	 * Url of external theme. If this one is set the component 
	 * will not import included theme
	 * 
	 * @var String
	 */
	public $externalTheme = null;

	
	/**
	 * List of published themes. cache for multiple times imported themes.
	 * @var unknown_type
	 */
	static $_publishedThemes = array();
	
	const THEMES_CSS_DIRECTORY = 'jquery/css/';
	const THEME_CSS_FILENAME = 'jquery-ui-1.7.1.custom.css';
	
	public function publishTheme(){
		if (isset(self::$_publishedTheme[$this->theme])){
			$cs = $this->getClientScript();
			$url = null;
			if ($this->externalTheme==null){ // is internal theme
		        $dir = dirname(__FILE__).DIRECTORY_SEPARATOR.self::THEMES_CSS_DIRECTORY . $this->theme;
		        $baseUrl = Yii::app()->getAssetManager()->publish($dir);
		        $url = $baseUrl . self::THEME_CSS_FILENAME;
        		$cs->registerCssFile($baseUrl . self::THEME_CSS_FILENAME);
			}else{
				$url = $this->externalTheme;
				$cs->registerCssFile($this->externalTheme);
			}
			self::$_publishedThemes[$this->theme] = $url;
		}
	}
	
	
	abstract function scriptsToImport();
	
	
	static $importedScripts = array();
	
	// This could also be done by the constructor class.
	
	public function init(){
		$scripts = $this->scriptsToImport();
		if (is_array($scripts)){
			$cs = $this->getClientScript();
		    $dir = dirname(__FILE__).DIRECTORY_SEPARATOR.self::SCRIPTS_DIRECTORY;
		    $baseUrl = Yii::app()->getAssetManager()->publish($dir);
	    	foreach($scripts as $scriptFilename){
	    		if (!isset(self::$importedScripts[$scriptFilename])){
		        	$url = $baseUrl . DIRECTORY_SEPARATOR . $scriptFilename;
        			$cs->registerScriptFileFile($baseUrl . self::THEME_CSS_FILENAME);
        			self::$importedScripts[$scriptFilename] = $scriptFilename;
	    		}
			}
		}
	}
	
	

}

