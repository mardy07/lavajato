<?php

define("BASE_URL", $container['request']->getUri()->getBaseUrl() . '/');
define("PATH", $container['request']->getUri()->getPath());
define("BASE_PATH", rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/'));
define('PREFIX', 'pc_');
