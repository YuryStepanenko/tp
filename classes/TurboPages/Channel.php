<?php

namespace TurboPages;

class Channel extends \TurboPages\Node {

    private $headers;
    private $excludedIDs;
    private $subMaterialIDs;

    public function __construct(int $id, Array $excludedIDs) {
        $catalog = \Cetera\Catalog::getByID($id);

        $this->headers = [];
        $this->excludedIDs = $excludedIDs;
        $this->subMaterialIDs = \TurboPages\Catalog::getSubMaterialIDs($catalog);
        
        $this->headers[] = new \TurboPages\Node('link', 'https://' . $catalog->fields['alias']);

        $this->headers[] = new \TurboPages\Node('title', $catalog->fields['meta_title']);

    }

    public function toString() {

        $nodeChannel = new \TurboPages\Node('channel');

        foreach ($this->headers as $header) {
            $nodeChannel->addChild($header);
        }

        foreach ($this->subMaterialIDs as $id) {
            if (!in_array($id, $this->excludedIDs)) {
                $nodeChannel->addChild(new \TurboPages\Item($id));
            }
        }

        return $nodeChannel->toString();
    }

}