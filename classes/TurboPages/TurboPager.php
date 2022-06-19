<?php

namespace TurboPages;

class TurboPager {

    use \Cetera\DbConnection;

    private static function getItems() {

        $dirIDs = \TurboPages\Options::getDirIDs();
        // $str = '';
        // foreach ($dirIDs as $dirID) {
        //     $str .= $dirID . ', ';
        // }
        // throw new \Exception($str);

        $subMaterialIDs = [];

        foreach ($dirIDs as $dirID) {
            //file_put_contents(DOCROOT . 'log.txt', $dirID, FILE_APPEND);
            if ($dirID === 9594) {
                continue;
            }
            //$catalog = new \TurboPages\Catalog($dirID);
            $catalog = \Cetara\Catalog::getbyID($dirID);
            $subMaterialIDs = [...$subMaterialIDs, ...\TurboPages\Catalog::getSubMaterialIDs($catalog)];
        }

        $materialIDs = \TurboPages\Options::getMaterialIDs();

        $excludedIDs = array_unique([...$subMaterialIDs, ...$materialIDs]);

        $idRoot = 1;
        $root = new \TurboPages\Catalog($idRoot);
        $allIDs = $root->getSubMaterialIDs();

        $ids = array_diff($allIDs, $excludedIDs);

        $items = [];
        $counter = 0;
        // $ex = [];
        // $ex[] = 31;
        // $ex[] = 32;
        foreach ($ids as $id) {
            // if (in_array($id, $ex)) {
            //     continue;
            // }
            $items[] = new \TurboPages\Item($id);
        }
        
        return $items;
    }

    private static function toString($items) {
        $cb = function ($acc, $item) {
            $acc .= $item->toString();
            return $acc;
        };
        $result = array_reduce($items, $cb, '');
        return $result;
    }

    private static function save($items) {
        $prefix = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL . 
        '<rss xmlns:yandex="http://news.yandex.ru" xmlns:media="http://search.yahoo.com/mrss/" xmlns:turbo="http://turbo.yandex.ru" version="2.0">' . PHP_EOL . 
        '<channel>' . PHP_EOL;
        $postfix = '</channel></rss>';

        $content = self::toString($items);
        $filename = \TurboPages\Options::getFilename();
        file_put_contents(DOCROOT . $filename, $prefix . $content . $postfix);
    }

    public static function export() {
        $items = self::getItems();
        self::save($items);
    }
}

?>