<?php
class Whitelabeler {
	/*
	|--------------------------------------------------------------------------
	| Replace Colors in Stylesheets
	|--------------------------------------------------------------------------
	*/
	public function colors($path, $version, $sidebar_background, $mautic_primary, $mautic_hover) {
		if(substr($version, 0, 3) == 2.5 || substr($version, 0, 3) == 2.6) { $version = substr($version, 0, 3); }
		// Replace app.css contents with template and new colors
		$app_css = $path.'/app/bundles/CoreBundle/Assets/css/app.css';
		if (file_exists($app_css)) {
			$app_css_template = file_get_contents('templates/'.$version.'/app/bundles/CoreBundle/Assets/css/app.css');
			$app_css_new = str_replace(
				// Look for these template tags.
				array('{{sidebar_background}}', '{{mautic_primary}}', '{{mautic_hover}}'),
				// Replace template tags with new colors.
				array($sidebar_background, $mautic_primary, $mautic_hover),
				$app_css_template
			);
			$file = fopen($app_css, "w");
			fwrite($file, $app_css_new);
			fclose($file);
		} else {
			return 'Unable to find app.css in your Mautic installation.';
		}
		// Replace libraries.css contents with template and new colors
		$libraries_css = $path.'/app/bundles/CoreBundle/Assets/css/libraries/libraries.css';
		if (file_exists($libraries_css)) {
			$libraries_css_template = file_get_contents('templates/'.$version.'/app/bundles/CoreBundle/Assets/css/libraries/libraries.css');
			$libraries_css_new = str_replace(
				// Look for these template tags.
				array('{{sidebar_background}}','{{mautic_primary}}','{{mautic_hover}}'),
				// Replace template tags with new colors.
				array($sidebar_background, $mautic_primary, $mautic_hover),
				$libraries_css_template
			);
			$file = fopen($libraries_css, "w");
			fwrite($file, $libraries_css_new);
			fclose($file);
			return 'CSS files updated with new colors.';
		} else {
			return 'Unable to find libraries.css in your Mautic installation.';
		}
	}

	/*
	|--------------------------------------------------------------------------
	| Replace "Mautic" with Comapny Name
	|--------------------------------------------------------------------------
	*/
	public function companyName($path, $version, $company_name) {
		if(substr($version, 0, 3) == 2.5 || substr($version, 0, 3) == 2.6 ) { $version = substr($version, 0, 3); }

		$base_copyright = '/app/bundles/CoreBundle/Views/Default/base.html.php';
		$head_title = '/app/bundles/CoreBundle/Views/Default/head.html.php';

		$content_versions = array(
			'2.6',
			'2.7.0',
			'2.7.1',
			'2.8.0',
			'2.8.1',
			'2.9.0',
			'2.9.1',
			'2.9.2',
			'2.10.0',
			'2.10.1',
			'2.11.0',
			'2.12.0',
			'2.12.1',
			'2.12.2',
			'2.13.1',
			'2.14.0'
		);

		if ( in_array($version, $content_versions) ) {
			$js = '1a.content.js';
		} else {
			$js = '1.core.js';
		}

		$core_js = '/app/bundles/CoreBundle/Assets/js/'.$js;
		$left_panel = '/app/bundles/CoreBundle/Views/LeftPanel/index.html.php';
		$login_page = '/app/bundles/UserBundle/Views/Security/base.html.php';

		if (file_exists($path.$base_copyright)) {
			// get template
			$base_copyright_template = file_get_contents('templates/'.$version.$base_copyright);
			// fill template tags
			$base_copyright_new = str_replace('{{company_name}}', $company_name, $base_copyright_template);
			// Replace Mautic file
			$file = fopen($path.$base_copyright, "w");
			fwrite($file, $base_copyright_new);
			fclose($file);
		} else {
			return 'Couldn\'t find base.html.php to update.';
			exit();
		}

		if (file_exists($path.$head_title)) {
			// get template
			$head_title_template = file_get_contents('templates/'.$version.$head_title);
			// fill template tags
			$head_title_new = str_replace('{{company_name}}', $company_name, $head_title_template);
			// Replace Mautic file
			$file = fopen($path.$head_title, "w");
			fwrite($file, $head_title_new);
			fclose($file);
		} else {
			return 'Couldn\'t find head.html.php to update.';
			exit();
		}

		if (file_exists($path.$login_page)) {
			// get template
			$login_page_template = file_get_contents('templates/'.$version.$login_page);
			// fill template tags
			$login_page_new = str_replace('{{company_name}}', $company_name, $login_page_template);
			// Replace Mautic file
			$file = fopen($path.$login_page, "w");
			fwrite($file, $login_page_new);
			fclose($file);
		} else {
			return 'Couldn\'t find login page base.html.php to update.';
			exit();
		}

		if (file_exists($path.$core_js)) {
			// get template
			$core_js_template = file_get_contents('templates/'.$version.$core_js);
			// fill template tags
			$core_js_new = str_replace('{{company_name}}', $company_name, $core_js_template);
			// Replace Mautic file
			$file = fopen($path.$core_js, "w");
			fwrite($file, $core_js_new);
			fclose($file);
		} else {
			return 'Couldn\'t find core javascript file to update.';
			exit();
		}

		return 'Updated company name.';
	}

