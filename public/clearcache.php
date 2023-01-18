<?php

opcache_reset();
if (function_exists('apcu_clear_cache')) {
    apcu_clear_cache();
}

include 'not-found.html';
