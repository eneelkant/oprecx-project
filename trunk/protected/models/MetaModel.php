<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MetaModel
 *
 * @author abie
 */
abstract class MetaModel extends CModel
{
    protected $data = NULL;
    private $elmId = '';
    private $_modified;
    
    protected $metaNameAttrName = 'meta_name';
    protected $metaValueAttrName = 'meta_value';
    
    private $tableName, $elmAttrName, $cacheName;
    
    public function load($id, $class = __CLASS__){
        
    }
    
    abstract protected function tableName();
    abstract protected function elmAttrName();


    public function __construct($id)
    {
        $this->elmId = $id;
        $this->tableName = $this->tableName();
        $this->elmAttrName = $this->elmAttrName();
        $this->cacheName = $this->cacheName();
    }
    
    protected function init(){
        if ($this->data === NULL || FALSE == ($this->data = O::app()->cache->get($this->cacheName))) {
            $this->data = array();
        }
    }
    
    protected function setModified() {
        if (!$this->_modified) {
            O::app()->onEndRequest = array($this, 'saveCache');
            $this->_modified = true;
        }
    }
    
    protected function getFromTable($name) {
        return CDbCommandEx::create()
                ->select($this->metaValueAttrName)
                ->from($this->tableName)
                ->where("\${$this->elmAttrName} = :id AND \${$this->metaNameAttrName} = :name",
                        array('id' => $this->elmId, 'name' => $name))
                ->limit(1)
                ->queryScalar(); 
    }

    public function saveCache(){
        O::app()->getCache()->set($this->cacheName, $this->data);
    }

    public function __get($name)
    {
        return $this->getValue($name);
        //parent::__get($name);
    }
    
    public function __set($name, $value)
    {
        $this->setValue($name, $value);
    }
    
    public function getValue($name) {
        if (isset($this->data[$name]))
            return $this->data[$name];
        else 
            return $this->data[$name] = $this->getFromTable ($name);
    }
 
    
    public function setValue($name, $value) {
        // kalo ga ada di cache, hapus dulu aja
        if (!$value || !isset($this->data[$name]) || !$this->data[$name]) {
            try {
                CDbCommandEx::create()->delete(
                    $this->tableName , 
                    "\${$this->elmAttrName} = :id AND \${$this->metaNameAttrName} = :name",
                    array('id' => $this->elmId, 'name' => $name)
                );
                if (!$value) {
                    $this->data[$name] = NULL;
                    $this->setModified();
                }
            }
            catch (Exception $e) {}
        }
        if ($value) {
            if (isset($this->data[$name]) && isset($this->data[$name])) {
                try {
                    CDbCommandEx::create()->update(
                            $this->tableName , 
                            array($this->metaValueAttrName = $value),
                            "\${$this->elmAttrName} = :id AND \${$this->metaNameAttrName} = :name",
                            array('id' => $this->elmId, 'name' => $name)
                    );
                    $this->data[$name] = $value;
                    $this->setModified();
                }
                catch (Exception $e) {}
            } else {
                try {
                    CDbCommandEx::create()->insert($this->tableName , array(
                        $this->elmAttrName = $this->elmId,
                        $this->metaNameAttrName = $name,
                        $this->metaValueAttrName = $value,
                    ));
                    $this->data[$name] = $value;
                    $this->setModified();
                }
                catch (Exception $e) {}
            }
        }
    }


    protected function cacheName() {
        return 'oprecx:Meta:' . $this->tableName . ':' . $this->elmAttrName . '=' . $this->elmId;
    }

    public function attributeNames()
    {
        return array_keys($this->data);
    }
}

?>
