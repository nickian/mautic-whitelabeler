<?php
require_once('whitelabeler.php');
$whitelabeler = new Whitelabeler;

// Which Endpoint?
if (isset($_GET['q'])) {

	/*
	|--------------------------------------------------------------------------
	| Find Mautic version by path
	|--------------------------------------------------------------------------
	*/
	if ( $_GET['q'] == 'version' ) {
		if ( isset($_GET['path']) ) {
			if ( substr($_GET['path'], -1) == '/' ) {
				$path = substr($_GET['path'], 0, -1);
			} else {
				$path = $_GET['path'];
			}
			if (file_exists($path.'/app/version.txt')) {
				$file = fopen($path.'/app/version.txt', 'r') or die('Unable to open file!');
				$version = trim(fread($file , filesize($path.'/app/version.txt')));
				if (strpos($version, '-dev') !== false) {
					$version = explode('-', $version);
					$version = $version[0];
				}
				echo $whitelabeler->templateVersions($version);
			} else {
				echo 0;
			}
			exit();
		}

	/*
	|--------------------------------------------------------------------------
	| Find Mautic by URL (look for package.json to verify this is Mautic root)
	|--------------------------------------------------------------------------
	*/
	} elseif ( $_GET['q'] == 'url' && isset($_GET['url']) ) {
		$url = urldecode($_GET['url']);
		if ( substr($url, -1) == '/' ) {
			$url = substr($url, 0, -1);
		}
		$headers = get_headers($url.'/LICENSE.txt');
		if ( substr($headers[0], 9, 3) != 200) {
			echo 0;
		} else {
			$license = substr(file_get_contents($url.'/LICENSE.txt'), 0, 6);
			if ($license == 'Mautic') {
				echo 1;
			} else {
				echo 0;
			}
		}
		exit();

	/*
	|--------------------------------------------------------------------------
	| Clear Cache
	|--------------------------------------------------------------------------
	*/
	} elseif ( $_GET['q'] == 'assets' && isset($_GET['assets']) && isset($_GET['path']) ) {
		if ( substr($_GET['path'], -1) == '/' ) {
			$path = substr($_GET['path'], 0, -1);
		} else {
			$path = $_GET['path'];
		}
		if ( $_GET['assets'] == 'clear' ) {
			echo $whitelabeler->clearMauticCache($path);
		} else if ( $_GET['assets'] == 'regenerate' ) {
			echo $whitelabeler->rebuildAssets($path);
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
			if (!isset($_FILES['sidebar_logo'])) { $errors[] = 'Couldn\'t find file for the sidebar logo.'; }
			if (!isset($_FILES['login_logo'])) { $errors[] = 'Couldn\'t find file for the login logo.'; }
			if (empty($errors)) {
				// Use login logo as favicon
				if ( isset($_FILES['sidebar_logo']) && isset($_FILES['login_logo']) && !isset($_FILES['favicon']) ) {
					$favicon = $_FILES['login_logo'];
				// Separate favicon file
				} elseif ( isset($_FILES['sidebar_logo']) && isset($_FILES['login_logo']) && isset($_FILES['favicon']) ) {
					$favicon = $_FILES['favicon'];
				}
				$whitelabeler->replaceImages(
					$path,
					$url,
					$_POST['version'],
					$_FILES['sidebar_logo'],
					$_POST['sidebar_logo_width'],
					array(
						'top' => $_POST['sidebar_margin_top'],
						'right' => $_POST['sidebar_margin_right'],
						'left' => $_POST['sidebar_margin_left']
					),
					$_FILES['login_logo'],
					$_POST['login_logo_width'],
					array(
						'top' => $_POST['login_margin_top'],
						'bottom' => $_POST['login_margin_bottom']
					),
					$favicon
				);
			} else {
				foreach($errors as $error) {
					echo $error.PHP_EOL;
				}
			}
			exit();
		} else {
			echo 'Path or URL not set.';
			exit();
		}

	/*
	|--------------------------------------------------------------------------
	| POST CSS Colors
	|--------------------------------------------------------------------------
	*/
	} elseif ( $_GET['q'] == 'css' &&  $_SERVER['REQUEST_METHOD'] == 'POST' ) {
		if (
			!empty($_POST['path']) &&
			!empty($_POST['version']) &&
			!empty($_POST['sidebar_background']) &&
			!empty($_POST['mautic_primary']) &&
			!empty($_POST['mautic_hover'])
		) {
			if ( substr($_POST['path'], -1) == '/' ) {
				$path = substr($_POST['path'], 0, -1);
			} else {
				$path = $_POST['path'];
			}
			$colors = $whitelabeler->colors(
				$path,
				$_POST['version'],
				$_POST['sidebar_background'],
				$_POST['mautic_primary'],
				$_POST['mautic_hover']
			);
			echo $colors;
		} else {
			echo 'Missing CSS color field';
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
			$_POST['company_name']
		);
		echo $company_name;
		exit();
	}
}

// Load Whitelabeler form
require_once('view.php');
