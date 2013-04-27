<?php

class AdminModule extends CWebModule {

    private $_assetsUrl;

    public function init() {
        if (Yii::app()->user->isGuest) {
            $url = Yii::app()->getUser()->loginUrl;
            $url['nexturl'] = Yii::app()->getRequest()->requestUri;
            Yii::app()->getRequest()->redirect(CHtml::normalizeUrl($url));
        }
        
        // this method is called when the module is being created
        // you may place code here to customize the module or the application
        // import the module-level models and components
        $this->setImport(array(
            'admin.models.*',
            'admin.components.*',
        ));

        $this->layout = 'main';
        
        O::app()->clientScript->scriptMap['jquery.js'] = '/js/jquery-1.9.1.min.js';
        O::app()->clientScript->scriptMap['jquery-ui.js'] = '/js/ui/jquery-ui.js';
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
            //$controller->layout = 'standard';
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
