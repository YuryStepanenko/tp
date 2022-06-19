<?php

namespace TurboPages;

class Item extends \TurboPages\Node {
    
    use \Cetera\DbConnection;

    private $link;
    private $title;
    private $content;
    private $fields;

    public function __construct(int $id) {

        try {
            $material = \TurboPages\Material::getByID($id);
        } catch (\Throwable $th) {
            throw new \Exception($id);
        }

        $this->link = $material['link'];
        $this->title = $material['name'];
        $this->content = $material['text'];
        $this->fields = $material;
    }

    public function toString() {
        
        $node = new \TurboPages\Node('item', [], ['turbo' => 'true']);

        $link = new \TurboPages\Node('link', self::relink($this->link));
        $node->addChild($link);

        $title = new \TurboPages\Node('title', $this->title);
        $node->addChild($title);

        $cdata = new \TurboPages\CDATA($this->content, $this->title);
        $turbocontent = new \TurboPages\Node('turbo:content', $cdata->toString());
        $node->addChild($turbocontent);

        return $node->toString();
    }

    private static function relink(String $link) {
        $re = '/^^(http|https):\/\/(.+?)(\/index|)$/';
        preg_match($re, $link, $matches);
        return 'https://' . $matches[2] . '/';
    }

}

?>