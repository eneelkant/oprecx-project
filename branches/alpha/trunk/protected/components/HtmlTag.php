<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of HtmlTag
 *
 * @author abie
 */
class HtmlTag
{
    /** @var string tag name */
    protected $tag;
    
    /** @var array attributes */
    protected $attributes;
    
    /** @var string|array attributes */
    protected $content;
    
    /** @var boolean is tag need close tag? */
    protected $closeTag;
    
    /**
     * 
     * @param type $content
     * @param type $url
     * @return HtmlTag
     */
    public static function link($content, $url = '#') {
        return self::tag(
                'a',
                array('href' => CHtml::normalizeUrl($url)),
                $content
        );
    }
   
    /**
     * 
     * @param type $tag
     * @param type $attributes
     * @param type $html
     * @param type $close_tag
     * @return HtmlTag
     */
    public static function tag($tag, $attributes = array(), $html = null, $close_tag = true) {
        return new HtmlTag($tag, $attributes, $html, $close_tag);
    }
    


    /**
     * 
     * @param string $tag
     * @param array $attributes
     * @param string|array $html
     * @param boolean $close_tag
     */
    public function __construct($tag, $attributes = array(), $html = null, $close_tag = true)
    {
        $this->tag = $tag;
        $this->attributes = $attributes ? $attributes : array();
        $this->content = $html ? $html : array();
        $this->closeTag = $close_tag;
    }
    
    /**
     * 
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }
    
    /**
     * 
     * @param string $name
     * @param string $value
     * @return JqmTag
     */
    public function &id($id) {
        return $this->attr('id', $id);
    }
    
    public function &attr($name, $value) {
        $this->attributes[$name] = $value;
        return $this;
    }


    public function &appendClass($class) {
        if (is_array($class)) $class = implode (' ', $class);
        
        if (isset($this->attributes['class']))
            $this->attributes['class'] .= ' ' . $class;
        else 
            $this->attributes['class'] = $class;
        
        return $this;
    }


    /**
     * 
     * @param string|JqmTag $content
     * @return JqmTag Description
     */
    public function &appendContent($content) {
        if ($this->closeTag) {
            if (!is_array($this->content)) {
                $this->content = array($this->content, $content);
            } else {
                $this->content[] = $content;
            }
        }
        return $this;
    }
    
    public function &appendTag($tag, $attributes = array(), $html = null, $close_tag = true) {
        return $this->appendContent(self::tag($tag, $attributes, $html, $close_tag));
    }
    
    public function &appendLi($content, $attr = array ()) {
        return $this->appendTag('li', $attr, $content);
    }


    /**
     * 
     * @param boolean $echo
     * @return string|null Description
     */
    public function render($echo = false) {
        $html = CHtml::tag($this->tag, $this->attributes, 
                $this->closeTag ? self::normalize($this->content) : NULL, 
                $this->closeTag);
        
        if ($echo) echo $html;
        else return $html;
    }
    
    /**
     * 
     * @param JqmTag|array|string $content
     * @return string
     */
    public static function normalize($content) {
        if (is_array($content)) {
            $html = array();
            $attr = array();
            foreach ($content as $k => $v) {
                if (is_numeric($k))
                    $html[] = self::normalize ($v);
                else 
                    $attr[$k] = $v;
            }
            $content = implode('', $html);
            if (isset($attr['tag'])) {
                $tag = $attr['tag'];
                if (isset($attr['closeTag'])) {
                    $closeTag = $attr['closeTag'];
                    unset($attr['closeTag']);
                } else
                    $closeTag = true;
                
                unset($attr['tag']);
                return self::tag($tag, $attr, $content, $closeTag)->render();
            }
            return $content;
        } elseif (is_a($content, 'JqmTag')) {
            return $content->render();
        } else {
            return $content;
        }
    }
}

?>
