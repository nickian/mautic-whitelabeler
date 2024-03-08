<?php
/**
 * This class provides functionalities to handle replacing colors in CSS,
 * company name in JavaScript and HTML templates, and replacing images, as
 * well as some utility functions.
 * 
 * @package Whitelabeler
 * @author Nick Thompson
 */
class Whitelabeler {

	/**
	 * Get JSON config files from assets folder.
	 *
	 * @return array Saved configuration files.
	 */
	public function getConfigFiles()
	{
		$config_files = array();
		$files = scandir(__DIR__.'/assets');
		foreach( $files as $file ) {
    		$file_extension = explode('.', $file);
    		$file_extension = end($file_extension);
    		if ( $file_extension == strtolower('json') ) {
                $config_files[] = $file;
    		}
		}
		return $config_files;
	}


	/**
	 * Load conifg.json file, if it exists.
	 * 
	 * @param string $file The json filename.
	 * @return array Configuration values in file.
	 */
	public function loadJsonConfig($file)
	{
		$config = array();
		if ( file_exists( __DIR__.'/assets/'.$file ) ) {
			$config = json_decode(file_get_contents(__DIR__.'/assets/'.$file), true);
			return $config;
		} else {
			return false;
		}
	}


	/**
	 * Validate that the Mautic URL works with cURL.
	 * 
	 * @param string $url The Mautic URL.
	 * @return array Status number and message.
	 */
    public function findMauticUrl($url)
	{
        if ( function_exists('curl_version') ) {

    		$url = urldecode($url);
    		if ( substr($url, -1) == '/' ) {
    			$url = substr($url, 0, -1);
    		}

    		$curl = curl_init();
    		curl_setopt_array($curl, array(
    		    CURLOPT_URL => $url.'/favicon.ico',
    		    CURLOPT_HEADER => true,
    		    CURLOPT_RETURNTRANSFER => true,
    		    CURLOPT_NOBODY => true,
    			CURLOPT_CONNECTTIMEOUT => 5,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_SSL_VERIFYPEER => false
    		));
    		$output = curl_exec($curl);

    		$headers = [];
    		$data = explode("\n",$output);
    		$headers['status'] = $data[0];
    		array_shift($data);
    		foreach($data as $key => $part) {
    			$middle = explode(":",$part);
    			if ( isset($middle[1]) ) {
    				$headers[trim($middle[0])] = trim($middle[1]);
    			}
    		}

    		$http_code = explode(' ', $headers['status']);
    		curl_close($curl);

    		if ( $output == false ) {
    			return array(
    				'status' => 0,
    				'message' => 'Address timed out. Make sure it\'s accessible by your server\'s network.'
    			);
    		} else {
    			if ( $http_code[1] != 200) {
    				return array(
    					'status' => 0,
    					'message' => 'Mautic not found: '. $headers['status']
    				);
    			} else {
    				$favicon = file_get_contents($url.'/favicon.ico', false, stream_context_create(array(
    				    'ssl' => array(
    				        'verify_peer' => false,
                            'verify_peer_name' => false
                        )
                    )));

    				if ($favicon) {
    					return array(
    						'status' => 1,
    						'message' => 'OK, Mautic found.'
    					);
    				} else {
    					return array(
    						'status' => 0,
    						'message' => 'Mautic not found. Make sure favicon.ico exists in the domain root, check for errors in your server error log.'
    					);
    				}
    			}
    		}
    	} else {
    		return array(
    			'status' => 0,
    			'message' => 'cURL PHP extension is not installed on your server.'
    		);
    	}
    }


	/**
	 * Determine Mautic's version by version.txt or release_metadata.json.
	 * 
	 * @param string $path The json filename.
	 * @return array|string Status number/message or version.
	 */
	public function mauticVersion($path)
	{
		$data = array();

		if ( substr($path, -1) == '/' ) {
			$path = substr($path, 0, -1);
		}

		if (file_exists($path.'/app/version.txt')) {
			$file = fopen($path.'/app/version.txt', 'r') or die('Unable to open file!');

			$version = trim(fread($file , filesize($path.'/app/version.txt')));

			if (strpos($version, '-dev') !== false) {
				return array(
					'status' => 0,
					'message' => 'You are using a development version of Mautic ('.$version.'). Whitelabeler only supports official, non-beta releases.'
				);
			} else {
				return $this->templateVersions($version);
			}
		} else {
			$release_metadata = $path.'/app/release_metadata.json';

			// Look for >= 3.0.0 /app/release_metadata.json
			if (file_exists($release_metadata)) {

				$release_metadata = $path.'/app/release_metadata.json';
				$file = fopen($path.'/app/release_metadata.json', 'r') or die('Unable to open file!');
				$version = json_decode(fread($file, filesize($release_metadata)), true);
				$version = $version['version'];

				if (strpos($version, '-dev') !== false) {
					return array(
						'status' => 0,
						'message' => 'You are using a development version of Mautic ('.$version.'). Whitelabeler only supports official, non-beta releases.'
					);
				} else {
					return $this->templateVersions($version);
				}

			// Couldn't find version.txt or release_metadata.json
			} else {
				return array(
					'status' => 0,
					'message' => $path.'/app/version.txt file not found.'
				);
			}
		}
	}


