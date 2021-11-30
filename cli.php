<?php
require_once('vendor/autoload.php');
require_once('whitelabeler.php');
$cli = new League\CLImate\CLImate;
$whitelabeler = new Whitelabeler;

if ( count($argv) > 1 ) {
    if ( $argv[1] == '--whitelabel' ) {
        if ( isset($argv[2]) ) {
	        $config_file = explode('=', $argv[2]);
	        if ( !empty($config_file) && $config_file[0] == '--config' ) {
		        
		        if ( file_exists($config_file[1]) ) {
			        $config = $whitelabeler->validateConfigValues($config_file[1]);
		        } else {
			        $cli->error('Config file not found.');
			        exit();
		        }
	        } else {
		    
	            if ( file_exists(__DIR__.'/assets/'.$argv[2]) ) {
	                $config = $whitelabeler->validateConfigValues($argv[2]);
	            } else {
	                $cli->error('Config file not found.');
	                exit();
	            }
	        }
        } else {
	        if ( file_exists(__DIR__.'/assets/config.json') ) {
            	$config = $whitelabeler->validateConfigValues();
            } else {
	            $cli->error('Config file not found.');
	            exit();
            }
        }

		if ( !empty($config['errors'] ) ) {
		    foreach( $config['errors'] as $error ) {
		        $cli->red($error);
		    }
		} else {
		    $cli->magenta('Whitelabeling...');

		    // Replace CSS colors
		    $cli->out('Updating colors...');
		    
		    $config = $config['config'];
		    
		    $version = $whitelabeler->mauticVersion($config['path']);

			$colors = $whitelabeler->colors(
				$config['path'],
				$version['version'],
				$config['logo_bg'], 
				$config['primary'], 
				$config['hover'],
				$config['sidebar_bg'],
				$config['sidebar_submenu_bg'],
				$config['sidebar_link'],
				$config['sidebar_link_hover'],
				$config['active_icon'],
				$config['divider_left'],
				$config['sidebar_divider'],
				$config['submenu_bullet_bg'],
				$config['submenu_bullet_shadow']
			);
			
			if ( $colors['status'] == 1 ) {
                $cli->green($colors['message']);
			} else {
				$cli->error($colors['message']);
				exit();
			}
			
			// Update company name in templates
			
			$cli->out('Updating company name in templates...');
			$company_name = $whitelabeler->companyName(
				$config['path'],
				$version['version'],
				$config['company'],
				$config['footer_prefix'],
				$config['footer']
			);
            	
			if ( $company_name['status'] == 1 ) {
				$cli->green($company_name['message']);
			} else {
				foreach( $company_name['message'] as $error ) {
					$cli->error($error);
				}
				exit();
			}
			
			// Update logo images
			
			$cli->out('Updating logo files...');
			
			if ( $config['favicon'] ) {
				$favicon = $config['favicon'];
			} else {
				$favicon = $config['login_logo'];
			}
			
			$logos = $whitelabeler->replaceImages(
				$config['path'],
				$config['url'],
				$version['version'],
				__DIR__.'/assets/'.$config['sidebar_logo'],
				$config['sidebar_logo_width'],
				array(
					'top' => $config['sidebar_logo_margin_top'],
					'right' => $config['sidebar_logo_margin_right'],
					'left' => $config['sidebar_logo_margin_left']
				),
				__DIR__.'/assets/'.$config['login_logo'],
				$config['login_logo_width'],
				array(
					'top' => $config['login_logo_margin_top'],
					'bottom' => $config['login_logo_margin_bottom']
				),
				__DIR__.'/assets/'.$favicon
			);
			
			if ( $logos['status'] == 1 ) {
				$cli->green($logos['message']);
			} else {
				$cli->error($logos['message']);
				exit();
			}
			
			// Clear Mautic cache and rebuild assets
			
            $cli->out('Clearing Mautic cache...');
            $clear_cache = $whitelabeler->clearMauticCache($config['path']);
			if ( $clear_cache['status'] == 1 ) {
                
                $cli->out('Rebuilding Mautic assets...');
                $whitelabeler->rebuildAssets($config['path']);
                $cli->green('Finished!');
                $cli->out('Make sure to clear your browser\'s cache if your Mautic styles aren\'t updated after a browser refresh!');
                
            } else {
                $cli->error($clear_cache['message']);
            }
		}

    } elseif ( $argv[1] == '--backup' ) {	  
        if ( $whitelabeler->mauticVersion(dirname(__DIR__, 1))['status'] == 1 ) {
            $mautic_path = dirname(__DIR__, 1);
            $backups_dir = __DIR__.'/backups';
            $cli->out('Backing up Mautic... ');
            	
            // Create backups folder if it doesn't exist
            if ( !file_exists($backups_dir) ) {
                mkdir($backups_dir, 0755, true);
            }
			
            // Get the name of the top directory that Mautic is in
            $mautic_dir_name = explode('/', $mautic_path);
            $mautic_dir_name = end($mautic_dir_name);
            
            $backup_name = $mautic_dir_name.'_backup_'.date('Y-m-d_H-i-s',time());
            
            echo shell_exec('cd '.$mautic_path.'; tar --exclude=./'.basename(__DIR__).' -zcvf '.$mautic_path.'/'.basename(__DIR__).'/backups/'.$backup_name.'.tgz .');
          
            if ( !file_exists($backups_dir.'/'.$backup_name.'.tgz') ) {
                $cli->red('There was a problem creating the backup.');
            } else {
                $cli->green('Backup complete!');
            }
              			
 		} else {
     		$cli->yellow('Mautic not found. Make sure the whitelabeler is placed in the Mautic root directory.');
 		}

    } elseif ( $argv[1] == '--restore' ) {
	    if ( !is_dir(__DIR__.'/backups') ) {
		    $cli->yellow('No backups found. Use "php cli.php --backup --path=/path/to/mautic" to backup a Mautic installation');
		    exit();
	    }
	    // Look for backups
	    $backups = array();
		foreach (new DirectoryIterator(__DIR__.'/backups') as $file) {
		    if ( $file->isDot() ) continue;
		    if ( $file->isFile() ) {
    		    $file_extension = explode('.', $file);
    		    $file_extension = end($file_extension);
				if ( $file_extension == 'tgz' ) {
		        	array_push($backups, $file->getFilename());	
				}
		    }
		}

	    if ( !empty($backups) ) {
		    
		    $input = $cli->radio('Select a backup to use:', $backups);
			$restore_backup = __DIR__.'/backups/'.$input->prompt();
			
	    } else {
		    $cli->yellow('No backups found. Use "php cli.php --backup --path=/path/to/mautic" to backup a Mautic installation');
		    exit();
	    }
	    
		if ( $whitelabeler->mauticVersion(dirname(__DIR__, 1))['status'] == 1 ) {
			$mautic_path = dirname(__DIR__, 1);
			$cli->out('Mautic found.');
		} else {
			$input = $cli->yellow('Couldn\'t automatically find your Mautic files in '. dirname(__DIR__, 1));
			$input = $cli->input('What\'s the absolute path to your Mautic files?');
			$mautic_path = $input->prompt();
			
			if ( $whitelabeler->mauticVersion($mautic_path)['status'] == 1 ) {
				$cli->green('Ok, found Mautic.');
			} else {
				$input = $cli->red('Couldn\'t find your Mautic files in '.$mautic_path);
				exit();
			}
		}
		
		// Do the restore
		
		$backup_name = explode('/', $restore_backup);
		$backup_name = end($backup_name);
		
		$mautic_dir_name = explode('/', $mautic_path);
		$mautic_dir_name = end($mautic_dir_name);

		$cli->out('Extracting backup files to Mautic directory...');			
		
		echo shell_exec('cd '.$mautic_path.'; tar --strip-components=1 -zxvf '.$mautic_path.'/'.basename(__DIR__).'/backups/'.$backup_name);
		
        $cli->out('Setting ownership of the Mautic directory to www-data:www-data user/group...');
        shell_exec('chown -R www-data:www-data '.$mautic_path);
        $cli->green('Restore complete!');
	
	} elseif ( $argv[1] == '--compare' ) {
		$errors = array();
        if ( isset($argv[2]) && substr($argv[2], 0, 7) == '--path1' ) {
            $mautic_path_1 = explode('=', $argv[2]);
            $mautic_path_1 = $mautic_path_1[1];
        } else {
            $errors[] = 'Define the path to Mautic installation 1.';
        }

        if ( isset($argv[3]) && substr($argv[3], 0, 7) == '--path2' ) {
            $mautic_path_2 = explode('=', $argv[3]);
            $mautic_path_2 = $mautic_path_2[1];  
        } else {
           $errors[] = 'Define the path to Mautic installation 2.';
        }
        
        if (!empty($errors)) {
        	    foreach($errors as $error) {
            	    $cli->error($error);
        	    }
            exit();    
        }
        
        if ( !file_exists($mautic_path_1.'/app/version.txt') ) {
            $errors[] = 'Not able to find Mautic at path 1.';
        }
        
        if ( !file_exists($mautic_path_2.'/app/version.txt') ) {
            $errors[] = 'Not able to find Mautic at path 2.';
        }
        
        if (empty($errors)) {   
            $cli->dump($whitelabeler->compareMauticVersions($mautic_path_1, $mautic_path_2));
    	    } else {
        	    foreach($errors as $error) {
            	    $cli->error($error);
        	    }
    	    }
    	}
   
} else {
	$cli->addArt(__DIR__.'/lib/ascii');
	$cli->magenta()->draw('title');
	echo PHP_EOL;
	$cli->out('This utility looks for configuration values in assets/config.json to use for whitelabeling Mautic. You can also use it to easily make backups of your Mautic files and restore your installation from these backups.');
	echo PHP_EOL;
	$cli->yellow('Command line options:');
	echo PHP_EOL;
	$cli->out('<bold>php cli.php --whitelabel');
	$cli->out('Looks for config.json in assets folder, validates JSON, and begins whitelabeling process.');
	echo PHP_EOL;
	$cli->out('<bold>php cli.php --whitelabel specific_file.json');
	$cli->out('Looks for specific_file.json in assets folder, validates JSON, and begins whitelabeling process.');
	echo PHP_EOL;
	$cli->out('<bold>php cli.php --whitelabel --config=/path/to/specific_file.json');
	$cli->out('Looks for JSON file at specific absolute path, validates JSON, and begins whitelabeling process.');
	echo PHP_EOL;
	$cli->out('<bold>php cli.php --backup</bold>');
	$cli->out('Creates a compressed backup of your Mautic files and saves it into the whitelabeler backup directory.');
	echo PHP_EOL;
	$cli->out('<bold>php cli.php --restore');
	$cli->out('Select a backup and extract files back into the Mautic directory.');
	echo PHP_EOL;
	$cli->out('<bold>php cli.php --compare --path1=/mautic1 --path2=/mautic2');
	$cli->out('Compare the template files between two versions of Mautic. This helps us determine what template files need to be updated in order for the whitelabeler to work with new versions of Mautic.');
	echo PHP_EOL;
}