	/*
	|--------------------------------------------------------------------------
	| Resize and Save Image file
	|--------------------------------------------------------------------------
	*/
	public function imageResize($new_width, $original, $target) {
	    $info = getimagesize($original);
	    if ($info['mime'] == 'image/png') {
	    	try {
				$im = imagecreatefrompng($original);
			} catch(Exception $e)  {
				echo 'Error with image: ',  $e->getMessage(), "\n";
				exit();
			}
			$srcWidth = imagesx($im);
			$srcHeight = imagesy($im);
			$nWidth = $new_width;
			$nHeight = ($srcHeight / $srcWidth) * $nWidth;
			$newImg = imagecreatetruecolor($nWidth, $nHeight);
			imagealphablending($newImg, false);
			imagesavealpha($newImg,true);
			$transparent = imagecolorallocatealpha($newImg, 255, 255, 255, 127);
			imagefilledrectangle($newImg, 0, 0, $nWidth, $nHeight, $transparent);
			imagecopyresampled($newImg, $im, 0, 0, 0, 0, $nWidth, $nHeight, $srcWidth, $srcHeight);
			$target = $target;
			imagepng($newImg, $target);
			return $target;
	    } else {
	    	if ($info['mime'] == 'image/jpeg') {
	            $image_create_func = 'imagecreatefromjpeg';
	            $image_save_func = 'imagejpeg';
	    	} elseif($info['mime'] == 'image/gif') {
	            $image_create_func = 'imagecreatefromgif';
	            $image_save_func = 'imagegif';
	    	} else {
	    		echo 'Unknown image format';
	    		exit();
	    	}
		    $img = $image_create_func($original);
		    list($width, $height) = getimagesize($original);
		    $newHeight = ($height / $width) * $new_width;
		    $tmp = imagecreatetruecolor($new_width, $newHeight);
		    imagecopyresampled($tmp, $img, 0, 0, 0, 0, $new_width, $newHeight, $width, $height);
		    $image_save_func($tmp, "$target");
		    return $target;
	    }
	}