	/**
	 * Check for an asset by its URL
	 * 
	 * @param string $url The JSON filename.
	 * @return boolean 
	 */
	public function assetExists($url) 
	{
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => $url,
			CURLOPT_HEADER => true,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_NOBODY => true,
			CURLOPT_SSL_VERIFYHOST => false,
			CURLOPT_SSL_VERIFYPEER => false
		));
		$output = curl_exec($curl);
		$headers = [];
		$data = explode("\n",$output);
		$headers['status'] = $data[0];
		array_shift($data);
		foreach($data as $key => $part) {
			$middle = explode(":",$part);
			if ( isset($middle[1]) ) {
				$headers[trim($middle[0])] = trim($middle[1]);
			}
		}
		$http_code = explode(' ', $headers['status']);

		if ($http_code[1] == 200) {
			return 1;
		} else {
			return 0;
		}
	}


	/**
	 * Look for an image in assets folder.
	 * 
	 * @param string $image The image filename.
	 * @return boolean 
	 */
	public function imageExists($image) 
	{
		if ( file_exists(__DIR__.'/assets/'.$image) ) {
			return true;
		} else {
			return false;
		}
	}


	/**
	 * Replace colors in the stylesheets.
	 * 
	 * @param string $path The path to Mautic files.
	 * @param string $version Mautic version.
	 * @param string $logo_bg Hex value of logo background.
	 * @param string $primary Hex value for the primary color.
	 * @param string $hover Hex value for the hover color.
	 * @param string $sidebar_bg Hex value for the sidebar background.
	 * @param string $sidebar_submenu_bg Hex value for the sidebar submenu background.
	 * @param string $sidebar_link Hex value for sidebar links.
	 * @param string $sidebar_link_hover Hex value for sidebar link hover.
	 * @param string $active_icon Hex value for the active icon.
	 * @param string $sidebar_divider Hex value for the sidebar divider.
	 * @param string $submenu_bullet_bg Hex value for submenu bullets.
	 * @param string $submenu_bullet_shadow Hex value for bullet shadows.
	 * @return array Status number and message. 
	 */
	public function colors(
		$path,
		$version,
		$logo_bg,
		$primary,
		$hover,
		$sidebar_bg,
		$sidebar_submenu_bg,
		$sidebar_link,
		$sidebar_link_hover,
		$active_icon,
		$divider_left,
		$sidebar_divider,
		$submenu_bullet_bg,
		$submenu_bullet_shadow
	) {
		// Replace app.css contents with template and new colors
		$app_css = $path.'/app/bundles/CoreBundle/Assets/css/app.css';

		if (file_exists($app_css)) {
			$app_css_template = file_get_contents('templates/'.$version.'/app/bundles/CoreBundle/Assets/css/app.css');
			$app_css_new = str_replace(
				// Look for these template tags.
				array('{{logo_bg}}', '{{primary}}', '{{hover}}', '{{sidebar_bg}}', '{{sidebar_submenu_bg}}',
				      '{{sidebar_link}}', '{{sidebar_link_hover}}', '{{active_icon}}', '{{divider_left}}', '{{sidebar_divider}}',
				      '{{submenu_bullet_bg}}', '{{submenu_bullet_shadow}}'
				),
				// Replace template tags with new colors.
				array($logo_bg, $primary, $hover, $sidebar_bg, $sidebar_submenu_bg, $sidebar_link, $sidebar_link_hover,
				      $active_icon, $divider_left, $sidebar_divider, $submenu_bullet_bg, $submenu_bullet_shadow
				),
				$app_css_template
			);
			$file = fopen($app_css, "w");
			fwrite($file, $app_css_new);
			fclose($file);
		} else {
			return array(
				'status' => 0,
				'message' => 'Unable to find app.css in your Mautic installation.'
			);
		}

		// Replace libraries.css contents with template and new colors
		$libraries_css = $path.'/app/bundles/CoreBundle/Assets/css/libraries/libraries.css';

		if (file_exists($libraries_css)) {
			$libraries_css_template = file_get_contents('templates/'.$version.'/app/bundles/CoreBundle/Assets/css/libraries/libraries.css');
			$libraries_css_new = str_replace(
				// Look for these template tags.
				array('{{$logo_bg}}','{{primary}}','{{hover}}'),
				// Replace template tags with new colors.
				array($logo_bg, $primary, $hover),
				$libraries_css_template
			);
			$file = fopen($libraries_css, "w");
			fwrite($file, $libraries_css_new);
			fclose($file);

			return array(
			    'status' => 1,
			    'message' => 'CSS files updated with new colors!'
			 );
		} else {
			return array(
			    'status' => 0,
			    'message' => 'Unable to find libraries.css in your Mautic installation.'
			 );
		}
	}


	/**
	 * Replace "Mautic" with Company Name
	 * 
	 * @param string $path The path to Mautic files.
	 * @param string $version Mautic's version.
	 * @param string $company_name The value to replace "Mautic" with.
	 * @param string $footer_prefix Text to put before the footer.
	 * @param string $footer Text to use in the footer.
	 * @return array Status number and message.
	 */
	public function companyName($path, $version, $company_name, $footer_prefix, $footer)
	{
		// Version 5+
		if ( substr($version, 0, 1) == 5 ) {
			$base_copyright = '/app/bundles/CoreBundle/Resources/views/Default/base.html.twig';
			$head_title = '/app/bundles/CoreBundle/Resources/views/Default/head.html.twig';
			$left_panel = '/app/bundles/CoreBundle/Resources/views/LeftPanel/index.html.twig';
			$login_page = '/app/bundles/UserBundle/Resources/views/Security/base.html.twig';


		// Below V5
		} elseif ( substr($version, 0, 1) < 5 ) {

			$base_copyright = '/app/bundles/CoreBundle/Views/Default/base.html.php';
			$head_title = '/app/bundles/CoreBundle/Views/Default/head.html.php';
			$left_panel = '/app/bundles/CoreBundle/Views/LeftPanel/index.html.php';
			$login_page = '/app/bundles/UserBundle/Views/Security/base.html.php';

		}

		$js = '1a.content.js';
        $core_js = '/app/bundles/CoreBundle/Assets/js/'.$js;
        
		$errors = array();

		if (file_exists($path.$base_copyright)) {
			// get template
			$base_copyright_template = file_get_contents('templates/'.$version.$base_copyright);
			// fill template tags
			if ( $footer_prefix != '' ) {
    			$footer_prefix_base = '. ' . $footer_prefix;
			} else {
    			$footer_prefix_base = $footer;
			}
			if ( $footer != '' ) {
    			$footer_base = '| '.$footer;
			} else {
    			$footer_base = $footer;
			}
			$base_copyright_new = str_replace(array('{{company_name}}', '{{footer_prefix}}', '{{footer}}'), array($company_name, $footer_prefix_base, $footer_base), $base_copyright_template);
			// Replace Mautic file
			$file = fopen($path.$base_copyright, "w");
			fwrite($file, $base_copyright_new);
			fclose($file);
		} else {
			$errors[] = 'Unable to find Mautic file: '.$base_copyright;
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
			$errors[] = 'Unable to find Mautic file: '.$head_title;
		}

		if (file_exists($path.$login_page)) {
			// get template
			$login_page_template = file_get_contents('templates/'.$version.$login_page);
			// fill template tags
			if ( $footer_prefix != '' ) {
    			$footer_prefix_login = '. ' . $footer_prefix;
			} else {
    			$footer_prefix_login = $footer_prefix;
			}
			$login_page_new = str_replace(array('{{company_name}}', '{{footer_prefix}}', '{{footer}}'), array($company_name, $footer_prefix_login, $footer), $login_page_template);
			// Replace Mautic file
			$file = fopen($path.$login_page, "w");
			fwrite($file, $login_page_new);
			fclose($file);
		} else {
			$errors[] = 'Unable to find Mautic file: '.$login_page;
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
			$errors[] = 'Couldn\'t find core javascript file to update.';
		}

		if (empty($errors)) {
			return array(
				'status' => 1,
				'message' => 'Updated company name in templates!'
			);
		} else {
			return array(
				'status' => 0,
				'message' => $errors
			);
		}

	}


	/**
	 * Resize and save logo image file.
	 * 
	 * @param string $image The image filename.
	 * @param string $original The original image file.
	 * @param string $target The file location for the resized image.
	 * @return string The new image file location.
	 */
	public function imageResize($new_width, $original, $target) 
	{
	    $info = getimagesize($original);

	    if ($info['mime'] == 'image/png') {

	    	try {
				$im = imagecreatefrompng($original);
			} catch(Exception $e)  {
				echo 'Error with image: ',  $e->getMessage(), "\n";
				return false;
			}

			$srcWidth = imagesx($im);
			$srcHeight = imagesy($im);

			if ( $new_width <= 400 ) {
				$nWidth = $info[0];
			} else {
				$nWidth = 400;
			}

			$nHeight = ($srcHeight / $srcWidth) * $nWidth;
			$newImg = imagecreatetruecolor($nWidth, $nHeight);
			imagealphablending($newImg, false);
			imagesavealpha($newImg,true);
			$transparent = imagecolorallocatealpha($newImg, 255, 255, 255, 127);
			imagefilledrectangle($newImg, 0, 0, $nWidth, $nHeight, $transparent);
			imagecopyresampled($newImg, $im, 0, 0, 0, 0, $nWidth, $nHeight, $srcWidth, $srcHeight);
			imagepng($newImg, $target);

			return $target;

	    } else {
	    	if ($info['mime'] == 'image/jpeg' ) {
	            $image_create_func = 'imagecreatefromjpeg';
	            $image_save_func = 'imagejpeg';
	    	} elseif($info['mime'] == 'image/gif') {
	            $image_create_func = 'imagecreatefromgif';
	            $image_save_func = 'imagegif';
	    	} else {
	    		echo 'Unknown image format';
	    		return false;
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


	/**
	 * Resize and replace logo images.
	 * 
	 * @param string $path The image filename.
	 * @param string $url The URL to Mautic.
	 * @param string $version Mautic version.
	 * @param string $sidebar_image logo image file.
	 * @param string $sidebar_width Desired logo width.
	 * @param array $sidebar_margin Margin values for sidebar logo.
	 * @param string $login_image Image for login screen.
	 * @param string $login_width Width of login image.
	 * @param array $login_margin Margin values for login image.
	 * @param string $favicon_image Image for favicon.
	 * @return array Status number and message.
	 */
	public function replaceImages(
		$path,
		$url,
		$version,
		$sidebar_image,
		$sidebar_width,
		$sidebar_margin, // array (top, right, left)
		$login_image,
		$login_width,
		$login_margin, // array (top, bottom)
		$favicon_image
	) {
		if ( $favicon_image == false ) {
			$favicon_image = $login_image;
		}

		$media_images = $path.'/media/images';

		// Update favicon
		require_once('vendor/chrisjean/php-ico/class-php-ico.php');

		// If favicon is .ico, move/copy the file
		if ( strpos(finfo_file(finfo_open(FILEINFO_MIME_TYPE),$favicon_image), 'ico') ) { // check if the mimetype of picture contain ico (match all image/*ico* )
			copy($favicon_image, $path.'/favicon.ico');
			copy($path.'/favicon.ico', $media_images.'/favicon.ico');
		// convert to .ico and save.
		} else {
			$ico_lib = new PHP_ICO($favicon_image, array(array(64, 64)));
			$ico_lib->save_ico($path.'/favicon.ico');
			$ico_lib->save_ico($media_images.'/favicon.ico');
		}

		// Update sidebar logo
		$this->imageResize(250, $sidebar_image, $media_images.'/sidebar_logo.png');

		// Version 5+
		if ( substr($version, 0, 1) >= 5 ) {
			$left_panel = $path.'/app/bundles/CoreBundle/Resources/views/LeftPanel/index.html.twig';
		// Below V5
		} elseif ( substr($version, 0, 1) < 5 ) {
			$left_panel = $path.'/app/bundles/CoreBundle/Views/LeftPanel/index.html.php';
		}
		
		if (file_exists($left_panel)) {

			// Version 5+
			if ( substr($version, 0, 1) >= 5 ) {
				$left_panel_template = file_get_contents('templates/'.$version.'/app/bundles/CoreBundle/Resources/views/LeftPanel/index.html.twig');
			// Below V5
			} elseif ( substr($version, 0, 1) < 5 ) {
				$left_panel_template = file_get_contents('templates/'.$version.'/app/bundles/CoreBundle/Views/LeftPanel/index.html.php');
			}
		
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
			return array(
				'status' => 0,
				'message' => $left_panel.' NOT FOUND.'
			);
		}

		// Update login logo and create some icons from login logo

		// Apple Touch Icon
		$this->imageResize(192, $login_image, $media_images.'/apple-touch-icon.png');
		// mautic_logo_db64.png
		$this->imageResize(64, $login_image, $media_images.'/mautic_logo_db64.png');
		// mautic_logo_db200.png
		$this->imageResize(200, $login_image, $media_images.'/mautic_logo_db200.png');
		// mautic_logo_lb200.png
		$this->imageResize(200, $login_image, $media_images.'/mautic_logo_lb200.png');

		$this->imageResize(400, $login_image, $media_images.'/login_logo.png');

		// Version 5+
		if ( substr($version, 0, 1) == 5 ) {
			$login_page = $path.'/app/bundles/UserBundle/Resources/views/Security/base.html.twig';
		// Below V5
		} elseif ( substr($version, 0, 1) < 5 ) {
			$login_page = $path.'/app/bundles/UserBundle/Views/Security/base.html.php';
		}

		if ( file_exists($login_page) ) {
			$login_page_template = file_get_contents($login_page);
			$login_page_new = str_replace(
				// Look for these template tags.
				array('{{login_logo}}', '{{login_logo_width}}', '{{login_logo_margin_top}}', '{{login_logo_margin_bottom}}'),
				// Replace template tags with values.
				array($url.'/media/images/login_logo.png', $login_width, $login_margin['top'], $login_margin['bottom']),
				// In this file
				$login_page_template
			);
			$file = fopen($login_page, "w");
			fwrite($file, $login_page_new);
			fclose($file);

			return array(
				'status' => 1,
				'message' => 'Logos updated! '
			);
		} else {
			return array(
				'status' => 0,
				'message' => $login_page.' NOT FOUND.'
			);
		}
	}

	/*
	|--------------------------------------------------------------------------
	| Used to run Mautic Console Commands (taken from upgrade.php)
	|--------------------------------------------------------------------------
	*/
	public function runSymfonyCommand($path, $command, array $args) 
	{
	    static $application;
	    $autoloadPath = file_exists($path.'/autoload.php') ? $path.'/autoload.php' : $path.'/app/autoload.php';
        require_once $autoloadPath;
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


	/**
	 * Remove a directory (used to clear cache - taken from upgrade.php)
	 * 
	 * @param string $directory The directory to remove.
	 * @return boolean
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


	/**
	 * Rebuild the Mautic cache.
	 * 
	 * @param string $path Path to Mautic.
	 * @return string Message from command.
	 */
	public function buildCache($path) 
	{
	    return $this->runSymfonyCommand($path, 'cache:clear', ['--no-interaction', '--env=prod', '--no-debug', '--no-warmup']);
	}


	/**
	 * Clear and rebuild the Mautic cache.
	 * 
	 * @param string $path Path to Mautic.
	 * @return array Status number and message.
	 */
	public function clearMauticCache($path) 
	{
	    $cachePath = is_dir($path.'/var/cache/prod') ? $path.'/var/cache/prod' : 'app/cache/prod';
	    if (!$this->recursiveRemoveDirectory($cachePath)) {
	        return array(
    	            'status' => 0,
    	            'message' => 'Could not remove the application cache. You will need to do this manually.'
	        );
	    }
	    return array(
    	        'status' => 1,
    	        'message' => $this->buildCache($path)
	    );
	}


	/**
	 * Rebuild Mautic assets.
	 * 
	 * @param string $path Path to Mautic.
	 * @return string Message from command.
	 */
	public function rebuildAssets($path) 
	{
		return $this->runSymfonyCommand($path, 'mautic:assets:generate', ['--no-interaction', '--env=prod', '--no-debug']);
	}


	/**
	 * Get Compatible Versions of Mautic for Whitelabeling
	 * 
	 * @param string $version Version of Mautic to test compatability.
	 * @return array Status number and message.
	 */
	public function templateVersions($version) 
	{
		$path = 'templates';
		$versions = array();
		foreach (new DirectoryIterator($path) as $file) {
		    if ($file->isDot()) continue;
		    if ($file->isDir()) {
		        array_push($versions, $file->getFilename());
		    }
		}
		if (in_array(substr($version, 0, 3), $versions) || in_array($version, $versions)) {
			return array(
				'status' => 1,
				'version' => $version,
				'message' => 'Compatible version found ('.$version.')'
			);
		} else {
			return array(
				'status' => 0,
				'message' => 'The version of Mautic you are using ('.$version.') is not currently supported.'
			);
		}
	}


	/**
	 * Compare two versions of Mautic to see files relevant to whitelabeling
	 * have been changed.
	 * 
	 * @param string $v1 Previous supported version.
	 * @param string $v1 New version to compare to.
	 * @return array Comparison results.
	 */
	public function compareMauticVersions($v1, $v2) 
	{

		$changed = 'Changes detected.';
		$not_changed = 'No changes detected.';
		$not_found = 'File not found.';

		$version1 = explode('/', $v1);
		$version1 = $version1[count($version1)-1];

		$version2 = explode('/', $v2);
		$version2 = $version2[count($version2)-1];

		$comparision = array();
		echo PHP_EOL;
		echo 'Comparing version '.$version1.' to version '.$version2;
		echo PHP_EOL.PHP_EOL;

		// Check for v5+
		if ( substr($version1, 0, 1) >= 5 && substr($version2, 0, 1) >= 5 ) {
			$base_copyright = '/app/bundles/CoreBundle/Resources/views/Default/base.html.twig';
		} else {
			$base_copyright = '/app/bundles/CoreBundle/Views/Default/base.html.php';
		}

		if (file_exists($v1.$base_copyright) && file_exists($v2.$base_copyright)) {
			$v1_base_copyright = sha1(file_get_contents($v1.$base_copyright));
			$v2_base_copyright = sha1(file_get_contents($v2.$base_copyright));
			if ( $v1_base_copyright == $v2_base_copyright ) {
				$comparision[$base_copyright] = $not_changed;
			} else {
				$comparision[$base_copyright] = $changed;
			}
		} else {
			$comparision[$base_copyright] = $not_found;
		}

		// Check for v5+
		if ( substr($version1, 0, 1) >= 5 && substr($version2, 0, 1) >= 5 ) {
			$head_title = '/app/bundles/CoreBundle/Resources/views/Default/head.html.twig';
		} else {
			$head_title = '/app/bundles/CoreBundle/Views/Default/head.html.php';
		}

		if (file_exists($v1.$head_title) && file_exists($v2.$head_title)) {
			$v1_head_title = sha1(file_get_contents($v1.$head_title));
			$v2_head_title = sha1(file_get_contents($v2.$head_title));
			if ( $v1_head_title == $v2_head_title ) {
				$comparision[$head_title] = $not_changed;
			} else {
				$comparision[$head_title] = $changed;
			}
		} else {
			$comparision[$head_title] = $not_found;
		}

		// Check for v5+
		if ( substr($version1, 0, 1) >= 5 && substr($version2, 0, 1) >= 5 ) {
			$left_panel = '/app/bundles/CoreBundle/Resources/views/LeftPanel/index.html.twig';
		} else {
			$left_panel = '/app/bundles/CoreBundle/Views/LeftPanel/index.html.php';
		}

		if (file_exists($v1.$left_panel) && file_exists($v2.$left_panel)) {
			$v1_left_panel = sha1(file_get_contents($v1.$left_panel));
			$v2_left_panel = sha1(file_get_contents($v2.$left_panel));
			if ( $v1_left_panel == $v2_left_panel ) {
				$comparision[$left_panel] = $not_changed;
			} else {
				$comparision[$left_panel] = $changed;
			}
		} else {
			$comparision[$left_panel] = $not_found;
		}

		// Check for v5+
		if ( substr($version1, 0, 1) >= 5 && substr($version2, 0, 1) >= 5 ) {
			$login_logo = '/app/bundles/UserBundle/Resources/views/Security/base.html.twig';
		} else {
			$login_logo = '/app/bundles/UserBundle/Views/Security/base.html.php';
		}
		
		if (file_exists($v1.$login_logo) && file_exists($v2.$login_logo)) {
			$v1_login_logo = sha1(file_get_contents($v1.$login_logo));
			$v2_login_logo = sha1(file_get_contents($v2.$login_logo));
			if ( $v1_login_logo == $v2_login_logo ) {
				$comparision[$login_logo] = $not_changed;
			} else {
				$comparision[$login_logo] = $changed;
			}
		} else {
			$comparision[$login_logo] = $not_found;
		}

		$app_css = '/app/bundles/CoreBundle/Assets/css/app.css';

		if (file_exists($v1.$app_css) && file_exists($v2.$app_css)) {
			$v1_app_css = sha1(file_get_contents($v1.$app_css));
			$v2_app_css = sha1(file_get_contents($v2.$app_css));
			if ( $v1_app_css == $v2_app_css ) {
				$comparision[$app_css] = $not_changed;
			} else {
				$comparision[$app_css] = $changed;
			}
		} else {
			$comparision[$app_css] = $not_found;
		}

		$libraries_css = '/app/bundles/CoreBundle/Assets/css/libraries/libraries.css';

		if (file_exists($v1.$libraries_css) && file_exists($v2.$libraries_css)) {
			$v1_libraries_css = sha1(file_get_contents($v1.$libraries_css));
			$v2_libraries_css = sha1(file_get_contents($v2.$libraries_css));
			if ( $v1_libraries_css == $v2_libraries_css ) {
				$comparision[$libraries_css] = $not_changed;
			} else {
				$comparision[$libraries_css] = $changed;
			}
		} else {
			$comparision[$libraries_css] = $not_found;
		}

		$core_js = '/app/bundles/CoreBundle/Assets/js/1.core.js';

		if (file_exists($v1.$core_js) && file_exists($v2.$core_js)) {
			$v1_core_js = sha1(file_get_contents($v1.$core_js));
			$v2_core_js = sha1(file_get_contents($v2.$core_js));
			if ( $v1_core_js == $v2_core_js ) {
				$comparision[$core_js] = $not_changed;
			} else {
				$comparision[$core_js] = $changed;
			}
		} else {
			$comparision[$core_js] = $not_found;
		}

		$content_js = '/app/bundles/CoreBundle/Assets/js/1a.content.js';

		if (file_exists($v1.$content_js) && file_exists($v2.$content_js)) {
			$v1_content_js = sha1(file_get_contents($v1.$content_js));
			$v2_content_js = sha1(file_get_contents($v2.$content_js));
			if ( $v1_content_js == $v2_content_js ) {
				$comparision[$content_js] = $not_changed;
			} else {
				$comparision[$content_js] = $changed;
			}
		} else {
			$comparision[$content_js] = $not_found;
		}

		return $comparision;
	}


	/**
	 * Validate configuration values from config.json
	 * 
	 * @param string $file The config JSON file.
	 * @return array Errors found and messages.
	 */
	public function validateConfigValues($file=false) 
	{
        if ( $file != false ) {
            $config_vals = $this->loadJsonConfig($file);
        } else {
            $config_vals = $this->loadJsonConfig('config.json');
        }

	    $errors = array();

	    // Verify the PATH in config.txt is correct and Mautic exists there
	    $path = $this->mauticVersion($config_vals['path']);
	    if ( $path['status'] != 1 ) {
	        $errors[] =  $path['message'];
	    }

	    // Verify the URL in config.txt is correct
	    $url = $this->findMauticUrl($config_vals['url']);
	    if ( $url['status'] != 1 ) {
	        $errors[] = 'Invalid URL provided' . (isset($url['message']) ? ', ' . $url['message'] : '') . '.';
	    }

	    // Verify that a COMPANY name is defined in config.txt
	    if ( !$config_vals['company'] ) {
	        $errors[] = 'Please provide a company name.';
	    }

	    // Verify that a valid hex value is provided for primary in config.json
	    if ( !preg_match('/#([a-fA-F0-9]{3}){1,2}\b/', $config_vals['primary'] ) ) {
	        $errors[] = 'Invalid hex value provided for the primary color.';
	    }

	    // Verify that a valid hex value is provided for hover in config.json
	    if ( !preg_match('/#([a-fA-F0-9]{3}){1,2}\b/', $config_vals['hover'] ) ) {
	        $errors[] = 'Invalid hex value provided for the hover color.';
	    }

	    // Verify that a valid hex value is provided for logo_bg
	    if ( !preg_match('/#([a-fA-F0-9]{3}){1,2}\b/', $config_vals['logo_bg'] ) ) {
	        $errors[] = 'Invalid hex value provided for the sidebar logo background color.';
	    }

	    // Verify that a valid hex value is provided for sidebar_bg
	    if ( !preg_match('/#([a-fA-F0-9]{3}){1,2}\b/', $config_vals['sidebar_bg'] ) ) {
	        $errors[] = 'Invalid hex value provided for the sidebar background color.';
	    }

	    // Verify that a valid hex value is provided for sidebar_bg
	    if ( !preg_match('/#([a-fA-F0-9]{3}){1,2}\b/', $config_vals['sidebar_submenu_bg'] ) ) {
	        $errors[] = 'Invalid hex value provided for the sidebar submenu background color.';
	    }

	    // Verify that a valid hex value is provided for sidebar_link
	    if ( !preg_match('/#([a-fA-F0-9]{3}){1,2}\b/', $config_vals['sidebar_link'] ) ) {
	        $errors[] = 'Invalid hex value provided for the sidebar link color.';
	    }

	    // Verify that a valid hex value is provided for sidebar_link_hover
	    if ( !preg_match('/#([a-fA-F0-9]{3}){1,2}\b/', $config_vals['sidebar_link_hover'] ) ) {
	        $errors[] = 'Invalid hex value provided for the sidebar link hover color.';
	    }

	    // Verify that a valid hex value is provided for active_icon
	    if ( !preg_match('/#([a-fA-F0-9]{3}){1,2}\b/', $config_vals['active_icon'] ) ) {
	        $errors[] = 'Invalid hex value provided for the active icon color.';
	    }

	    // Verify that a valid hex value is provided for sidebar_divider
	    if ( !preg_match('/#([a-fA-F0-9]{3}){1,2}\b/', $config_vals['sidebar_divider'] ) ) {
	        $errors[] = 'Invalid hex value provided for the sidebar divider color.';
	    }

	    // Verify that a valid hex value is provided for submenu_bullet_bg
	    if ( !preg_match('/#([a-fA-F0-9]{3}){1,2}\b/', $config_vals['submenu_bullet_bg'] ) ) {
	        $errors[] = 'Invalid hex value provided for the submenu bullet background color.';
	    }

	    // Verify that a valid hex value is provided for submenu_bullet_shadow
	    if ( !preg_match('/#([a-fA-F0-9]{3}){1,2}\b/', $config_vals['submenu_bullet_shadow'] ) ) {
	        $errors[] = 'Invalid hex value provided for the submenu bullet shadow color.';
	    }

	    // Verify that sidebar_image file exists in the assets folder
	    if ( !$this->imageExists($config_vals['sidebar_logo']) ) {
	        $errors[] = 'Can\'t find the sidebar image provided ('.$config_vals['sidebar_logo'].')';
	    }

	    // Verify that a valid numeric value for sidebar_logo_width was provided
	    if ( !is_numeric($config_vals['sidebar_logo_width']) ) {
	        $errors[] = 'Invalid sidebar logo width value provided.';
	    }

	    // Verify that a valid sidebar_logo_margin_top number was provided
	    if ( !is_numeric($config_vals['sidebar_logo_margin_top']) ) {
	        $errors[] = 'Invalid sidebar_logo_margin_top value provided.';
	    }

	    // Verify that a valid sidebar_logo_margin_right number was provided
	    if ( !is_numeric($config_vals['sidebar_logo_margin_right']) ) {
	        $errors[] = 'Invalid sidebar_logo_margin_right value provided.';
	    }

	    // Verify that a valid sidebar_logo_margin_left number was provided
	    if ( !is_numeric($config_vals['sidebar_logo_margin_left']) ) {
	        $errors[] = 'Invalid sidebar_logo_margin_left value provided.';
	    }

	    // Verify that login_logo file exists in the assets folder
	    if ( !$this->imageExists($config_vals['login_logo']) ) {
	        $errors[] = 'Can\'t find the login logo image provided ('.$config_vals['login_logo'].')';
	    }

	    // Verify that a valid numeric value for sidebar_logo_width was provided
	    if ( !is_numeric($config_vals['login_logo_width']) ) {
	        $errors[] = 'Invalid login logo width value provided.';
	    }

	    // Verify that a valid login_logo_margin_top number was provided
	    if ( !is_numeric($config_vals['login_logo_margin_top']) ) {
	        $errors[] = 'Invalid login_logo_margin_top value provided.';
	    }

	    // Verify that a valid login_logo_margin_top number was provided
	    if ( !is_numeric($config_vals['login_logo_margin_bottom']) ) {
	        $errors[] = 'Invalid login_logo_margin_bottom value provided.';
	    }

	    // Verify that favicon file exists in the assets folder
	    if ( !$this->imageExists($config_vals['favicon']) ) {
	        $errors[] = 'Can\'t find the favicon image provided ('.$config_vals['favicon'].')';
	    }

	    return array(
	        'errors' => $errors,
            'config' => $config_vals
	    );
	}
}
