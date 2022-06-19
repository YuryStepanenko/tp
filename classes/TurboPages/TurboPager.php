<?php

namespace TurboPages;

class TurboPager {

    use \Cetera\DbConnection;

    private static function getExcludedIDs() {

        $dirIDs = \TurboPages\Options::getDirIDs();

        $subMaterialIDs = [];

        foreach ($dirIDs as $dirID) {
            $catalog = \Cetera\Catalog::getByID($dirID);
            $subMaterialIDs = [...$subMaterialIDs, ...\TurboPages\Catalog::getSubMaterialIDs($catalog)];
        }

        $materialIDs = \TurboPages\Options::getMaterialIDs();

        return array_unique([...$subMaterialIDs, ...$materialIDs]);

    }

    private static function toString() {
        
        $result = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;

        $nodeChannels = new \TurboPages\Node('rss', [], ['xmlns:yandex' => 'http://news.yandex.ru',
                                                'xmlns:media' => 'http://search.yahoo.com/mrss/',
                                                'xmlns:turbo' => 'http://turbo.yandex.ru',
                                                'version' => '2.0']);

        $root = \Cetera\Catalog::getRoot();

        $servers = $root->getChildren();

        $excludedIDs = self::getExcludedIDs();

        foreach ($servers as $server) {
            $channel = new \TurboPages\Channel($server->id, $excludedIDs);
            $nodeChannels->addChild($channel);
        }

        return $result . $nodeChannels->toString();

    }

    public static function export() {

        $filename = \TurboPages\Options::getFilename();
        $content = self::toString();
        file_put_contents(DOCROOT . $filename, $content);
    }
}

?>