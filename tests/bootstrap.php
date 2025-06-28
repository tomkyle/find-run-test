<?php

/**
 * This file is part of tomkyle/find-run-test
 *
 * Find and run the PHPUnit test for a single changed PHP class file, most useful when watching the filesystem
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

$root_path = dirname(__DIR__);
$autoloader = $root_path.'/vendor/autoload.php';
if (!is_readable($autoloader)) {
    exit(sprintf("\nMissing Composer's Autoloader '%s'; Install Composer dependencies first.\n\n", $autoloader));
}
