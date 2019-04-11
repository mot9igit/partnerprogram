<?php
/** @var xPDOTransport $transport */
/** @var array $options */
/** @var modX $modx */
if ($transport->xpdo) {
    $modx =& $transport->xpdo;

    $dev = MODX_BASE_PATH . 'Extras/partnerProgram/';
    /** @var xPDOCacheManager $cache */
    $cache = $modx->getCacheManager();
    if (file_exists($dev) && $cache) {
        if (!is_link($dev . 'assets/components/partnerprogram')) {
            $cache->deleteTree(
                $dev . 'assets/components/partnerprogram/',
                ['deleteTop' => true, 'skipDirs' => false, 'extensions' => []]
            );
            symlink(MODX_ASSETS_PATH . 'components/partnerprogram/', $dev . 'assets/components/partnerprogram');
        }
        if (!is_link($dev . 'core/components/partnerprogram')) {
            $cache->deleteTree(
                $dev . 'core/components/partnerprogram/',
                ['deleteTop' => true, 'skipDirs' => false, 'extensions' => []]
            );
            symlink(MODX_CORE_PATH . 'components/partnerprogram/', $dev . 'core/components/partnerprogram');
        }
    }
}

return true;