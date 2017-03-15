<?php
// Default Mautic colors (used in JS and CSS)
$sidebar_background = '#4e5d9d';
$mautic_primary = '#4e5d9d';
$mautic_hover = '#3d497b';
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Mautic Whitelabeler</title>
	<link rel="icon" type="image/x-icon" href="images/favicon.ico" />
	<link rel="stylesheet" href="lib/bootstrap.min.css">
	<link rel='stylesheet' href="lib/spectrum/spectrum.css" />
	<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
	<link rel='stylesheet' href="lib/font-awesome-4.7.0/css/font-awesome.min.css" />
	<link rel='stylesheet' href="lib/style.php?sidebar_background=<?=urlencode($sidebar_background);?>&mautic_primary=<?=urlencode($sidebar_background);?>&mautic_hover=<?=urlencode($mautic_hover);?>" />
	<script src="lib/jquery-3.1.1.min.js"></script>
	<script src="lib/bootstrap.min.js"></script>
	<script src="lib/spectrum/spectrum.js"></script>
</head>
<body>
	<div id="header">
		<div id="logo">
			<div id="logoimg">
	 			<img id="mautic-logo" src="images/logo.png" alt="" style="width:130px;margin: 10px 0 0 0;" />
			</div><!--logoimg-->
 		</div>
 		<div id="links">
 			<a href="" class="mautic-link">Primary Color</a>
 			<a href="" class="mautic-hover">Secondary Color (Hover State)</a>
 		</div>
	</div>
	<div class="whitelabeler">
		<div class="container">
			<div class="row">
				<div class="col-md-6">
					<div id="options">
						<div class="row">
							<div class="col-md-12">
								<h2>Mautic Whitelabeler</h2>
							</div><!--col-md-12-->
						</div><!--row-->
						<div class="row">
							<div class="col-md-12">
								<form method="post" enctype="multipart/form-data">
									<div id="basic">
										<div class="form-group">
											<label for="mautic-path">Where is Mautic installed?</label><br/>
											<small>Absolute path to Mautic's root on your server.</small>
											<input type="text" class="form-control" id="mautic-path" value="<?=realpath(__DIR__ . '/..');?>">
											<small class="path-success">Compatible Mautic installation found (version <span class="version"></span>).</small>
											<small class="path-fail">Mautic installation not found here.</small>
										</div>
										<div class="form-group">
											<label for="mautic-url">Mautic Root URL</label><br/>
											<input type="text" class="form-control" id="mautic-url" placeholder="">
											<small class="url-success">OK, found Mautic here.</small>
											<small class="url-fail">Mautic installation not found at this URL.</small>
										</div>
										<div class="form-group">
											<label for="company-name">Company Name</label><br/>
											<small>This replaces "Mautic" in the page titles and footer.</small>
											<input type="text" class="form-control" id="company-name" placeholder="">
										</div>
									</div><!--basic-->
									<div id="colors">
										<h3>Mautic Colors</h3>
										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
													<label for="sidebar_background"><small>Logo Background</small></label><br/>
													<input type="text" class="form-control sidebar_background" id="sidebar-background" name="sidebar_background">
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label for="mautic_primary"><small>Pimary Color</small></label><br/>
													<input type="text" class="form-control mautic_primary" id="mautic-primary" name="mautic_primary">
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label for="mautic_hover"><small>Secondary Color</small></label><br/>
													<input type="text" class="form-control mautic_hover" id="mautic-hover" name="mautic_hover">
												</div>
											</div>
										</div><!--row-->
									</div><!--colors-->
									<div id="sidebarlogo">
										<h3>Sidebar Logo</h3>
										<div class="row">
											<div class="col-md-12">
												<div class="form-group">
													<label for="sidebar_logo">Image File</label>
													<input type="file" id="sidebar-logo-file" name="sidebar_logo_file">
												</div>
											</div>
										</div><!--row-->
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label for="logosidebarwidth"><small>Width</small></label><br/>
													<div class="input-group">
														<input type="number" class="form-control" id="logo-sidebar-width" name="logo_sidebar_width" value="130" min="50" max="200">
														<div class="input-group-addon">px</div>
													</div>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label for="sidebarmarginTop"><small>Top Margin</small></label><br/>
													<div class="input-group">
														<input type="number" class="form-control margintop" id="sidebar-margin-top" name="sidebar_margin_top" data-margin="marginTop" data-logo="sidebar" placeholder="0" value="10">
														<div class="input-group-addon">px</div>
													</div>
												</div>		
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label for="sidebarmarginRight"><small>Right Margin</small></label><br/>
													<div class="input-group">
														<input type="number" class="form-control marginright" id="sidebar-margin-right" name="sidebar_margin_right" data-margin="marginRight" data-logo="sidebar" value="0" placeholder="0">
														<div class="input-group-addon">px</div>
													</div>
												</div>						
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label for="sidebarmarginLeft"><small>Left Margin</small></label><br/>
													<div class="input-group">
														<input type="number" class="form-control marginleft" id="sidebar-margin-left" name="sidebar_margin_left" data-margin="marginLeft" data-logo="sidebar" value="0" placeholder="0">
														<div class="input-group-addon">px</div>
													</div>
												</div>						
											</div>
										</div><!--row-->
									</div><!--sidebarlogo-->
									<div id="loginlogo">
										<h3>Login Logo</h3>
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label for="login_logo">Image File</label>
													<input type="file" name="login_logo_file" id="login-logo-file">
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label for="logologinwidth"><small>Width</small></label><br/>
													<div class="input-group">
														<input type="number" class="form-control" id="logo-login-width" name="logo_login_width" value="150" min="50" max="400">
														<div class="input-group-addon">px</div>
													</div>
												</div>
											</div>
										</div><!--row-->
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label for="loginmarginTop"><small>Top Margin</small></label><br/>
													<div class="input-group">
														<input type="number" class="form-control margintop" id="login-margin-top" name="login_margin_top" data-margin="marginTop" data-logo="login" placeholder="0" value="20">
														<div class="input-group-addon">px</div>
													</div>
												</div>		
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label for="loginmarginBottom"><small>Bottom Margin</small></label><br/>
													<div class="input-group">
														<input type="number" class="form-control marginbottom" id="login-margin-bottom" name="login_margin_bottom" data-margin="marginBottom" data-logo="login" placeholder="0" value="20">
														<div class="input-group-addon">px</div>
													</div>
												</div>						
											</div>
										</div>
									</div><!--loginlogo-->
									<div id="favicon">
										<h3>Favicon</h3>
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label for="favicon">Image File</label>
													<input type="file" id="favicon" name="favicon">
												</div>
											</div>
											<div class="col-md-6">
												<img src="" id="favicon-preview" style="max-width:64px;" />
											</div>
										</div><!--row-->
									</div><!--favicon-->
									<button type="submit" class="btn btn-default">Start Whitelabeling</button>
								</form>
							</div><!--col-md-12-->
						</div><!--row-->
					</div><!--options-->
				</div><!--col 6-->
				<div class="col-md-6 console">
					<div id="right">
						<div class="panel panel-primary consolewindow">
							<div class="panel-heading">
								<div class="spinner">
									<div class="bounce1"></div>
									<div class="bounce2"></div>
									<div class="bounce3"></div>
								</div>
							</div>
							<div class="panel-body">
								<div id="console">
									<p id="waiting-for-input"><span class="arrow">></span> <span class="console-message">Waiting for input <span class="dots">...</span></span></p>
									<p id="waiting-for-input-success"><span class="arrow">></span> <span class="success">OK</span></p>
									<p id="looking-for-installation"><span class="arrow">></span> <span class="console-message">Looking for compatible Mautic installation <span class="dots">...</span></span></p>
									<p id="looking-for-installation-success"><span class="arrow">></span> <span class="success">FOUND</span></p>
									<p id="looking-for-installation-error"><span class="arrow">></span> <span class="error">ERROR:</span> Couldn't find compatibe Mautic installation. Check your path and version.</p>
									<p id="updating-css"><span class="arrow">></span> Updating CSS colors <span class="dots">...</span></p>
									<p id="updating-css-success"><span class="arrow">></span> <span class="success">SUCCESS</span></p>
									<p id="updating-css-error"><span class="arrow">></span> <span class="error">ERROR</span></p>
									<p id="updating-companyname"><span class="arrow">></span> Updating company name <span class="dots">...</span></p>
									<p id="updating-companyname-success"><span class="arrow">></span> <span class="success">SUCCESS</span></p>
									<p id="updating-companyname-error"><span class="arrow">></span> <span class="error">ERROR</span></p>
									<p id="updating-images"><span class="arrow">></span> Updating logos and favicon <span class="dots">...</span></p>
									<p id="updating-images-success"><span class="arrow">></span> <span class="success">SUCCESS</span></p>
									<p id="updating-images-error"><span class="arrow">></span> <span class="error">ERROR</span></p>
									<p id="regenerating"><span class="arrow">></span> Regenerating Mautic assets. Be patient. This can take a couple minutes <span class="dots">...</span></p>
									<p id="regenerating-success"><span class="arrow">></span> <span class="success">SUCCESS</span></p>
									<p id="regenerating-error"><span class="arrow">></span> <span class="error">ERROR</span></p>
									<p id="clearing"><span class="arrow">></span> Clearing Mautic's cache <span class="dots">...</span></p>
									<p id="clearing-success"><span class="arrow">></span> <span class="success">SUCCESS</span></p>
									<p id="clearing-error"><span class="arrow">></span> <span class="error">ERROR</span></p>
									<p id="complete"><span class="arrow">></span> Whitelabeling complete! You may have to clear your browser's cache. Don't forget to remove this directory or move it somewhere not publicly accessible!</p>
								</div>
							</div>
						</div><!--panel primary-->
						<div id="login_preview">
							<h3>Login Preview</h3>
							<div class="panel" name="form-login">
							    <div class="panel-body">
							        <div class="mautic-logo img-circle mb-md text-center" style="width:150px;">
							            <img id="login-logo" src="images/login_logo.png" alt="" style="width:150px; margin:20px 0 20px 0" />
							        </div>
							        <form class="form-group login-form" name="login" action="">
							            <div class="input-group mb-md">
							                <span class="input-group-addon"><i class="fa fa-user"></i></span>
							                <label for="username" class="sr-only">Username or email</label>
							                <input type="text" id="username" name="_username" class="form-control input-lg" value="" required="" autofocus="" placeholder="Username or email">
							            </div>
							            <div class="input-group mb-md">
							                <span class="input-group-addon"><i class="fa fa-key"></i></span>
							                <label for="password" class="sr-only">Password:</label>
							                <input type="text" id="password" name="_password" class="form-control input-lg" required="" placeholder="Password">
							            </div>
							            <div class="checkbox-inline custom-primary pull-left mb-md">
							                <label for="remember_me">
							                <input type="checkbox" id="remember_me" name="_remember_me">keep me logged in</label>
							            </div>
							            <button class="btn btn-lg btn-primary btn-block" id="login_button" type="submit">login</button>
							            <div class="mt-sm text-right">
							                <a href="#" id="forgot">forgot your password?</a>
							            </div>
							        </form>
							    </div><!--panel-body-->
							</div><!--panel-->
						</div><!--login preview-->
					</div><!--right-->
				</div><!--col 6-->
			</div><!--row-->
		</div><!--container-->
	</div><!--whitelabeler-->
	<div id="overlay"></div>
	<script>
		var sidebar_background = '<?=$sidebar_background;?>';
		var mautic_primary = '<?=$mautic_primary;?>';
		var mautic_hover = '<?=$mautic_hover;?>';
	</script>
	<script src="lib/whitelabeler.js.php?sidebar_background=<?=urlencode($sidebar_background);?>&mautic_primary=<?=urlencode($sidebar_background);?>&mautic_hover=<?=urlencode($mautic_hover);?>"></script>
</body>
</html>