	/*
	|--------------------------------------------------------------------------
	| Process and replace images
	|--------------------------------------------------------------------------
	*/
	public function replaceImages($path, $url, $version, $sidebar_image, $sidebar_width, $sidebar_margin, $login_image, $login_width, $login_margin, $favicon_image) {
		$media_images = $path.'/media/images';
		if(substr($version, 0, 3) == 2.5 || substr($version, 0, 3) == 2.6) { $version = substr($version, 0, 3); }
		// Apple Touch Icon
		$this->imageResize(192, $login_image['tmp_name'], $media_images.'/apple-touch-icon.png');
		// mautic_logo_db64.png
		$this->imageResize(64, $login_image['tmp_name'], $media_images.'/mautic_logo_db64.png');
		// mautic_logo_db200.png
		$this->imageResize(200, $login_image['tmp_name'], $media_images.'/mautic_logo_db200.png');
		// mautic_logo_lb200.png
		$this->imageResize(200, $login_image['tmp_name'], $media_images.'/mautic_logo_lb200.png');

		require_once('lib/php-ico/class-php-ico.php' );
		// If favicon is .ico, move/copy the file
		if ($favicon_image['type'] == 'image/vnd.microsoft.icon' || $favicon_image['type'] == 'image/x-icon') {
			move_uploaded_file($favicon_image["tmp_name"], $path.'/favicon.ico');
			copy($path.'/favicon.ico', $media_images.'/favicon.ico');
		// convert to .ico and save.
		} else {
			$ico_lib = new PHP_ICO($favicon_image['tmp_name'],  array( array( 64, 64 ) ) );
			$ico_lib->save_ico($path.'/favicon.ico');
			$ico_lib->save_ico($media_images.'/favicon.ico');
		}
		// Update sidebar logo
		$this->imageResize(250, $sidebar_image['tmp_name'], $media_images.'/sidebar_logo.png');
		$left_panel = $path.'/app/bundles/CoreBundle/Views/LeftPanel/index.html.php';
		if (file_exists($left_panel)) {
			$left_panel_template = file_get_contents('templates/'.$version.'/app/bundles/CoreBundle/Views/LeftPanel/index.html.php');
			$left_panel_new = str_replace(
				// Look for these template tags.
				array('{{sidebar_image}}', '{{sidebar_width}}', '{{margin_top}}','{{margin_right}}', '{{margin_left}}'),
				// Replace template tags with values.
				array($url.'/media/images/sidebar_logo.png', $sidebar_width, $sidebar_margin['top'], $sidebar_margin['right'], $sidebar_margin['left']),
				$left_panel_template
			);
			$file = fopen($left_panel, "w");
			fwrite($file, $left_panel_new);
			fclose($file);
		} else {
			return $left_panel.' NOT FOUND.';
		}
		// Update login logo
		$this->imageResize(400, $login_image['tmp_name'], $media_images.'/login_logo.png');
		$login_page = $path.'/app/bundles/UserBundle/Views/Security/base.html.php';
		if (file_exists($login_page)) {
			$login_page_template = file_get_contents($login_page);
			$login_page_new = str_replace(
				// Look for these template tags.
				array('{{login_logo}}', '{{login_logo_width}}', '{{login_logo_margin_top}}', '{{login_logo_margin_bottom}}'),
				// Replace template tags with values.
				array($url.'/media/images/login_logo.png', $login_width, $login_margin['top'], $login_margin['bottom']),
				$login_page_template
			);
			$file = fopen($login_page, "w");
			fwrite($file, $login_page_new);
			fclose($file);
		} else {
			return $login_page.' NOT FOUND.';
		}
	}

