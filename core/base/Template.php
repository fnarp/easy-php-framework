<?php
/**
 * Template generator
 *
 */
class Template {
	// The controller, which owns this template object
	private $controller;
	// The owner controllers current action
	private $action;
	// The params that the action created
	private $params;
	// The used layout, which surrounds the innet contents
	private $layout;
	// Pre defined default layout, it's redifinable with setting controller's $default_layout property
	const DEFAULT_LAYOUT = 'main';

	
	/**
	 * Main initialization method
	 * Setup controller, action and template layout
	 *
	 * @param string $controller
	 * @param string $action
	 * @param array $params
	 */
	public function init($controller, $action) {
		$this->controller = $controller;
		$this->action = $action;
		$this->layout = Template::DEFAULT_LAYOUT;
	}
	
	/**
	 * Main renderint method
	 * Prints template to the stdout
	 *
	 */
	public function renderToScreen() {
		$out = Template::getFileContent($this->getTemplateDir().$this->action.'.htpl', Loader::load('Template')->params);		
		$out = str_replace('[[CONTENT]]', $out, $this->getLayout());		
		
		echo $out;
	}
	
	/**
	 * Get the layout content
	 * 
	 * @return string
	 */
	private function getLayout() {
		return Template::getFileContent(BASE_PATH.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.'layout'.DIRECTORY_SEPARATOR.$this->layout.'.htpl', Loader::load('Template')->params);
	}
	
	/**
	 * Get the path of the controller's template dir
	 *
	 * @return string
	 */
	private function getTemplateDir() {
		return BASE_PATH.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.$this->controller.DIRECTORY_SEPARATOR;
	}
	
	/**
	 * Set params generated by controller
	 *
	 * @param array $params
	 */
	public function setParams($params) {
		$this->params = $params;
	}
	
	/**
	 * Set template layout
	 *
	 * @param string $layout
	 */
	public function setLayout($layout) {
		if (!empty($layout)) {
			$this->layout = $layout;
		}
	}
	
	public function setTemplateFile($controller, $action) {
		$this->controller = $controller;
		$this->action = $action;
	}
	
	/**
	 * Get a plain text file's content, and set up variables for the inner usage
	 *
	 * @param string $file file's path
	 * @param array $variables variables used in file (in php blocks)
	 * @return string
	 */
	private static function getFileContent($file, $variables=NULL) {
		if (!is_file($file)) {
			return NULL;
		}
		
		if (is_array($variables)) {
			extract($variables);
		}
		
		if (!DEBUG_MODE) {
			ob_clean(); // Clean trash output, uncomment this for debug
		}
		ob_start();
		include $file;
		$out = ob_get_contents();
		ob_end_clean();
		
		return $out;
	}
	
}
?>