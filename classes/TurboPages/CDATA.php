<?php

namespace TurboPages;

class CDATA {

    private $content;
    private $h1;
    private $figure;

    public function __construct(String $content, String $h1, String $figure = '') {
        
        $this->content = $content;
        $this->h1 = $h1;
        $this->figure = $figure;
    }

    public function toString() {
        
        $nodeHeader = new \TurboPages\Node('header');

        if (!empty($this->figure)) {
            $nodeFigure = new \TurboPages\Node('figure', $this->figure);
            $nodeHeader->addChild($nodeFigure);
        }

        $nodeH1 = new \TurboPages\Node('h1', $this->h1);
        $nodeHeader->addChild($nodeH1);
        
        $arr = [];
        $arr[] = '<![CDATA[';
        $arr[] = PHP_EOL;
        $arr[] = $nodeHeader->toString();
        $arr[] = PHP_EOL;
        $arr[] = $this->content;
        $arr[] = PHP_EOL;
        $arr[] = ']]>';

        $result = implode('', $arr);

        return $result;
    }
}