<?php
/**
 * Loads every block file in inc/blocks/ so each block can register itself
 * independently. To add a new block, drop a new file in inc/blocks/ — no
 * other file needs to change.
 *
 * @package MeatScience
 */

defined( 'ABSPATH' ) || exit;

foreach ( glob( __DIR__ . '/blocks/*.php' ) as $meat_science_block_file ) {
	require_once $meat_science_block_file;
}
unset( $meat_science_block_file );