	/*
	|--------------------------------------------------------------------------
	| Used to run Mautic console commands (taken from upgrade.php)
	|--------------------------------------------------------------------------
	*/
	public function runSymfonyCommand($path, $command, array $args) {
	    static $application;
	    require_once $path.'/app/autoload.php';
	    require_once $path.'/app/AppKernel.php';
	    $args = array_merge(
	        ['console', $command],
	        $args
	    );
	    if (null == $application) {
	        $kernel      = new \AppKernel('prod', true);
	        $application = new \Symfony\Bundle\FrameworkBundle\Console\Application($kernel);
	        $application->setAutoExit(false);
	    }
	    $input    = new \Symfony\Component\Console\Input\ArgvInput($args);
	    $output   = new \Symfony\Component\Console\Output\NullOutput();
	    $exitCode = $application->run($input, $output);
	    unset($input, $output);
	    return $exitCode === 0;
	}
	/*
	|--------------------------------------------------------------------------
	| Used to clear cache (taken from upgrade.php)
	|--------------------------------------------------------------------------
	*/
	public function recursiveRemoveDirectory($directory) {
	    // if the path has a slash at the end we remove it here
	    if (substr($directory, -1) == '/') {
	        $directory = substr($directory, 0, -1);
	    }
	    // if the path is not valid or is not a directory ...
	    if (!file_exists($directory)) {
	        return true;
	    } elseif (!is_dir($directory)) {
	        return false;
	        // ... if the path is not readable
	    } elseif (!is_readable($directory)) {
	        // ... we return false and exit the function
	        return false;
	        // ... else if the path is readable
	    } else {
	        // we open the directory
	        $handle = opendir($directory);
	        // and scan through the items inside
	        while (false !== ($item = readdir($handle))) {
	            // if the filepointer is not the current directory
	            // or the parent directory
	            if ($item != '.' && $item != '..') {
	                // we build the new path to delete
	                $path = $directory.'/'.$item;
	                // if the new path is a directory
	                if (is_dir($path)) {
	                    // we call this function with the new path
	                    $this->recursiveRemoveDirectory($path);
	                    // if the new path is a file
	                } else {
	                    // we remove the file
	                    @unlink($path);
	                }
	            }
	        }
	        // close the directory
	        closedir($handle);
	        // try to delete the now empty directory
	        if (!@rmdir($directory)) {
	            // return false if not possible
	            return false;
	        }
	        // return success
	        return true;
	    }
	}

	// Rebuild the cache
	public function buildCache($path) {
	    return $this->runSymfonyCommand($path, 'cache:clear', ['--no-interaction', '--env=prod', '--no-debug', '--no-warmup']);
	}

	// Clear and rebuild the cache
	public function clearMauticCache($path) {
	    if (!$this->recursiveRemoveDirectory($path.'/app/cache/prod')) {
	        return 'Could not remove the application cache. You will need to do this manually.';
	    }
	    return $this->buildCache($path);
	}

	// Rebuild Mautic assets
	public function rebuildAssets($path) {
		return $this->runSymfonyCommand($path, 'mautic:assets:generate', ['--no-interaction', '--env=prod', '--no-debug']);
	}

	/*
	|--------------------------------------------------------------------------
	| Get compatible versions of Mautic for whitelabeling
	|--------------------------------------------------------------------------
	*/
	public function templateVersions($version) {
		$path = 'templates';
		$versions = array();
		foreach (new DirectoryIterator($path) as $file) {
		    if ($file->isDot()) continue;
		    if ($file->isDir()) {
		        array_push($versions, $file->getFilename());
		    }
		}
		if (in_array(substr($version, 0, 3), $versions) || in_array($version, $versions)) {
			return $version;
		} else {
			return 0;
		}
	}

