<?php
namespace Larabase\Support;

use File;

class Helper
{
    /**
     * Load helpers from a directory
     * @param string $directory
     * @since 2.0
     */
    public static function autoload(string $directory): void
    {
        $helpers = File::glob($directory . '/*.php');
        foreach ($helpers as $helper) {
            File::requireOnce($helper);
        }
    }
}
