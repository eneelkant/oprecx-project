<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of HTMLSimplifier
 *
 * @author abie
 */
class HTMLSimplifier
{
    public $unsafeAttribute = array('src', 'href');
    
    private $_allowedTags = array(
        'p' => array('align'), 
        'a' => array('href', 'title', 'target', 'rel'),
        'img' => array('src', 'width', 'height', 'title', 'alt'),
        'div', 'b', 'i', 'u', 'strong', 'em', 'blockquote', 'ul', 'ol', 'li', 'br'
    );
    
    private $_normalizedAllowedTags = NULL;
    
    public function setAllowedTags($allowedTags) {
        $this->_allowedTags = $allowedTags;
        $this->_normalizedAllowedTags = NULL;
    }
    
    public function getAllowedTags() {
        return $this->_allowedTags;
    }

    //public $removeTags = array('script', 'style');
    
    public function simplyfy($source) {
        return preg_replace_callback('/(<\/?)(\w+)(\s+[^>\/]*)?(\/?>)/m', array($this, '_preg_callback'), $source);
    }
    
    private static function normalizeAllowedTag($allowedTag) {
        $new = array();
        foreach ($allowedTag as $key => $value) {
            if (is_numeric($key)) {
                $new[$value] = array();
            } else {
                $new[$key] = array_flip($value);
            }
        }
        return $new;
    }

    /**
     * 
     * @param type $m
     * 
     */
    public function _preg_callback($m) {
        if (! $this->_normalizedAllowedTags)
            $this->_normalizedAllowedTags = self::normalizeAllowedTag ($this->_allowedTags);
        
        $tags =& $this->_normalizedAllowedTags;
        
        
        if (isset($tags[$m[2]])) {
            $allowedAttr = $tags[$m[2]];
            $html = $m[1] . $m[2];
            
            if ($attrs = explode(' ', $m[3])) {
                foreach ($attrs as $k => $attr) {
                    //$attr = trim($attr);
                    if (!$attr) continue;
                    
                    if (strpos('=', $attr)) {
                        list($attr_name, $attr_value) = explode('=', $attr);
                    } else {
                        $attr_name = $attr;
                        $attr_value = NULL;
                    }
                    
                    if (isset($allowedAttr[$attr_name])) {
                        if ($attr_name == 'href' || $attr_name == 'src') {
                            if ($attr_value && substr_compare($attr_value, 'javascript:', 1, 11, true) == 0) {
                                $attr_name = null;
                            }
                        }
                    } 
                    else $attr_name = null;
                    
                    if ($attr_name) {
                        $attrs[$k] = $attr_name . ($attr_value ? '=' . $attr_value : '');
                    } else {
                        unset($attrs[$k]);
                    }
                }
                
                $html .= implode(' ', $attrs);
            }
            
            return $html . $m[4];
        } else {
            return '';
        }
    }
}

?>
