<?php

class AdminModule extends CWebModule {

    private $_assetsUrl;

    public function init() {
        // this method is called when the module is being created
        // you may place code here to customize the module or the application
        // import the module-level models and components
        $this->setImport(array(
            'admin.models.*',
            'admin.components.*',
        ));

        $this->layout = 'main';
    }

    /**
     * 
     * @param CController $controller
     * @param CAction $action
     * @return boolean
     */
    public function beforeControllerAction($controller, $action) {
        if (parent::beforeControllerAction($controller, $action)) {
            // this method is called before any module controller action is performed
            // you may place customized code here
            $controller->layout = 'main';
            return true;
        }
        else
            return false;
    }
    
	/**
	 * @return string the base URL that contains all published asset files of gii.
	 */
	public function getAssetsUrl()
	{
		if($this->_assetsUrl===null)
			$this->_assetsUrl=Yii::app()->getAssetManager()->publish(dirname(__FILE__) . '/assets');
		return $this->_assetsUrl;
	}

}
