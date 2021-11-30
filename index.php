<?php
require_once('whitelabeler.php');
$whitelabeler = new Whitelabeler;

if (isset($_GET['q'])) {

	/*
	|--------------------------------------------------------------------------
	| Determine Mautic version by path
	|--------------------------------------------------------------------------
	*/
	if ( $_GET['q'] == 'version' ) {
		header('Content-Type: application/json');
		if ( isset($_GET['path']) && is_dir($_GET['path']) ) {
			echo json_encode(
				$whitelabeler->mauticVersion($_GET['path'])
			);
		} else {
			echo json_encode(array(
				'status' => 0,
				'message' => 'Directory does not exist.'
			));
		}
		exit();

	/*
	|--------------------------------------------------------------------------
	| Find Mautic by URL (look for LICENSE.txt to verify this is Mautic root)
	|--------------------------------------------------------------------------
	*/
	} elseif ( $_GET['q'] == 'url' && isset($_GET['url']) ) {
		header('Content-Type: application/json');
        echo json_encode(
            $whitelabeler->findMauticUrl($_GET['url'])
        );
		exit();

	/*
	|--------------------------------------------------------------------------
	| Check for image in assets
	|--------------------------------------------------------------------------
	*/
	} elseif ( $_GET['q'] == 'asset' && isset($_GET['url']) ) {
        echo $whitelabeler->assetExists(urldecode($_GET['url']));
        exit();

	/*
	|--------------------------------------------------------------------------
	| Save Images
	|--------------------------------------------------------------------------
	*/
	} elseif ( $_GET['q'] == 'save-images' && $_SERVER['REQUEST_METHOD'] == 'POST' ) {

		require_once('vendor/chrisjean/php-ico/class-php-ico.php');

		$errors = array();
		$result = array();

		// sidebar logo
		if ( isset($_FILES['sidebar_logo_file']) ) {
			if (
				$_FILES['sidebar_logo_file']['type'] == 'image/png' ||
				$_FILES['sidebar_logo_file']['type'] == 'image/jpg' ||
				$_FILES['sidebar_logo_file']['type'] == 'image/jpeg' ||
				$_FILES['sidebar_logo_file']['type'] == 'image/gif'
			) {
				$whitelabeler->imageResize(
					$_POST['sidebar_logo_width'],
					$_FILES['sidebar_logo_file']['tmp_name'],
					__DIR__.'/assets/'.$_FILES['sidebar_logo_file']['name']
				);

				if ( !file_exists(__DIR__.'/assets/'.$_FILES['sidebar_logo_file']['name']) ) {
					$errors[] = 'Error uploading sidebar logo file '.$_FILES['sidebar_logo_file']['name'];
				} else {
					$result['images']['sidebar_logo'] = $_FILES['sidebar_logo_file']['name'];
				}
			} else {
				$errors[] = 'Invalid file type provided for sidebar logo.';
			}
		}

		// login logo
		if ( isset($_FILES['login_logo_file']) ) {
			if (
				$_FILES['login_logo_file']['type'] == 'image/png' ||
				$_FILES['login_logo_file']['type'] == 'image/jpg' ||
				$_FILES['login_logo_file']['type'] == 'image/jpeg' ||
				$_FILES['login_logo_file']['type'] == 'image/gif'
			) {
				$whitelabeler->imageResize(
					$_POST['login_logo_width'],
					$_FILES['login_logo_file']['tmp_name'],
					__DIR__.'/assets/'.$_FILES['login_logo_file']['name']
				);

				if ( !file_exists(__DIR__.'/assets/'.$_FILES['login_logo_file']['name']) ) {
					$errors[] = 'Error uploading login logo file '.$_FILES['login_logo_file']['name'];
				} else {
					$result['images']['login_logo'] = $_FILES['login_logo_file']['name'];
				}

				// If favicon file is not set, we'll use the login logo
				if ( !isset($_FILES['favicon_file']) && !isset($_POST['saved_favicon']) ) {
					$logo_filename_explode = explode('.', $_FILES['login_logo_file']['name']);
					$ico_lib = new PHP_ICO($_FILES['login_logo_file']['tmp_name'],  array( array( 64, 64 ) ) );
					$ico_lib->save_ico(__DIR__.'/assets/favicon-'.$logo_filename_explode[0].'.ico');

					if ( !file_exists(__DIR__.'/assets/favicon-'.$logo_filename_explode[0].'.ico') ) {
						$errors[] = 'Error using login logo file for favicon.';
					} else {
						$result['images']['favicon_files'] = 'favicon-'.$logo_filename_explode[0].'.ico';
					}
				}
			} else {
				$errors[] = 'Invalid file type provided for login logo.';
			}
		}

		// favicon
		if ( isset($_FILES['favicon_file']) ) {
			if (
				$_FILES['favicon_file']['type'] == 'image/png' ||
				$_FILES['favicon_file']['type'] == 'image/x-icon' ||
				$_FILES['favicon_file']['type'] == 'image/vnd.microsoft.icon' ||
				$_FILES['favicon_file']['type'] == 'image/jpg' ||
				$_FILES['favicon_file']['type'] == 'image/jpeg' ||
				$_FILES['favicon_file']['type'] == 'image/gif'
			) {
				// If favicon is .ico, move/copy the file
				if ($_FILES['favicon_file']['type'] == 'image/vnd.microsoft.icon' || $_FILES['favicon_file']['type'] == 'image/x-icon') {
					move_uploaded_file($_FILES['favicon_file']['tmp_name'], __DIR__.'/assets/'.$_FILES['favicon_file']['name']);

					if ( !file_exists(__DIR__.'/assets/'.$_FILES['favicon_file']['name']) ) {
						$errors[] = 'Error using login logo file for favicon.';
					} else {
						$result['images']['favicon'] = $_FILES['favicon_file']['name'];
					}
				// convert to .ico and save.
				} else {
					$logo_filename_explode = explode('.', $_FILES['favicon_file']['name']);

					$ico_lib = new PHP_ICO($_FILES['favicon_file']['tmp_name'],  array( array( 64, 64 ) ) );
					$ico_lib->save_ico(__DIR__.'/assets/'.$logo_filename_explode[0].'.ico');

					if ( !file_exists(__DIR__.'/assets/'.$logo_filename_explode[0].'.ico') ) {
						$errors[] = 'Error using login logo file for favicon.';
					} else {
						$result['images']['favicon'] = $logo_filename_explode[0].'.ico';
					}
				}
			} else {
				$errors[] = 'Invalid file type provided for favicon.';
			}
		}
		header('Content-Type: application/json');
		if ( !empty($errors) ) {
			echo json_encode( array('status' => 0, 'message' => $errors) );
		} else {
			echo json_encode( array('status' => 1, 'message' => $result) );
		}
		exit();

	/*
	|--------------------------------------------------------------------------
	| Save values entered
	|--------------------------------------------------------------------------
	*/
	} elseif ( $_GET['q'] == 'save' && $_SERVER['REQUEST_METHOD'] == 'POST' ) {
		// Encode the array into a JSON string.
		$encodedString = json_encode($_POST['config'], JSON_PRETTY_PRINT);
		// Save to JSON file in assets.
		if (file_put_contents(__DIR__.'/assets/config.json', $encodedString)) {
			header('Content-Type: application/json');
			echo json_encode( array('status' => 1, 'message' => 'Config values saved.') );
		};
		exit();

	/*
	|--------------------------------------------------------------------------
	| Save as a different filename
	|--------------------------------------------------------------------------
	*/
	} elseif ( $_GET['q'] == 'save-as' && $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['file']) ) {
		// Encode the array into a JSON string.
		$encodedString = json_encode($_POST['config'], JSON_PRETTY_PRINT);
		// Save to JSON file in assets.
		if (file_put_contents(__DIR__.'/assets/'.$_POST['file'].'.json', $encodedString)) {
			header('Content-Type: application/json');
			echo json_encode( array('status' => 1, 'message' => $_POST['file'].'.json saved in assets folder.') );
		};
		exit();

	/*
	|--------------------------------------------------------------------------
	| Get list of config files from assets directory
	|--------------------------------------------------------------------------
	*/
    } elseif ( $_GET['q'] == 'config-files' ) {
        header('Content-Type: application/json');
        echo json_encode($whitelabeler->getConfigFiles());
        exit();

	/*
	|--------------------------------------------------------------------------
	| Look for saved values and files to populate form automatically
	|--------------------------------------------------------------------------
	*/
	} elseif ( $_GET['q'] == 'saved' && isset($_GET['file']) ) {
		$config = $whitelabeler->loadJsonConfig($_GET['file']);

		if ( $config ) {
			header('Content-Type: application/json');
			echo json_encode(array('status' => 1, 'message' => 'config.json file found.', 'data' => $config));
		} else {
			header('Content-Type: application/json');
			echo json_encode(array('status' => 0, 'message' => 'config.json not found in assets folder.'));
		}
		exit();

	/*
	|--------------------------------------------------------------------------
	| Reset saved values
	|--------------------------------------------------------------------------
	*/
	} elseif ( $_GET['q'] == 'reset' && $_SERVER['REQUEST_METHOD'] == 'POST' ) {
		//Encode the array into a JSON string.
		$encodedString = json_encode($_POST['config'], JSON_PRETTY_PRINT);
		//Save to JSON file in assets.
		if (file_put_contents(__DIR__.'/assets/config.json', $encodedString)) {
			echo json_encode( array('status' => 1, 'message' => 'Config values saved.') );
		};
		exit();

	/*
	|--------------------------------------------------------------------------
	| Regenerate Assets / Clear Cache
	|--------------------------------------------------------------------------
	*/
	} elseif ( $_GET['q'] == 'assets' && isset($_GET['assets']) && isset($_GET['path']) ) {
		if ( substr($_GET['path'], -1) == '/' ) {
			$path = substr($_GET['path'], 0, -1);
		} else {
			$path = $_GET['path'];
		}
		if ( $_GET['assets'] == 'clear' ) {
			print_r($whitelabeler->clearMauticCache($path));
		} else if ( $_GET['assets'] == 'regenerate' ) {
			print_r($whitelabeler->rebuildAssets($path));
		}
		exit();

	/*
	|--------------------------------------------------------------------------
	| POST Logos
	|--------------------------------------------------------------------------
	*/
	} elseif ( $_GET['q'] == 'logos' ) {
		if ( isset($_POST['mautic_path']) && isset($_POST['mautic_url']) ) {
			if ( substr($_POST['mautic_path'], -1) == '/' ) {
				$path = substr($_POST['mautic_path'], 0, -1);
			} else {
				$path = $_POST['mautic_path'];
			}

			if ( substr($_POST['mautic_url'], -1) == '/' ) {
				$url = substr($_POST['mautic_url'], 0, -1);
			} else {
				$url = $_POST['mautic_url'];
			}

			$errors = array();

			if ( !isset($_FILES['sidebar_logo_file']) && !isset($_POST['sidebar_logo_file']) ) {
				$errors[] = 'Couldn\'t find file for the sidebar logo.';
			}

			if ( !isset($_FILES['login_logo_file']) && !isset($_POST['sidebar_logo_file']) ) {
				$errors[] = 'Couldn\'t find file for the login logo.';
			}

			if (empty($errors)) {
				// Use saved sidebar logo
				if ( !isset($_FILES['sidebar_logo_file']) && isset($_POST['sidebar_logo_file']) ) {
					$sidebar_logo = __DIR__.'/assets/'.$_POST['sidebar_logo_file'];
				// Use uploaded sidebar logo
				} elseif ( isset($_FILES['sidebar_logo_file']) ) {
					$sidebar_logo = $_FILES['sidebar_logo_file']['tmp_name'];
				}

				// Use saved login logo
				if ( !isset($_FILES['login_logo_file']) && isset($_POST['login_logo_file']) ) {
					$login_logo = __DIR__.'/assets/'.$_POST['login_logo_file'];
				// Use uploaded login logo
				} elseif ( isset($_FILES['login_logo_file']) ) {
					$login_logo = $_FILES['login_logo_file']['tmp_name'];
				}

				// Use saved favicon
				if ( !isset($_FILES['favicon_file']) && isset($_POST['favicon_file']) && $_POST['favicon_file'] != 'null' ) {
					$favicon = __DIR__.'/assets/'.$_POST['favicon_file'];
				// Use uploaded favicon
				} elseif ( isset($_FILES['favicon_file']) ) {
					$favicon = $_FILES['favicon_file']['tmp_name'];
				// Nothing is set -- we'll use the login logo as the favicon
				} else {
					$favicon = false;
				}

				$logos = $whitelabeler->replaceImages(
					$path,
					$url,
					$_POST['version'],
					$sidebar_logo,
					$_POST['sidebar_logo_width'],
					array(
						'top' => $_POST['sidebar_logo_margin_top'],
						'right' => $_POST['sidebar_logo_margin_right'],
						'left' => $_POST['sidebar_logo_margin_left']
					),
					$login_logo,
					$_POST['login_logo_width'],
					array(
						'top' => $_POST['login_logo_margin_top'],
						'bottom' => $_POST['login_logo_margin_bottom']
					),
					$favicon
				);

				header('Content-Type: application/json');
				echo json_encode($logos);

			} else {
				header('Content-Type: application/json');
				echo json_encode(array(
					'status' => 0,
					'message' => $errors
				));
			}
			exit();
		} else {
			header('Content-Type: application/json');
			echo json_encode(array(
				'status' => 0,
				'message' => 'Path or URL not set.'
			));
			exit();
		}

	/*
	|--------------------------------------------------------------------------
	| POST CSS Colors
	|--------------------------------------------------------------------------
	*/
	} elseif ( $_GET['q'] == 'css' &&  $_SERVER['REQUEST_METHOD'] == 'POST' ) {
		if (
			isset($_POST['path']) &&
			isset($_POST['version']) &&
			isset($_POST['logo_bg']) &&
			isset($_POST['primary']) &&
			isset($_POST['hover']) &&
			isset($_POST['sidebar_bg']) &&
			isset($_POST['sidebar_submenu_bg']) &&
			isset($_POST['sidebar_link']) &&
			isset($_POST['sidebar_link_hover']) &&
			isset($_POST['active_icon']) &&
			isset($_POST['divider_left']) &&
			isset($_POST['sidebar_divider']) &&
			isset($_POST['submenu_bullet_bg']) &&
			isset($_POST['submenu_bullet_shadow'])
		) {
			if ( substr($_POST['path'], -1) == '/' ) {
				$path = substr($_POST['path'], 0, -1);
			} else {
				$path = $_POST['path'];
			}
			$colors = $whitelabeler->colors(
				$path,
				$_POST['version'],
				$_POST['logo_bg'],
				$_POST['primary'],
				$_POST['hover'],
				$_POST['sidebar_bg'],
				$_POST['sidebar_submenu_bg'],
				$_POST['sidebar_link'],
				$_POST['sidebar_link_hover'],
				$_POST['active_icon'],
				$_POST['divider_left'],
				$_POST['sidebar_divider'],
				$_POST['submenu_bullet_bg'],
				$_POST['submenu_bullet_shadow']
			);

			header('Content-Type: application/json');
			echo json_encode($colors);

		} else {
			header('Content-Type: application/json');
			echo array(0, 'Missing CSS color field values.');
		}
		exit();

	/*
	|--------------------------------------------------------------------------
	| POST Company Name
	|--------------------------------------------------------------------------
	*/
	} elseif ( $_GET['q'] == 'companyname' &&  $_SERVER['REQUEST_METHOD'] == 'POST') {
		if ( substr($_POST['path'], -1) == '/' ) {
			$path = substr($_POST['path'], 0, -1);
		} else {
			$path = $_POST['path'];
		}
		$company_name = $whitelabeler->companyName(
			$path,
			$_POST['version'],
			$_POST['company_name'],
			$_POST['footer_prefix'],
			$_POST['footer']
		);
		header('Content-Type: application/json');
		echo json_encode($company_name);
		exit();
	}
}

require_once('view.php');
