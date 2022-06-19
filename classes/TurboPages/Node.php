<?php

namespace TurboPages;

class Node {

    private $tag;
    private $attrs;
    private $content;

    public function __construct(String $tag, $content = [], Array $attrs = []) {
        
        $this->tag = $tag;
        $this->attrs = $attrs;
        $this->content = $content;
    }

    public function addChild(\TurboPages\Node $node) {
        $this->content[] = $node;
    }

    public function toString() {

        $arr = [];

        //tag open
        $arr[] = '<';
        $arr[] = $this->tag;
        
        //attributes in tag
        foreach ($this->attrs as $key => $value) {
            $arr[] = " {$key}=\"{$value}\"";
        }
        $arr[] = empty($attrs) ? '' : " {$attributes}";
        $arr[] = '>';

        //inner content
        if (is_array($this->content)) {
            foreach ($this->content as $innerNode) {
                $arr[] = PHP_EOL . $innerNode->toString();
            }
            $arr[] = PHP_EOL;
        } else {
            $arr[] = $this->content;
        }

        //tag close
        $arr[] = "</{$this->tag}>";

        return implode('', $arr);
    }
}