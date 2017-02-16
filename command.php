<?php
/**
 * Zips plugins and themes for delivery elsewhere.
 */

if ( ! class_exists( 'WP_CLI' ) ) {
	return;
}

WP_CLI::add_command( 'zip', 'Zip_Package' );

class Zip_Package {

	private $files 				= array();
	private $exclude_dir 	= array( '_scss', '_sass', 'scss', 'sass' );
	private $exclude_ext 	= array( 'scss', 'sass', 'git', 'gitignore', 'DS_Store' );
	private $package_name = '';
	private $destination 	= '';
	private $folder_to_zip = '';
	private $zip_name 		= '';
	private $type 				= 'plugin';
	private $overwrite 		= false;

	// Raw data
	private $args;
	private $assoc_args;

	public function __invoke( $args, $assoc_args ) {
		$this->args = $args;
		$this->assoc_args = $assoc_args;

		$this->set_type();
		$this->set_valid_name();
		$this->set_destination();

		// Zip the damn thing
		$this->zip_file();
	}

	private function set_type() {
		// Check for a valid option type
		$valid_types = array( 'plugin', 'theme' );
		$this->type = trim( strtolower( $this->args[0] ) );
		if ( ! in_array( $this->type, $valid_types ) ) {
			WP_CLI::error( $this->type . ' is an invalid option. Valid options are theme or plugin.' );
		}
	}

	private function set_valid_name() {
		// Check for provided name
		$this->package_name = $this->args[1];
		if ( empty( $this->package_name ) ) {
			WP_CLI::error( 'Please provide a valid ' . $this->type . ' name.' );
		}
		// Check that provided name exists
		$existing = $this->get_existing_of_type();
		if ( ! in_array( $this->package_name, array_values( $existing ) ) ) {
			WP_CLI::error( 'The ' . $this->package_name . ' ' . $this->type . ' does not exist.' );
		}
		WP_CLI::log( $this->package_name . ' is a valid ' . $this->type );
	}

	private function set_destination() {
		$tmp_dir = WP_CLI\Utils\get_temp_dir();
		$this->destination = ABSPATH . 'wp-content/';
		$this->folder_to_zip = $this->destination . $this->type . 's/' . $this->package_name;
		$this->zip_name = $this->package_name . '.zip';
	}

	private function get_existing_of_type() {
		$existing = WP_CLI::runcommand( $this->type . ' list --format=json --fields=name --quiet', array( 'return' => true, 'parse' => 'json' ) );
		$list = array();
		foreach ( $existing as $e ) {
			$list[] = $e['name'];
		}
		return $list;
	}

	private function exclude( $type ) {
		if ( 'directory' === $type ) {
			return $this->exclude_dir;
		}
		if ( 'extensions' === $type ) {
			return $this->exclude_ext;
		}
	}

	private function zip_file() {
		$source = $this->folder_to_zip . '/';
		$save_to = $this->destination . $this->zip_name;
		$zipped = $this->do_zip( $source, $save_to );
		if ( true == $zipped ) {
			WP_CLI::success( $source . ' zipped at ' . $save_to );
		} else {
			WP_CLI::error( 'Unable to zip' );
		}
	}

	function do_zip( $source, $destination ) {
	    if ( ! extension_loaded( 'zip' ) || ! file_exists( $source ) ) {
	        return false;
	    }

	    $zip = new ZipArchive();
	    if ( ! $zip->open( $destination, ZIPARCHIVE::CREATE ) ) {
	        return false;
	    }

	    $source = str_replace( '\\', '/', realpath( $source ) );

	    if ( true === is_dir( $source ) ) {
	        $files = new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $source ), RecursiveIteratorIterator::SELF_FIRST );

	        foreach ( $files as $file ) {
	            $file = str_replace( '\\', '/', $file );

							$file_name = substr( $file, strrpos( $file, '/' ) + 1 );

	            // Ignore "." and ".." folders
	            if ( in_array( $file_name, array( '.', '..' ) ) ) {
	                continue;
							}
	            $file = realpath( $file );

	            if ( true === is_dir( $file ) ) {
									$is_excluded = false;
									// Only include directories that aren't excluded
									$directory = basename( $file );
									if ( true === in_array( $directory, $this->exclude( 'directory' ) ) ) {
										WP_CLI::debug( $directory . ' was excluded because it was an excluded directory' );
										$is_excluded = true;
									}
									// Check parent directories
									$all_directories = explode( '/', $file );
									foreach ( $all_directories as $parent_dir ) {
										if ( true === in_array( $parent_dir, $this->exclude( 'directory' ) ) ) {
											WP_CLI::debug( $directory . ' was excluded because a parent folder was excluded' );
											$is_excluded = true;
										}
									}
									if ( false === $is_excluded ) {
										$zip->addEmptyDir( str_replace( $source . '/', '', $file . '/' ) );
									}
	            } elseif ( true === is_file( $file ) ) {
									$is_excluded = false;
									// Exclude file types
									$ext = pathinfo( $file, PATHINFO_EXTENSION );
									if ( true === in_array( $ext, $this->exclude( 'extensions' ) ) ) {
										WP_CLI::debug( $file . ' was excluded its extension was excluded' );
										$is_excluded = true;
									}
									if ( false === $is_excluded ) {
										$zip->addFromString( str_replace( $source . '/', '', $file ), file_get_contents( $file ) );
									}
	            }
	        }
	    } elseif ( true === is_file( $source ) ) {
					// If the plugin is just a file
	        $zip->addFromString( basename( $source ), file_get_contents( $source ) );
	    }

	    $zip->close();
		if ( file_exists( $destination ) ) {
			return true;
		} else {
			return false;
		}
	}
}
