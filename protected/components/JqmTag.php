<?php

/**
 * Write Less - Do More :)
 *
 * @author Abie
 */
class JqmTag extends HtmlTag
{
    
    /**
     * 
     * @param type $content
     * @param type $url
     * @param array $attr
     * @return type
     */
    public static function buttonLink($content, $url = '#', $attr = array()) {
        $attr['href'] = CHtml::normalizeUrl($url);
        return self::jTag('a', 'button', $attr, $content);
    }
    
    public static function jSubmit($label, $attr = array()) {
        return self::jTag('input', null, $attr, null, false)->attr('type', 'submit')->attr('value', $label);
    }

    public static function jTag($tag, $role = null, $attributes = array(), $html = null, $close_tag = true) {
        if ($role) $attributes['data-role'] = $role;
        return new JqmTag($tag, $attributes, $html, $close_tag);
    }
    
    /**
     * 
     * @param array() $content
     * @return JqmTag
     */
    public static function listview($content = array()) {
        return self::jTag('ul', 'listview', null, $content);
    }

    /**
     * 
     * @param string $name
     * @param string $value
     * @return JqmTag
     */
    public function &data($name, $value) {
        //$this->attributes['data-' . $name] = $value;
        return $this->attr('data-' . $name, $value);
    }
    
    /**
     * 
     * @param boolean $inline
     * @return JqmTag
     */
    public function &inline($inline = true) {
        return $this->data('inline', $inline ? 'true' : 'false');
    }
    
    /**
     * 
     * @param type $inset
     * @return type
     */
    public function &inset($inset = true) {
        return $this->data('inset', $inset ? 'true' : 'false');
    }


    public function &role($role) {
        return $this->data('role', $role);
    }
    
    public function &theme($theme) {
        return $this->data('theme', $theme);
    }
    
    public function &icon($icon) {
        return $this->data('icon', $icon);
    }
    
    public function &iconPos($pos) {
        return $this->data('iconpos', $pos);
    }

    /**
     * 
     * @param type $content
     * @param type $attr
     * @return JqmTag
     */
    public function &appendLvItem($content = null, $attr = array()) {
        return $this->appendContent(self::jTag('li', NULL, $attr, $content));
    }
    
    /**
     * 
     * @param type $content
     * @param type $attr
     * @return JqmTag
     */
    public function &appendLvSep($content = null, $attr = array()) {
        return $this->appendContent(self::jTag('li', 'divider', $attr, $content));
    }


}

?>