	/*
	|--------------------------------------------------------------------------
	| Compare two versions of Mautic to see files relevant to whitelabeling
	| have been changed.
	|--------------------------------------------------------------------------
	*/
	public function compareMauticVersions($v1, $v2) {
		$comparision = array();

		$base_copyright = '/app/bundles/CoreBundle/Views/Default/base.html.php';
		if (file_exists($v1.$base_copyright) && file_exists($v2.$base_copyright)) {
			$v1_base_copyright = sha1(file_get_contents($v1.$base_copyright));
			$v2_base_copyright = sha1(file_get_contents($v2.$base_copyright));
			if ( $v1_base_copyright == $v2_base_copyright ) {
				$comparision[$base_copyright] = 'Same';
			} else {
				$comparision[$base_copyright] = 'Different';
			}
		} else {
			$comparision[$base_copyright] = 'File Not Found';
		}

		$head_title = '/app/bundles/CoreBundle/Views/Default/head.html.php';
		if (file_exists($v1.$head_title) && file_exists($v2.$head_title)) {
			$v1_head_title = sha1(file_get_contents($v1.$head_title));
			$v2_head_title = sha1(file_get_contents($v2.$head_title));
			if ( $v1_head_title == $v2_head_title ) {
				$comparision[$head_title] = 'Same';
			} else {
				$comparision[$head_title] = 'Different';
			}
		} else {
			$comparision[$head_title] = 'File Not Found';
		}

		$left_panel = '/app/bundles/CoreBundle/Views/LeftPanel/index.html.php';
		if (file_exists($v1.$left_panel) && file_exists($v2.$left_panel)) {
			$v1_left_panel = sha1(file_get_contents($v1.$left_panel));
			$v2_left_panel = sha1(file_get_contents($v2.$left_panel));
			if ( $v1_left_panel == $v2_left_panel ) {
				$comparision[$left_panel] = 'Same';
			} else {
				$comparision[$left_panel] = 'Different';
			}
		} else {
			$comparision[$left_panel] = 'File Not Found';
		}

		$login_logo = '/app/bundles/UserBundle/Views/Security/base.html.php';
		if (file_exists($v1.$login_logo) && file_exists($v2.$login_logo)) {
			$v1_login_logo = sha1(file_get_contents($v1.$login_logo));
			$v2_login_logo = sha1(file_get_contents($v2.$login_logo));
			if ( $v1_login_logo == $v2_login_logo ) {
				$comparision[$login_logo] = 'Same';
			} else {
				$comparision[$login_logo] = 'Different';
			}
		} else {
			$comparision[$login_logo] = 'File Not Found';
		}

		$app_css = '/app/bundles/CoreBundle/Assets/css/app.css';
		if (file_exists($v1.$app_css) && file_exists($v2.$app_css)) {
			$v1_app_css = sha1(file_get_contents($v1.$app_css));
			$v2_app_css = sha1(file_get_contents($v2.$app_css));
			if ( $v1_app_css == $v2_app_css ) {
				$comparision[$app_css] = 'Same';
			} else {
				$comparision[$app_css] = 'Different';
			}
		} else {
			$comparision[$app_css] = 'File Not Found';
		}

		$libraries_css = '/app/bundles/CoreBundle/Assets/css/libraries/libraries.css';
		if (file_exists($v1.$libraries_css) && file_exists($v2.$libraries_css)) {
			$v1_libraries_css = sha1(file_get_contents($v1.$libraries_css));
			$v2_libraries_css = sha1(file_get_contents($v2.$libraries_css));
			if ( $v1_libraries_css == $v2_libraries_css ) {
				$comparision[$libraries_css] = 'Same';
			} else {
				$comparision[$libraries_css] = 'Different';
			}
		} else {
			$comparision[$libraries_css] = 'File Not Found';
		}

		$core_js = '/app/bundles/CoreBundle/Assets/js/1.core.js';
		if (file_exists($v1.$core_js) && file_exists($v2.$core_js)) {
			$v1_core_js = sha1(file_get_contents($v1.$core_js));
			$v2_core_js = sha1(file_get_contents($v2.$core_js));
			if ( $v1_core_js == $v2_core_js ) {
				$comparision[$core_js] = 'Same';
			} else {
				$comparision[$core_js] = 'Different';
			}
		} else {
			$comparision[$core_js] = 'File Not Found';
		}

		$content_js = '/app/bundles/CoreBundle/Assets/js/1a.content.js';
		if (file_exists($v1.$content_js) && file_exists($v2.$content_js)) {
			$v1_content_js = sha1(file_get_contents($v1.$content_js));
			$v2_content_js = sha1(file_get_contents($v2.$content_js));
			if ( $v1_content_js == $v2_content_js ) {
				$comparision[$content_js] = 'Same';
			} else {
				$comparision[$content_js] = 'Different';
			}
		} else {
			$comparision[$content_js] = 'File Not Found';
		}

		return $comparision;
	}

}
