<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of JqmGrid
 *
 * @author abie
 */
class JqmGrid extends JqmTag
{
    public static function createGrid($tag = 'div') {
        return new JqmGrid($tag);
    }

    /**
     * 
     * @param string|array $content
     * @param array $attr
     * @return HtmlTag
     */
    public function &addColumn($content, $attr = array()) {
        $column = self::tag('div', $attr, $content)->appendClass('ui-block-' . chr(count($this->content) + 0x61));
        $this->appendContent($column);
        return $column;
    }

    /**
     * 
     * @param boolean $echo
     * @return string
     */
    public function render($echo = false)
    {
        $this->appendClass('ui-grid-' . chr(count($this->content) + 0x5f));
        return parent::render($echo);
    }
}

?>
