<?php

opcache_invalidate(__FILE__);
opcache_reset();
if (function_exists('apcu_clear_cache')) {
    apcu_clear_cache();
}

phpinfo();
