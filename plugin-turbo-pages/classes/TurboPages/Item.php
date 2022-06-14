<?php

namespace TurboPages;

class Item {
    
    use \Cetera\DbConnection;

    private $link;
    private $picture;
    private $title;
    private $content;
    private $fields;

    public function __construct($id) {

        try {
            $material = \TurboPages\Material::getByID($id);
        } catch (\Throwable $th) {
            throw new \Exception($id);
        }

        $this->link = $material['link'];
        $this->picture = $material['path'];
        $this->title = $material['name'];
        $this->content = $material['text'];
        $this->fields = $material;
    }

    private static function tag($tag, $inner, $attr = false) {
        $arr = [];
        $arr[] = '<';
        $arr[] = $attr ? $tag . ' ' . $attr : $tag;
        $arr[] = '>';
        $arr[] = $inner;
        $arr[] = '</';
        $arr[] = $tag;
        $arr[] = '>';
        $arr[] = PHP_EOL;

        return implode('', $arr);
    }

    private static function link($link) {
        $re = '/^^(http|https):\/\/(.+?)(\/index|)$/';
        preg_match($re, $link, $matches);
        return 'https://' . $matches[2] . '/';
    }

    public function toString() {

        $arr = [];

        $arr[] = self::tag('link', self::link($this->link));
        $arr[] = self::tag('title', $this->title);

        //turbo content
        //TC header
        $header = [];
        $fields = [];
        // $header[] = self::tag('figure', $this->picture);
        $header[] = self::tag('h1', $this->title);
        $header = self::tag('header', implode('', $header));
        //TC-content
        $content = [];
        $content[] = PHP_EOL;
        $content[] = '<![CDATA[';
        $content[] = PHP_EOL;
        $content[] = $header;
        $content[] = PHP_EOL;
        $content[] = $this->content;
        $content[] = PHP_EOL;
        $content[] = ']]>';
        $content[] = PHP_EOL;

        $arr[] = self::tag('turbo:content', implode('', $content));

        $item = implode('', $arr);

        return self::tag('item', $item, 'turbo="true"');
    }
}

?>