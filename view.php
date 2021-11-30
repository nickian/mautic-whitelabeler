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
	<script src="lib/jquery-3.1.1.min.js"></script>
	<script src="lib/bootstrap.min.js"></script>
	<script src="lib/spectrum/spectrum.js"></script>
	<link rel='stylesheet' href="lib/style.css" />
</head>
<body>
	<div id="header">
		<div id="logo">
			<div id="logoimg">
	 			<img id="mautic-logo" src="images/sidebar.png" alt="" style="width:130px;margin: 10px 0 0 0;" />
			</div><!--logoimg-->
 		</div>
 		<div id="links">
 			<a href="" class="mautic-link">Primary Color</a>
 			<a href="" class="mautic-hover">Hover Color</a>
 		</div>
	</div>

	<div class="sidebar-container">
		<div class="sidebar">

		    <li class="menu-item">
		        <a href="#">
		            <span class="icon pull-left fa fa-pie-chart active-icon"></span>
		            <span class="nav-item-name">Segments</span>
		        </a>
		    </li>

		    <li class="menu-item group">
		        <a href="#">
		            <span class="icon pull-left fa fa-puzzle-piece"></span>
		            <span class="nav-item-name">Components</span>
					<span class="arrow"></span>
		        </a>

		        <ul class="nav-submenu collapse in" id="mautic_components_root_child" style="height: auto;">
		            <li class="nav-group">
		                <a href="#" data-menu-link="mautic_asset_index" id="mautic_asset_index" data-toggle="ajax"><span class="nav-item-name text">Assets</span></a>
		            </li>
		            <li class="nav-group">
		                <a href="#" data-menu-link="mautic_form_index" id="mautic_form_index" data-toggle="ajax"><span class="nav-item-name text">Forms</span></a>
		            </li>
		            <li class="nav-group">
		                <a href="#" data-menu-link="mautic_page_index" id="mautic_page_index" data-toggle="ajax"><span class="nav-item-name text">Landing Pages</span></a>
		            </li>
		            <li class="nav-group">
		                <a href="#" data-menu-link="mautic_dynamicContent_index" id="mautic_dynamicContent_index" data-toggle="ajax"><span class="nav-item-name text">Dynamic Content</span></a>
		            </li>
		        </ul>

				<li class="menu-item support">
					<h1>SUPPORT THIS PROJECT</h1>
					<p>If you find this project useful, please consider sending a small contribution. It allows me to spend more time on 
					open source software like this!</p>
					<a href="https://paypal.me/nickthompson" target="blank" class="btn btn-sm btn-support"><i class="fa fa-coffee" aria-hidden="true"></i>&nbsp;BUY ME A COFFEE</a>

					 <h1>NEED HOSTING?</h1>
					 <p>If you need fully managed web hosting compatible with Mautic and other open source software, feel free to reach out. We host and configure other open source apps like NextCloud, OnlyOffice, MatterMost, and build our own custom solutions.</p>
					 <a href="mailto:sales@creative.link?subject=Mautic%20Hosting" target="blank" class="btn btn-sm btn-support"><i class="fa fa-commenting" aria-hidden="true"></i>&nbsp;CONTACT</a>
		        </li>
		    </li>

		</div><!--sidebar-->
	</div><!--sidebar-container-->

	<div class="whitelabeler">
		<div class="container">
			<div class="row">
				<div class="col-md-6">
					<div id="options">
						<div class="row">
							<div class="col-md-12">
								<h2>Mautic Whitelabeler</h2>
								<p>See the <a href="https://github.com/nickian/mautic-whitelabeler" target="_blank" class="mautic-link">GitHub page</a> for more information and instructions.</p>
								<p>Don't use this without making a backup of your Mautic files first!</p>
								<hr/>
							</div><!--col-md-12-->
						</div><!--row-->
						<div class="row">
							<div class="col-md-12">
								<form method="post" enctype="multipart/form-data">
									<div id="basic">
										<div class="form-group">
											<label for="mautic-path">Where is Mautic installed?</label><br/>
											<small>Absolute path to Mautic's root on your server.</small>
											<div class="field-container">
												<input type="text" class="form-control" id="mautic-path" name="mautic_path">
												<span id="path-loading">
													<i class="fa fa-spinner fa-spin"></i>
												</span>
											</div>
											<small class="path-success"></small>
											<small class="path-fail"></small>
										</div>
										<div class="form-group">
											<label for="mautic-url">Mautic URL</label><br/>
											<div class="field-container">
												<input type="text" class="form-control" name="mautic_url" id="mautic-url" placeholder="">
												<span id="url-loading">
													<i class="fa fa-spinner fa-spin"></i>
												</span>
											</div>
											<small class="url-success"></small>
											<small class="url-fail"></small>
										</div>
										<div class="form-group">
											<label for="company-name">Company Name</label><br/>
											<small>This replaces "Mautic" in the page titles and footer.</small>
											<input type="text" class="form-control" name="company_name" id="company-name" value="Mautic">
										</div>
										
										<div class="form-group">
											<label for="footer-prefix">Footer Prefix (optional)</label><br/>
											<small>Displays after company name in the footer.</small>
											<input type="text" class="form-control" name="footer_prefix" id="footer-prefix" value="All Rights Reserved.">
										</div>
										
										<div class="form-group">
											<label for="footer">Footer (Optional)</label><br/>
											<small>Additional footer text or HTML to display after company name and footer prefix.</small>
											<input type="text" class="form-control" name="footer" id="footer" value="">
										</div>
										
									</div><!--basic-->
									<div id="colors">
										<h3>General Colors</h3>
										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
													<label for="mautic_primary"><small>Primary Color</small></label><br/>
													<input type="text" class="form-control mautic_primary" id="mautic-primary" name="mautic_primary">
												</div>
											</div>
											<div class="col-md-8">
												<div class="form-group">
													<label for="mautic_hover"><small>Hover Color (Button Background)</small></label><br/>
													<input type="text" class="form-control mautic_hover" id="mautic-hover" name="mautic_hover">
												</div>
											</div>
										</div><!--row-->
									</div><!--colors-->

									<div id="sidebarcolors">
										<h3>Sidebar Colors</h3>
										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
													<label for="logo_bg"><small>Logo Background</small></label><br/>
													<input type="text" class="form-control logo_bg" id="logo-bg" name="logo_bg">
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label for="sidebar_bg"><small>Sidebar Background</small></label><br/>
													<input type="text" class="form-control sidebar_bg" id="sidebar-bg" name="sidebar_bg">
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label for="sidebar_submenu_bg"><small>Submenu Background</small></label><br/>
													<input type="text" class="form-control sidebar_submenu_bg" id="sidebar-submenu-bg" name="sidebar_submenu_bg">
												</div>
											</div>
										</div><!--row-->

										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
													<label for="sidebar_link"><small>Link Text Color</small></label><br/>
													<input type="text" class="form-control sidebar_link" id="sidebar-link" name="sidebar_link">
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label for="sidebar_link_hover"><small>Link Text Hover</small></label><br/>
													<input type="text" class="form-control sidebar_link_hover" id="sidebar-link-hover" name="sidebar_link_hover">
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label for="active_icon"><small>Active Icon</small></label><br/>
													<input type="text" class="form-control active_icon" id="active-icon" name="active_icon">
												</div>
											</div>
										</div><!--row-->

										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
													<label for="sidebar_divider"><small>Divider</small></label><br/>
													<input type="text" class="form-control sidebar_divider" id="sidebar-divider" name="sidebar_divider">
												</div>
												<label for="divider_left"><small>Divider Left Position</small></label><br/>
												<div class="input-group">
													<input type="number" class="form-control" id="divider-left" name="divider_left" value="50">
													<div class="input-group-addon">px</div>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<div class="form-group">
														<label for="submenu_bullet_bg"><small>Submenu Bullet Background</small></label><br/>
														<input type="text" class="form-control submenu_bullet_bg" id="submenu-bullet-bg" name="submenu_bullet_bg">
													</div>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label for="submenu_bullet_shadow"><small>Submenu Bullet Shadow</small></label><br/>
													<input type="text" class="form-control submenu_bullet_shadow" id="submenu-bullet-shadow" name="submenu_bullet_shadow">
												</div>
											</div>
										</div><!--row-->
									</div><!--sidebar-colors-->

									<div id="sidebarlogo">
										<h3>Sidebar Logo</h3>
										<div class="row">
											<div class="col-md-12">
												<div class="form-group" id="sidebar-logo-upload">
													<label for="sidebar_logo">Image File</label>
													<input type="file" id="sidebar-logo-file" name="sidebar_logo_file">
													<span class="sidebar-logo-error"></span>
												</div>
												<div id="sidebar-logo-loaded">
													<p><a href="#"><i class="fa fa-times-circle" aria-hidden="true"></i></a> <span></span></p>
												</div>
											</div>
										</div><!--row-->
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label for="sidebar-logo-width"><small>Width</small></label><br/>
													<div class="input-group">
														<input type="number" class="form-control" id="sidebar-logo-width" name="sidebar_logo_width" value="130" min="50" max="200">
														<div class="input-group-addon">px</div>
													</div>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label for="sidebar-logo-margin-top"><small>Top Margin</small></label><br/>
													<div class="input-group">
														<input type="number" class="form-control margintop" id="sidebar-logo-margin-top" name="sidebar_logo_margin_top" data-margin="marginTop" data-logo="sidebar" placeholder="0" value="10">
														<div class="input-group-addon">px</div>
													</div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label for="sidebar-logo-margin-left"><small>Left Margin</small></label><br/>
													<div class="input-group">
														<input type="number" class="form-control marginleft" id="sidebar-logo-margin-left" name="sidebar_logo_margin_left" data-margin="marginLeft" data-logo="sidebar" value="0" placeholder="0">
														<div class="input-group-addon">px</div>
													</div>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label for="sidebar-logo-margin-right"><small>Right Margin</small></label><br/>
													<div class="input-group">
														<input type="number" class="form-control marginright" id="sidebar-logo-margin-right" name="sidebar_logo_margin_right" data-margin="marginRight" data-logo="sidebar" value="0" placeholder="0">
														<div class="input-group-addon">px</div>
													</div>
												</div>
											</div>
										</div><!--row-->
									</div><!--sidebarlogo-->
									<div id="loginlogo">
										<h3>Login Logo</h3>
										<div class="row">
											<div class="col-md-12">
												<div class="form-group" id="login-logo-upload">
													<label for="login_logo">Image File</label>
													<input type="file" name="login_logo_file" id="login-logo-file">
													<span class="login-logo-error"></span>
												</div>
												<div id="login-logo-loaded">
													<p><a href="#"><i class="fa fa-times-circle" aria-hidden="true"></i></a> <span></span></p>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label for="login-logo-width"><small>Width</small></label><br/>
													<div class="input-group">
														<input type="number" class="form-control" id="login-logo-width" name="login_logo_width" value="150" min="50" max="400">
														<div class="input-group-addon">px</div>
													</div>
												</div>
											</div>
											<div class="col-md-6">
											</div>
										</div><!--row-->
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label for="login-logo-margin-top"><small>Top Margin</small></label><br/>
													<div class="input-group">
														<input type="number" class="form-control margintop" id="login-logo-margin-top" name="login_logo_margin_top" data-margin="marginTop" data-logo="login" placeholder="0" value="20">
														<div class="input-group-addon">px</div>
													</div>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label for="login-logo-margin-bottom"><small>Bottom Margin</small></label><br/>
													<div class="input-group">
														<input type="number" class="form-control marginbottom" id="login-logo-margin-bottom" name="login_logo_margin_bottom" data-margin="marginBottom" data-logo="login" placeholder="0" value="20">
														<div class="input-group-addon">px</div>
													</div>
												</div>
											</div>
										</div>
									</div><!--loginlogo-->
									<div id="favicon">
										<h3>Favicon</h3>
										<div class="row">
											<div class="col-md-12">
												<div class="form-group" id="favicon-upload">
													<label for="favicon-file">Image File</label>
													<input type="file" id="favicon-file" name="favicon_file">
													<span class="favicon-error"></span>
												</div>
												<div id="favicon-loaded">
													<p><a href="#"><i class="fa fa-times-circle" aria-hidden="true"></i></a> <span></span></p>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<img src="images/favicon.ico" id="favicon-preview" style="max-width:64px;" />
											</div>
										</div><!--row-->
									</div><!--favicon-->

									<div id="actions">
										<button id="reset" class="btn btn-default">Reset to Defaults &nbsp;<i class="fa fa-undo" aria-hidden="true"></i></button>

										<button id="load-config" class="btn btn-default">Load a Config File &nbsp;<i class="fa fa-file-code-o" aria-hidden="true"></i></button>

										<br/><br/>

										<button id="save" class="btn btn-default">Save &nbsp;<i class="fa fa-spinner fa-spin fa-fw save-loading" style="display:none;"></i><i class="fa fa-floppy-o" aria-hidden="true"></i></button>
										<button id="save-as" class="btn btn-default">Save As &nbsp;<i class="fa fa-spinner fa-spin fa-fw save-as-loading" style="display:none;"></i><i class="fa fa-floppy-o" aria-hidden="true"></i></button>
										
										<button id="whitelabel-now" type="submit" class="btn btn-default">Whitelabel &nbsp;<i class="fa fa-magic" aria-hidden="true"></i></button>

										<div id="notification">
											<p class="success">Config saved to assets/config.json.</p>
										</div>

									</div><!--actions-->
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
							            <img id="login-logo" src="images/login.png" alt="" style="width:150px; margin:20px 0 20px 0" />
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
							                <input type="password" id="password" name="_password" class="form-control input-lg" required="" placeholder="Password">
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
							<div id="footer">
								<p>&copy; <?=date('Y');?> <span id="company-name-preview">Mautic</span><span id="footer-prefix-preview">. All Rights Reserved.</span></p>
								<p id="footer-preview"></p>
							</div>
						</div><!--login preview-->
					</div><!--right-->
				</div><!--col 6-->
			</div><!--row-->
		</div><!--container-->
	</div><!--whitelabeler-->
	<!--config open modal-->
    <div class="modal fade" id="open-config" tabindex="-1" role="dialog" aria-labelledby="open-config">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Select a file from assets:</h4>
                </div>
                <div class="modal-body">
                </div>            
            </div>
        </div>
    </div>
    <!--/config open modal-->
	<div id="overlay"><i class="fa fa-spinner fa-spin"></i><span>Loading</span></div>
	<script>
		var mautic_path = '<?=realpath(__DIR__ . '/..');?>';
	</script>
	<style>
	</style>
	<script src="lib/whitelabeler.js"></script>
</body>
</html>
