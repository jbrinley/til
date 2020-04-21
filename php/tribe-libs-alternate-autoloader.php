<?php

/**
 * When developing a library that will be installed as a dependency in
 * another project using composer, it can be helpful to have a
 * development copy of the library checked out independently of composer.
 *
 * This function will update the composer autoloader in a Square One
 * project to load Tribe Libs from an alternate directory.
 *
 * Add the code to mu.local.php
 */

function alternate_tribe_libs_autoloader( $libs_path = '/application/tribe-libs' ) {
	$autoload_file = dirname( __DIR__, 2 ) . '/vendor/autoload.php';

	preg_match( '#return\s+(.*?)::getLoader#', file_get_contents( $autoload_file ), $matches );

	if ( empty( $matches[1] ) ) {
		return;
	}

	$loader_class = $matches[1];

	/** @var ClassLoader $loader */
	$loader = $loader_class::getLoader();
	foreach ( $loader->getPrefixesPsr4() as $prefix => $paths ) {
		if ( strpos( $prefix, 'Tribe\\Libs' ) === false ) {
			continue;
		}
		$loader->setPsr4( $prefix, array_map( static function ( $path ) use ( $libs_path ) {
			return preg_replace( '#^.*tribe-libs/src#', $libs_path . '/src', $path );
		}, $paths ) );
	}
	$loader->setPsr4( "Tribe\\Libs\\", $libs_path . '/src' );
}

alternate_tribe_libs_autoloader( '/application/tribe-libs' );
