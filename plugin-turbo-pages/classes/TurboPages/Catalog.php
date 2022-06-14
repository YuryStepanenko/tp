<?php

namespace TurboPages;

class Catalog extends \Cetera\Catalog {

    private $catalog;

    public function __construct($id = 1) {
        $this->catalog = self::getByID($id);
    }

    private function getLocalMaterials($catalog) {
        
        $materials = $catalog->getMaterials();

        $materialIDs = [];

        foreach ($materials as $material) {
            if ((int)$material->getType() === 1) {
                $materialIDs[] = $material->id;
            }
        }

        return $materialIDs;
    }

    public function getSubMaterialIDs($catalog = false) {
       
        if (!$catalog) {
            $catalog = $this->catalog;
        }

        if ($catalog->isLink() || $catalog->isHidden()) {
            return [];
        }

        $oSubCatalogs = $catalog->getChildren();

        if (count($oSubCatalogs) === 0 ) {

            return $this->getLocalMaterials($catalog);
        }

        $ids = [];

        foreach ($oSubCatalogs as $subCatalog) {
            $ids = [...$ids, ...$this->getSubMaterialIDs($subCatalog)];
        }

        $ids = [...$ids, ...$this->getLocalMaterials($catalog)];

        return $ids;
 
    }
}