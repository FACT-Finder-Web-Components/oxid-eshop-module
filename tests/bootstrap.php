<?php

require dirname(__DIR__) . '/tests/Variant/Export/Data/ArticleCollectionVariant.php';
require dirname(__DIR__) . '/tests/Variant/Export/Stream/CsvVariant.php';
require dirname(__DIR__) . '/../../../source/bootstrap.php';

define('ABSPATH', dirname(__DIR__));
define('TEST_DIRECTORY', ABSPATH . '/tests');
define('TEST_DATA_DIRECTORY', TEST_DIRECTORY . '/data');
define('TEMP_DATA_DIRECTORY', ABSPATH . '/tmp_data');
