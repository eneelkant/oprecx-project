<?php

/**
 * @property string $assetsUrl
 */
class RegistrationModule extends CWebModule {

    /** @var string $_assetsUrl */
    var $_assetsUrl;

    public function init() {
        // this method is called when the module is being created
        // you may place code here to customize the module or the application
        // import the module-level models and components
        $this->setImport(array(
            'registration.models.*',
            'registration.components.*',
        ));

        //$this->layout = 'main';
    }

    public function beforeControllerAction($controller, $action) {
        if (parent::beforeControllerAction($controller, $action)) {
            // this method is called before any module controller action is performed
            // you may place customized code here
            $controller->layout = 'registration';
            return true;
        }
        else
            return false;
    }

    public function getAssetsUrl() {
        if ($this->_assetsUrl === null)
        //$this->_assetsUrl=O::app()->getAssetManager()->publish(dirname(__FILE__) . '/assets');
            $this->_assetsUrl = '/assets/register';
        return $this->_assetsUrl;
    }

}
