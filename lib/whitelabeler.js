if (window.location.href.substr(-1,1) == '/') {
    var mautic_url = window.location.href.substr(0,window.location.href.length-1);
} else {
    var mautic_url = window.location.href;
}
var wl = mautic_url.split('/')[mautic_url.split('/').length-1];
var mautic_url = mautic_url.replace(wl, '');
var company_name = $('input#company-name').val();
var footer_prefix = $('input#footer-prefix').val();
var mautic_primary = '#4e5d9d';
var mautic_hover = '#3d497b';
var logo_bg = '#4e5d9d';
var sidebar_bg = '#1d232b';
var sidebar_submenu_bg = '#171c22';
var sidebar_link = '#9e9e9e';
var sidebar_link_hover = '#ffffff';
var active_icon = '#fdb933';
var sidebar_divider = '#202830';
var submenu_bullet_bg = '#222a32';
var submenu_bullet_shadow = '#1a2026';
var divider_left = 50;
var sidebar_logo = null;
var sidebar_logo_width = 130;
var sidebar_logo_margin_top = 10;
var sidebar_logo_margin_left = 0;
var sidebar_logo_margin_right = 0;
var sidebar_logo_margin_left = 0;
var login_logo_width = 150;
var login_logo_margin_top = 20;
var login_logo_margin_bottom = 20;
var login_logo = null;
var favicon = null;

/*
|--------------------------------------------------------------------------
| Update Functions
|--------------------------------------------------------------------------
*/

// mautic_primary
function updateMauticPrimary(color) {
    mautic_primary = color;
    $('.panel-heading, button.btn-default, button#login_button').css({
		'background-color': mautic_primary,
		'border-color': mautic_primary
	});
    $('style#primary').remove();
    $('body').append('<style id="primary">.sidebar a.btn-support { background-color:'+mautic_primary+' !important; border-color: '+mautic_primary+' !important; }</style>');

    $('li.support').css({'box-shadow' : '0 0 30px inset '+mautic_primary, 'border-color' : mautic_primary });
	$('.panel-primary').css('border-color', mautic_primary);
	$('a.mautic-link, a#forgot').css('color', mautic_primary);
    $("input#mautic-primary").spectrum("set", mautic_primary);
}

// mautic_hover
function updateMauticHover(color) {
    mautic_hover = color;
    $('a.mautic-hover').css('color', mautic_hover);
    $('style#button-hover').remove();
    $('body').append('<style id="button-hover">button.btn-default:hover, button#login_button:hover, li.support a.btn-support:hover { background-color:'+mautic_hover+' !important; border-color: '+mautic_hover+' !important; }</style>');
    $("input#mautic-hover").spectrum("set", mautic_hover);
}

// logo_bg
function updateLogoBg(color) {
    logo_bg = color;
    $('div#logo').css('background', logo_bg);
    $("input#logo-bg").spectrum("set", logo_bg);
}

// sidebar_bg
function updateSidebarBg(color) {
	sidebar_bg = color;
	$('div.sidebar, div.sidebar > li > a').css('background-color', color);
    $("input#sidebar-bg").spectrum("set", sidebar_bg);
}

// sidebar_submenu_bg
function updateSidebarSubmenuBg(color) {
    sidebar_submenu_bg = color;
    $('.nav-submenu').css('background-color', sidebar_submenu_bg);
    $("input#sidebar-submenu-bg").spectrum("set", sidebar_submenu_bg);
}

// sidebar_link
function updateSidebarLink(color) {
	sidebar_link = color;
	$('div.sidebar a, li.support p').css('color', color);
    $("input#sidebar-link").spectrum("set", sidebar_link);
}

// sidebar_link_hover
function updateSidebarLinkHover(color) {
	sidebar_link_hover = color;
	$('style#hover').remove();
    $('body').append('<style id="hover">div.sidebar a:hover { color:'+sidebar_link_hover+' !important; }</style>');
    $("input#sidebar-link-hover").spectrum("set", sidebar_link_hover);
}

// active_icon
function updateActiveIcon(color) {
    active_icon = color;
    $('.sidebar span.active-icon, li.support h1').css('color', active_icon);
    $("input#active-icon").spectrum("set", active_icon);
}

// sidebar_divider_color
function updateSidebarDivider(color) {
    sidebar_divider = color;
    $('style#border').remove();
    $('body').append('<style id="border">.sidebar > li > a::after { border-bottom: 1px solid '+sidebar_divider+' !important; } .nav-submenu::after { background-color:'+sidebar_divider+' !important }</style>');
    $("input#sidebar-divider").spectrum("set", sidebar_divider);
}

// submenu_bullet_bg
function updateSubmenuBulletBg(color) {
    submenu_bullet_bg = color;
    $('style#bullet-bg').remove();
    $('body').append('<style id="bullet-bg">.sidebar li .nav-submenu .nav-group::after { background-color: '+submenu_bullet_bg+' !important; }</style>');
    $("input#submenu-bullet-bg").spectrum("set", submenu_bullet_bg);
}

// submenu_bullet_shadow
function updateSubmenuBulletShadow(color) {
    submenu_bullet_shadow = color;
    $('style#bullet-shadow').remove();
    $('body').append('<style id="bullet-shadow">.sidebar li .nav-submenu .nav-group::after { box-shadow: 0 0 0 2px '+submenu_bullet_shadow+' !important; }</style>');
    $("input#submenu-bullet-shadow").spectrum("set", submenu_bullet_shadow);
}

function updateDivderLeft(left) {
    divider_left = left;
    $('input#divider-left').val(divider_left)
    $('style#border-left').remove();
    $('body').append('<style id="border-left">.sidebar > li > a::after { left: '+divider_left+'px !important; }</style>');
}

function updateSidebarLogoWidth(width) {
    sidebar_logo_width = width;
    $('input#sidebar-logo-width').val(sidebar_logo_width);
    $('img#mautic-logo').css('width', sidebar_logo_width+'px');
}

function updateSidebarLogoMarginTop(n) {
    sidebar_logo_margin_top = n;
    $('input#sidebar-logo-margin-top').val(sidebar_logo_margin_top);
    $('img#mautic-logo').css('margin-top', sidebar_logo_margin_top+'px');
}

function updateSidebarLogoMarginLeft(n) {
    sidebar_logo_margin_left = n;
    $('input#sidebar-logo-margin-left').val(sidebar_logo_margin_left);
    $('img#mautic-logo').css('margin-left', sidebar_logo_margin_left+'px');
}

function updateSidebarLogoMarginRight(n) {
    sidebar_logo_margin_right = n;
    $('input#sidebar-logo-margin-right').val(sidebar_logo_margin_right);
    $('img#mautic-logo').css('margin-right', sidebar_logo_margin_right+'px');
}

function updateLoginLogoWidth(width) {
    login_logo_width = width;
    $('input#login-logo-width').val(login_logo_width);
    $('#login_preview img#login-logo, div.mautic-logo').css('width', login_logo_width+'px');
}

function updateLoginLogoMarginTop(n) {
    login_logo_margin_top = n;
    $('input#login-logo-margin-top').val(login_logo_margin_top);
    $('img#login-logo').css('margin-top', login_logo_margin_top+'px');
}

function updateLoginLogoMarginBottom(n) {
    login_logo_margin_bottom = n;
    $('input#login-logo-margin-bottom').val(login_logo_margin_bottom);
    $('img#login-logo').css('margin-bottom', login_logo_margin_bottom+'px');
}

function updateFooterPreview() {
	$('span#company-name-preview').text($('input#company-name').val());
	if ( $('input#footer-prefix').val().length > 0 ) {
		$('span#company-name-preview').text($('input#company-name').val()+'. ');
	} else {
		$('span#company-name-preview').text($('input#company-name').val());
	}
	$('span#footer-prefix-preview').text($('input#footer-prefix').val());
	$('p#footer-preview').html($('input#footer').val());
}


// Load assets/config.json file
function loadJsonConfig(config_file='config.json') {
    
    // Look for config values in assets/config.json
    $.get( window.location.href, { q: 'saved', file: config_file }, function(data) {

        console.log(data);

        if ( data.status == 1 ) {

            if ( data.data.path && data.data.path != '' ) {
                $('input#mautic-path').val(data.data.path);
                updatePath();
            } else {
                $('input#mautic-path').val(mautic_path);
                updatePath();
            }

            // Look for URL
            if ( data.data.url && data.data.url != '' ) {
                $('input#mautic-url').val(data.data.url);
                updateUrl();
            } else {
                $('input#mautic-url').val(mautic_url);
                updateUrl();
            }

            // Look for company name
            if ( data.data.company && data.data.company != '' ) {
                $('input#company-name').val(data.data.company);
                updateFooterPreview();
            }
            
            // Look for footer prefix
            if ( data.data.footer_prefix && data.data.footer_prefix != '' ) {
                $('input#footer-prefix').val(data.data.footer_prefix);
                updateFooterPreview();
            }
            
            // Look for footer
            if ( data.data.footer && data.data.footer != '' ) {
                $('input#footer').val(data.data.footer);
                updateFooterPreview();
            }

            // Primary
            if ( data.data.primary && data.data.primary != '' ) {
                updateMauticPrimary(data.data.primary);
            }

            // Hover
            if ( data.data.hover && data.data.hover != '' ) {
                updateMauticHover(data.data.hover);
            }

            // Logo background
            if ( data.data.logo_bg && data.data.logo_bg != '' ) {
                updateLogoBg(data.data.logo_bg);
            }

            // Sidebar background
            if ( data.data.sidebar_bg && data.data.sidebar_bg != '') {
                updateSidebarBg(data.data.sidebar_bg);
            }

            // Submenu background
            if ( data.data.sidebar_submenu_bg && data.data.sidebar_submenu_bg !='' ) {
                updateSidebarSubmenuBg(data.data.sidebar_submenu_bg);
            }

            // Sidebar links
            if ( data.data.sidebar_link && data.data.sidebar_link != '' ) {
                updateSidebarLink(data.data.sidebar_link);
            }

            // Sidebar links hover
            if ( data.data.sidebar_link_hover && data.data.sidebar_link_hover != '' ) {
                updateSidebarLinkHover(data.data.sidebar_link_hover);
            }

            // Active icon
            if ( data.data.active_icon && data.data.active_icon != '' ) {
                updateActiveIcon(data.data.active_icon);
            }

            // Sidebar divider
            if ( data.data.sidebar_divider && data.data.sidebar_divider != '' ) {
                updateSidebarDivider(data.data.sidebar_divider);
            }

            // Submenu bullet background
            if ( data.data.submenu_bullet_bg && data.data.submenu_bullet_bg != '' ) {
                updateSubmenuBulletBg(data.data.submenu_bullet_bg);
            }

            // Submenu bullet shadow
            if ( data.data.submenu_bullet_shadow && data.data.submenu_bullet_shadow != '' ) {
                updateSubmenuBulletShadow(data.data.submenu_bullet_shadow);
            }

            // Divider left position
            if ( data.data.divider_left && data.data.divider_left != '' ) {
                updateDivderLeft(data.data.divider_left);
            }

            // Sidebar Logo Width
            if ( data.data.sidebar_logo_width && data.data.sidebar_logo_width != '' ) {
                updateSidebarLogoWidth(data.data.sidebar_logo_width);
            }

            // Sidebar Logo Margin Top
            if ( data.data.sidebar_logo_margin_top && data.data.sidebar_logo_margin_top != '' ) {
                updateSidebarLogoMarginTop(data.data.sidebar_logo_margin_top);
            }

            // Sidebar Logo Margin Left
            if ( data.data.sidebar_logo_margin_left && data.data.sidebar_logo_margin_left != '' ) {
                updateSidebarLogoMarginLeft(data.data.sidebar_logo_margin_left);
            }

            // Sidebar Logo Margin Right
            if ( data.data.sidebar_logo_margin_right && data.data.sidebar_logo_margin_right != '' ) {
                updateSidebarLogoMarginRight(data.data.sidebar_logo_margin_right);
            }

            // Login Logo Width
            if ( data.data.login_logo_width && data.data.login_logo_width != '' ) {
                updateLoginLogoWidth(data.data.login_logo_width);
            }

            // Login Logo Margin Top
            if ( data.data.login_logo_margin_top && data.data.login_logo_margin_top != '' ) {
                updateLoginLogoMarginTop(data.data.login_logo_margin_top);
            }

            // Login Logo Margin Bottom
            if ( data.data.login_logo_margin_bottom && data.data.login_logo_margin_bottom != '' ) {
                updateLoginLogoMarginBottom(data.data.login_logo_margin_bottom);
            }


			if( window.location.href.substr(window.location.href.length-1 ) === '/') {
				var wl_url = window.location.href.substr(0, window.location.href.length - 1);
			} else {
				var wl_url = window.location.href;
			}

            // Check for sidebar logo image
            if (data.data.sidebar_logo) {
	            				
	            	// Check to see if the file is really there
			    $.get( window.location.href, {q:'asset', url:encodeURIComponent(wl_url+'/assets/'+data.data.sidebar_logo)}).done(function(d){

	                if ( d == 1 ) {
	                    $('img#mautic-logo').attr('src', 'assets/'+data.data.sidebar_logo);
	                    $('div#sidebar-logo-upload').hide();
	                    $('div#sidebar-logo-upload').val(null);
	                    $('div#sidebar-logo-loaded span').text('assets/'+data.data.sidebar_logo);
	                    $('div#sidebar-logo-loaded').show();
	                    $('input#sidebar-logo-file').val('');
	                    sidebar_logo = data.data.sidebar_logo;
	                } else {
	                    sidebar_logo = null;
	                    $('#sidebar-logo-file').val(null);
	                    $('img#mautic-logo').attr('src', 'images/sidebar.png');
	                    $('div#sidebar-logo-loaded').hide();
	                    $('div#sidebar-logo-upload').fadeIn();
	                    $('span.sidebar-logo-error').text(data.data.sidebar_logo+' is defined in config.json, but was not found in the assets folder.').fadeIn();   
	                }

			    });

            }

            // Check for login logo image
            if (data.data.login_logo) {

	            $.get( window.location.href, {q:'asset', url:encodeURIComponent(wl_url+'/assets/'+data.data.login_logo)}).done(function(d){

	                if ( d == 1 ) {
	                    $('img#login-logo').attr('src', 'assets/'+data.data.login_logo);
	                    $('div#login-logo-upload').hide();
	                    $('div#login-logo-loaded span').text('assets/'+data.data.login_logo);
	                    $('div#login-logo-loaded').show();
	                    $('input#login-logo-file').val('');
	                    login_logo = data.data.login_logo;            
	                } else {
	                    login_logo = null;
	                    $('#login-logo-file').val(null);
	                    $('img#login-logo').attr('src', 'images/login.png');
	                    $('div#login-logo-loaded').hide();
	                    $('div#login-logo-upload').fadeIn();
	                    $('span.login-logo-error').text(data.data.login_logo+' is defined in config.json, but was not found in the assets folder.').fadeIn();                    
	                }
                
                });

            }

            // Check for favicon image
            if (data.data.favicon) {
			
				$.get( window.location.href, {q:'asset', url:encodeURIComponent(wl_url+'/assets/'+data.data.favicon)}).done(function(d){

	                if (d == 1) {
	                    $('img#favicon-preview').attr('src', 'assets/'+data.data.favicon);
	                    $('link[rel="icon"]').attr('href', 'assets/'+data.data.favicon);
	                    $('div#favicon-upload').hide();
	                    $('div#favicon-loaded span').text('assets/'+data.data.favicon);
	                    $('div#favicon-loaded').show();
	                    $('input#favicon-file').val('');
	                    favicon = data.data.favicon;   
	                } else {
	                    favicon = null;
	                    $('#favicon-file').val(null);
	                    $('img#favicon-preview').attr('src', 'images/favicon.ico');
	                    $('div#favicon-loaded').hide();
	                    $('div#favicon-upload').fadeIn();
	                    $('span.favicon-error').text(data.data.favicon+' is defined in config.json, but was not found in the assets folder.').fadeIn();                    
	                }
                
                });

            }

            $('div#overlay').fadeOut();

        } else {

            // Set default values
            $('input#mautic-path').val(mautic_path);
            $('input#mautic-url').val(mautic_url);
            $('input#mautic-primary').val(mautic_primary);
            $('input#mautic-hover').val(mautic_hover);
            $('input#logo-bg').val(logo_bg);
            $('input#sidebar-bg').val(sidebar_bg);
            $('input#sidebar-submenu-bg').val(sidebar_submenu_bg);
            $('input#sidebar-link').val(sidebar_link);
            $('input#sidebar-link-hover').val(sidebar_link_hover);
            $('input#sidebar-divider').val(sidebar_divider);
            $('input#active-icon').val(active_icon);
            $('input#submenu-bullet-bg').val(submenu_bullet_bg);
            $('input#submenu-bullet-shadow').val(submenu_bullet_shadow);
            $('input#divider-left').val(divider_left);
            $('input#sidebar-logo-width').val(sidebar_logo_width);
            $('input#sidebar-margin-top').val(sidebar_logo_margin_top);
            $('input#sidebar-margin-left').val(sidebar_logo_margin_left);
            $('input#sidebar-margin-right').val(sidebar_logo_margin_right);
            $('input#login-logo-width').val(login_logo_width);
            $('input#login-logo-margin-top').val(login_logo_margin_top);
            $('input#login-logo-margin-bottom').val(login_logo_margin_bottom);
            
            	updatePath();
            	updateUrl();
            	
            $('div#overlay').fadeOut();

        }
    });
}

/*
|--------------------------------------------------------------------------
| Color Dropdown Events
|--------------------------------------------------------------------------
*/

// mautic_primary
$("input#mautic-primary").on('move.spectrum change.spectrum', function(e, tinycolor) {
    updateMauticPrimary(tinycolor.toHexString());
});

// mautic_hover
$("input#mautic-hover").on('move.spectrum change.spectrum', function(e, tinycolor) {
	updateMauticHover(tinycolor.toHexString());
});

// logo_bg
$("input#logo-bg").on('move.spectrum change.spectrum', function(e, tinycolor) {
    updateLogoBg(tinycolor.toHexString());
});

// sidebar_bg
$("input#sidebar-bg").on('move.spectrum change.spectrum', function(e, tinycolor) {
    updateSidebarBg(tinycolor.toHexString());
});

// sidebar_submenu_bg
$("input#sidebar-submenu-bg").on('move.spectrum change.spectrum', function(e, tinycolor) {
    updateSidebarSubmenuBg(tinycolor.toHexString());
});

// sidebar_link
$("input#sidebar-link").on('move.spectrum change.spectrum', function(e, tinycolor) {
	updateSidebarLink(tinycolor.toHexString());
});

// sidebar_link_hover
$("input#sidebar-link-hover").on('move.spectrum change.spectrum', function(e, tinycolor) {
	updateSidebarLinkHover(tinycolor.toHexString());
});

// active_icon
$("input#active-icon").on('move.spectrum change.spectrum', function(e, tinycolor) {
	updateActiveIcon(tinycolor.toHexString());
});

// sidebar_divider
$("input#sidebar-divider").on('move.spectrum change.spectrum', function(e, tinycolor) {
	updateSidebarDivider(tinycolor.toHexString());
});

// submenu_bullet_bg
$("input#submenu-bullet-bg").on('move.spectrum change.spectrum', function(e, tinycolor) {
    updateSubmenuBulletBg(tinycolor.toHexString());
});

// submenu_bullet_shadow
$("input#submenu-bullet-shadow").on('move.spectrum change.spectrum', function(e, tinycolor) {
    updateSubmenuBulletShadow(tinycolor.toHexString());
});


/*
|--------------------------------------------------------------------------
| Input change event to look for Mautic compatible version
|--------------------------------------------------------------------------
*/

var validPath = false;
function updatePath() {
    $('small.path-fail, small.path-success, #path-loading').hide();
    $('input#mautic-path').prop( "disabled", true );
    $('#path-loading').show();
    $.get( window.location.href, { q: 'version', path: $('input#mautic-path').val() } ).done(function(data) {

        console.log(data);
        $('input#mautic-path').prop( "disabled", false );
        $('#path-loading').hide();

		if ( data.status == 1 ) {

            validPath = true;
			$($('input#mautic-path')).parent('div').parent('div').children('small.path-success').text(data.message).fadeIn();

		} else {

			validPath = false;
			$($('input#mautic-path')).parent('div').parent('div').children('small.path-fail').text(data.message).fadeIn();

		}
	});
}

$('body').on('change', 'input#mautic-path', function() {
    updatePath();
});


/*
|--------------------------------------------------------------------------
| Input change event to look for Mautic URL.
|--------------------------------------------------------------------------
*/

var validDomain = false;
function updateUrl() {

	$('small.url-fail, small.url-success, #url-loading').hide();
    $('input#mautic-url').prop( "disabled", true );
    $('#url-loading').show();

    $.get( window.location.href, { q: 'url', url: encodeURIComponent($('input#mautic-url').val()) } ).done(function(data){

        console.log(data);
        $('input#mautic-url').prop( "disabled", false );
        $('#url-loading').hide();

		if ( data.status == 1 ) {

			validDomain = true;
			$($('input#mautic-url')).parent('div').parent('div').children('small.url-success').text(data.message).fadeIn();
                $('div#overlay').fadeOut();

		} else {

			validDomain = false;
			$($('input#mautic-url')).parent('div').parent('div').children('small.url-fail').text(data.message).fadeIn(function(){
                $('div#overlay').fadeOut();
            });

		}
    });

}
$('body').on('change', 'input#mautic-url', function() {
    updateUrl();
});


/*
|--------------------------------------------------------------------------
| Event listener for company/footer fields
|--------------------------------------------------------------------------
*/
$('body').on('keyup', 'input#company-name, input#footer-prefix, input#footer', function() {
    updateFooterPreview();
});


/*
|--------------------------------------------------------------------------
| Read image input
|--------------------------------------------------------------------------
*/

function readImg(input) {

	var element = input;

	if (input.files && input.files[0]) {

	    var reader = new FileReader();

	    reader.onload = function (e) {

		    	if ( $(input).attr('id') == 'sidebar-logo-file' ) {
	
			        $('img#mautic-logo').attr('src', e.target.result);
			        $('img#mautic-logo').css('margin', 0);
	
		    	} else if ( $(input).attr('id') == 'login-logo-file' ) {
	
			        $('img#login-logo').attr('src', e.target.result);
			        $('img#login-logo').css('margin', 0);
	
	                if ( $('#favicon-file')[0].files.length != 1 && favicon == null ) {
	
	                    $('img#favicon-preview').attr('src', e.target.result);
	    		        $('link[rel="icon"]').attr('href', e.target.result);
	
	                }
	
		    	} else if (  $(input).attr('id') == 'favicon-file'  ) {
	
			        $('img#favicon-preview').attr('src', e.target.result);
			        $('link[rel="icon"]').attr('href', e.target.result);
	
		    	}
		    	
	    }

	    reader.readAsDataURL(input.files[0]);
	}
}


/*
|--------------------------------------------------------------------------
| Show live preview of image
|--------------------------------------------------------------------------
*/

$("input#sidebar-logo-file, input#login-logo-file, input#favicon-file").change(function(){

	readImg(this);

	if ( $(this).attr('id') == 'sidebar-logo-file' ) {

		$('#sidebar-logo-margin-top').val(0);
        $('.sidebar-logo-error').hide();

	} else if ( $(this).attr('id') == 'login-logo-file' ) {

		$('#login-logo-margin-top, #login-logo-margin-bottom').val(0);
        $('.login-logo-error').hide();

	} else if ( $(this).attr('id') == 'favicon-file' ) {

        $('#favicon-preview').val(0);
        $('.favicon-error').hide();
    }

});


/*
|--------------------------------------------------------------------------
| Event listener for sidebar logo width preview
|--------------------------------------------------------------------------
*/

$('body').on('keyup change', 'input#sidebar-logo-width', function(){
	$('img#mautic-logo').css('width', $(this).val()+'px');
});


/*
|--------------------------------------------------------------------------
| Event listener for login logo width preview
|--------------------------------------------------------------------------
*/

$('body').on('keyup change', 'input#login-logo-width', function(){
	$('#login_preview img#login-logo, div.mautic-logo').css('width', $(this).val()+'px');
});


/*
|--------------------------------------------------------------------------
| Event listener for logo margins
|--------------------------------------------------------------------------
*/

// Sidebar logo top margin
$('body').on('keyup change', 'input#sidebar-logo-margin-top', function(){
    $('img#mautic-logo').css('margin-top', $(this).val()+'px');
});

// Sidebar logo left margin
$('body').on('keyup change', 'input#sidebar-logo-margin-left', function(){
    $('img#mautic-logo').css('margin-left', $(this).val()+'px');
});

// Sidebar logo right margin
$('body').on('keyup change', 'input#sidebar-logo-margin-right', function(){
    $('img#mautic-logo').css('margin-right', $(this).val()+'px');
});

// Login logo top margin
$('body').on('keyup change', 'input#login-logo-margin-top', function(){
    $('img#login-logo').css('margin-top', $(this).val()+'px');
});

// Login logo top margin
$('body').on('keyup change', 'input#login-logo-margin-bottom', function(){
    $('img#login-logo').css('margin-bottom', $(this).val()+'px');
});

/*
|--------------------------------------------------------------------------
| Event listener for divider left position
|--------------------------------------------------------------------------
*/

$('body').on('keyup change', 'input#divider-left', function(){
    updateDivderLeft($(this).val());
});


/*
|--------------------------------------------------------------------------
| Event listener for clicking config json in modal
|--------------------------------------------------------------------------
*/

$('body').on('click', '#open-config .modal-body a', function(e){
    e.preventDefault();
	loadJsonConfig($(this).attr('data-file'));
	$('#open-config').modal('hide');
});


/*
|--------------------------------------------------------------------------
| Build config object to upload as JSON file
|--------------------------------------------------------------------------
*/

function buildConfig() {
    var config = {
        "path" : $('input#mautic-path').val(),
        "url" : $('input#mautic-url').val(),
        "company" : $('input#company-name').val(),
        "footer_prefix" : $('input#footer-prefix').val(),
        "footer" : $('input#footer').val(),
        "primary" : $('input#mautic-primary').val(),
        "hover" : $('input#mautic-hover').val(),
        "logo_bg" : $('input#logo-bg').val(),
        "sidebar_bg" : $('input#sidebar-bg').val(),
        "sidebar_submenu_bg" : $('input#sidebar-submenu-bg').val(),
        "sidebar_link" : $('input#sidebar-link').val(),
        "sidebar_link_hover" : $('input#sidebar-link-hover').val(),
        "active_icon" : $('input#active-icon').val(),
        "sidebar_divider" : $('input#sidebar-divider').val(),
        "divider_left" : $('input#divider-left').val(),
        "submenu_bullet_bg" : $('input#submenu-bullet-bg').val(),
        "submenu_bullet_shadow" : $('input#submenu-bullet-shadow').val(),
        "sidebar_logo" : sidebar_logo,
        "sidebar_logo_width" : $('input#sidebar-logo-width').val(),
        "sidebar_logo_margin_top" : $('input#sidebar-logo-margin-top').val(),
        "sidebar_logo_margin_right" : $('input#sidebar-logo-margin-right').val(),
        "sidebar_logo_margin_left" : $('input#sidebar-logo-margin-left').val(),
        "login_logo" : login_logo,
        "login_logo_width" : $('input#login-logo-width').val(),
        "login_logo_margin_top" : $('input#login-logo-margin-top').val(),
        "login_logo_margin_bottom" : $('input#login-logo-margin-bottom').val(),
        "favicon" : favicon
    };
    return config;
}

/*
|--------------------------------------------------------------------------
| Upload images and POST JSON file
|--------------------------------------------------------------------------
*/

function saveConfigJson(file=false) {
	
    $('div#overlay').show();
    $('button i.fa-floppy-o').hide();
    $('#notification p').removeClass('success fail').hide();
    $('button i.save-loading').show();	
	
	// Save images
	images = new FormData();
    
    // Sidebar logo file is ready to upload
    if ( $('#sidebar-logo-file')[0].files.length == 1 ) {
        sidebar_logo = $('#sidebar-logo-file')[0].files[0].name;
        images.append('sidebar_logo_file', $('#sidebar-logo-file')[0].files[0]);
        images.append('sidebar_logo_width', $('#sidebar-logo-width').val());
    }
	
	// Login logo is ready to upload
    if ( $('#login-logo-file')[0].files.length == 1 ) {
        images.append('login_logo_file', $('#login-logo-file')[0].files[0]);
        images.append('login_logo_width', $('#login-logo-width').val());
        login_logo = $('#login-logo-file')[0].files[0].name;
    }

	// Favicon is ready to upload
    if ( $('#favicon-file')[0].files.length == 1 ) {
	    
        images.append('favicon_file', $('input#favicon-file')[0].files[0]);
        	
        	if ( $('#favicon-file')[0].files[0].name.split('.')[1].toLowerCase() == 'ico' ) {    	
	        	favicon = $('#favicon-file')[0].files[0].name;
        	} else {        	
	        	favicon = $('#favicon-file')[0].files[0].name.split('.')[0]+'.ico';
        	}
        
    // Favicon is not set, check if login logo is ready to upload and use 
    } else {
        
        if (favicon) {
            images.append('favicon_saved', 1);
        } else if ( login_logo ) {
	        // Set favicon to use login logo (which will be converted to an .ico)
            if ( $('#login-logo-file')[0].files.length == 1 ) {
                favicon = 'favicon-'+$('#login-logo-file')[0].files[0].name.split('.')[0]+'.ico';
            } else {
                favicon = 'favicon-'+login_logo.split('.')[0]+'.ico';
            }
        }
    }

	// There's an image form set, let's upload them.
    if ( $('#sidebar-logo-file')[0].files.length > 0 || $('#login-logo-file')[0].files.length > 0 || $('#favicon-file')[0].files.length > 0 ) {

        $.ajax({
            url: window.location.href+'?q=save-images',
            type: 'POST',
            data: images,
            cache: false,
            contentType: false,
            processData: false,
            xhr: function() {
                var myXhr = $.ajaxSettings.xhr();
                if (myXhr.upload) {
                    myXhr.upload.addEventListener('progress', function(e) {
                        if (e.lengthComputable) {
                            console.log(e.loaded / e.total);
                        }
                    }, false);
                }
                return myXhr;
            },
            success: function(result) {

                if ( result.status == 1 ) {
    
					console.log(result);

					var config = buildConfig();
					
					if (file != false) {
    					
    				    $.post(window.location.href+'?q=save-as', {
        				    	'file' : file,
    				        'config' : config
    				    }, function(data) {
    				        if (data) {
    				            $('button i.save-loading').hide();
    				            $('button i.fa-floppy-o').show();
    				            $('#notification p').text(data.message).addClass('success').fadeIn();
    				            loadJsonConfig();
    				        } else {
    				            console.log('There was an error saving your data. Please review your server error log.')
    				        }
    				    });
    					
					} else {

	        			    $.post(window.location.href+'?q=save', {
	        			        'config' : config
	        			    }, function(data) {
	        			        if (data) {
	        			            $('button i.save-loading').hide();
	        			            $('button i.fa-floppy-o').show();
	        			            $('#notification p').text(data.message).addClass('success').fadeIn();
	        			            loadJsonConfig();
	        			        } else {
	        			            console.log('There was an error saving your data. Please review your server error log.')
	        			        }
	        			    });
			
					}
				
                } else {
	                console.log(result);
                    console.log(result.message[0]);
                }

            }
        });

	// We don't need to upload images
    } else {
	       
		var config = buildConfig();
	
		if (file != false) {
			
		    $.post(window.location.href+'?q=save-as', {
			    	'file' : file,
		        'config' : config
		    }, function(data) {
		        if (data) {
		            $('button i.save-loading').hide();
		            $('button i.fa-floppy-o').show();
		            $('#notification p').text(data.message).addClass('success').fadeIn();
		            loadJsonConfig(file+'.json');
		        } else {
		            console.log('There was an error saving your data. Please review your server error log.')
		        }
		    });
			
		} else {
	
		    $.post(window.location.href+'?q=save', {
		        'config' : config
		    }, function(data) {
		        if (data) {
	                $('button i.save-loading').hide();
	                $('button i.fa-floppy-o').show();
	                $('#notification p').text(data.message).addClass('success').fadeIn();
	                loadJsonConfig();
		        } else {
		            console.log('There was an error saving your data. Please review your server error log.')
		        }
		
		    });
		}
		
    }
	
}

// Save button event listener
$('body').on('click', 'button#save', function(e){
    e.preventDefault();
	saveConfigJson();
});

// Save button event listener
$('body').on('click', 'button#save-as', function(e){
    e.preventDefault();
    var filename = prompt('Give this configuration a name (don\'t include the .json extension):');
    if (filename) {
		saveConfigJson(filename);
	}
});


// Run save function when pressint ctrl/cmd + s
$(window).bind('keydown', function(e) {
    if (e.ctrlKey || e.metaKey) {
        switch (String.fromCharCode(e.which).toLowerCase()) {
        case 's':
            e.preventDefault();
            saveConfig();
            break;
        }
    }
});


/*
|--------------------------------------------------------------------------
| Clear console
|--------------------------------------------------------------------------
*/

function clearConsole() {
	$('div#console p').css('display', 'none');
}


/*
|--------------------------------------------------------------------------
|  Run whitelabeling process
|--------------------------------------------------------------------------
*/

function whiteLabel() {
	
	$('.spinner').fadeIn();
	clearConsole();
	var path = $('input#mautic-path').val();
	
	// Waiting for input
	$('#waiting-for-input').show();
	$('#waiting-for-input span.dots').removeClass('blink');
	$('#waiting-for-input-success').fadeIn();
	
	// Find Mautic installation
	$('#looking-for-installation').fadeIn();
	$.get( window.location.href, { q: 'version', path:path }, function(versionData){
		
		console.log(versionData);
		
		// version data success
		if (versionData != 0) {
			$("#console").animate({ scrollTop: $('#console').prop("scrollHeight")}, 500);
			$('#looking-for-installation-success').fadeIn();
			
			// css stuff
			$('#updating-css').fadeIn();
			$('#updating-css span.dots').addClass('blink');
			
			$.post(window.location.href+'?q=css', {
				path: path,
				version: versionData.version,
				logo_bg: logo_bg,
				primary: mautic_primary,
				hover: mautic_hover,
				sidebar_bg: sidebar_bg,
				sidebar_submenu_bg: sidebar_submenu_bg,
				sidebar_link: sidebar_link,
				sidebar_link_hover: sidebar_link_hover,
				active_icon: active_icon,
				divider_left: divider_left,
				sidebar_divider: sidebar_divider,
				submenu_bullet_bg: submenu_bullet_bg,
				submenu_bullet_shadow: submenu_bullet_shadow
			}, function(cssData) {
				
				console.log(cssData);
				
				if (cssData) {
					
					// css data success
					if(cssData.status==1) {
						
						$("#console").animate({ scrollTop: $('#console').prop("scrollHeight")}, 500);
						$('#updating-css span.dots').removeClass('blink');
						$('#updating-css-success').fadeIn();
						
						// update company name
						$('#updating-companyname').fadeIn();
						$('#updating-companyname span.dots').addClass('blink');
						
						$.post(window.location.href+'?q=companyname', {
							path: path,
							version:versionData.version,
							company_name: $('input#company-name').val(),
							footer_prefix: $('input#footer-prefix').val(),
							footer: $('input#footer').val()
						}, function(companyNameData) {
							
							console.log(companyNameData);
							
							// Success
							if (companyNameData.status == 1) {
								
								$("#console").animate({ scrollTop: $('#console').prop("scrollHeight")}, 500);
								$('#updating-companyname span.dots').removeClass('blink');
								$('#updating-companyname-success').fadeIn();
								
								// Update logos
								
								$('#updating-images').fadeIn();
								$('#updating-images span.dots').addClass('blink');
								data = new FormData();
								data.append('mautic_path', path);
								data.append('mautic_url', $('#mautic-url').val());
								data.append('version', versionData.version);
								
								// Check for saved values
								
								if ( sidebar_logo == null ) {
									data.append('sidebar_logo_file', $('#sidebar-logo-file')[0].files[0]);
								} else {
									data.append('sidebar_logo_file', sidebar_logo);
								}
								
								if ( login_logo == null ) {
									data.append('login_logo_file', $('#login-logo-file')[0].files[0]);
								} else {
									data.append('login_logo_file', login_logo);
								}

								if ( favicon == null && $('#favicon-file')[0].files[0] ) {
									
									data.append('favicon_file', $('#favicon-file')[0].files[0]);
																	
								} else {
									data.append('favicon_file', favicon);
								}
								
								data.append('sidebar_logo_width', $('#sidebar-logo-width').val());
								data.append('sidebar_logo_margin_top', $('#sidebar-logo-margin-top').val());
								data.append('sidebar_logo_margin_right', $('#sidebar-logo-margin-right').val());
								data.append('sidebar_logo_margin_left', $('#sidebar-logo-margin-left').val());
							    data.append('login_logo_width', $('#login-logo-width').val());
							    data.append('login_logo_margin_top', $('#login-logo-margin-top').val());
							    data.append('login_logo_margin_bottom', $('#login-logo-margin-bottom').val());
 
							    $.ajax({
							        url: window.location.href+'?q=logos',
							        type: 'POST',
							        data: data,
							        cache: false,
							        contentType: false,
							        processData: false,
							        xhr: function() {
							            var myXhr = $.ajaxSettings.xhr();
							            if (myXhr.upload) {
							                myXhr.upload.addEventListener('progress', function(e) {
							                    if (e.lengthComputable) {
							                    	console.log(e.loaded / e.total);
							                    }
							                }, false);
							            }
							            return myXhr;
							        },
							        success: function(imageData) {
								        
								        console.log(imageData);
								        
								        if ( imageData.status == 1 ) {
									        
								        		$('#updating-images span.dots').removeClass('blink');
											$('#updating-images-success').fadeIn();
											$("#console").animate({ scrollTop: $('#console').prop("scrollHeight")}, 500);   
											
								        } else {
									        
									        $('#updating-images-error span.error').append(': Could not update images. See console for errors.');   
								        		$('#updating-images span.dots').removeClass('blink');
											$('#updating-images-error').fadeIn();
											$("#console").animate({ scrollTop: $('#console').prop("scrollHeight")}, 500); 
								        }
								        
										// Regenerate Muatic Assets
										
										$('#regenerating').fadeIn();
										$('#regenerating span.dots').addClass('blink');
										
										$.get(window.location.href, { q: "assets", assets: "regenerate", path: path } )
										.done(function(assetsData) {
											
											$('#regenerating span.dots').removeClass('blink');
											$('#regenerating-success').fadeIn();
											$("#console").animate({ scrollTop: $('#console').prop("scrollHeight")}, 500);
										    
										    // Clearing Cache
										    $('#clearing span.dots').addClass('blink');
										    $('#clearing').fadeIn();
										    
											$.get(window.location.href, { q: "assets", assets: "clear", path: path } )
											.done(function(assetsData) {
												
											  	$('#clearing span.dots').removeClass('blink');
											  	$('#clearing-success').fadeIn();
											  	$("#console").animate({ scrollTop: $('#console').prop("scrollHeight")}, 500);
											    
											    // Complete!
											  	$('#complete').fadeIn();
											  	$("#console").animate({ scrollTop: $('#console').prop("scrollHeight")}, 500);
											  	$('.spinner').fadeOut();
											});
										});
							        }
							    });
							// Error
							} else {
								$('#updating-companyname-error').append(': Could not update company name. See console for errors.').fadeIn();
								$('.spinner').fadeOut();
							}
						}); // post company name data

					// css data error
					} else {
						$('#updating-css-error').append(': '+cssData.message).fadeIn();
						$('.spinner').fadeOut();
					}
				// css data error
				} else {
					$('#updating-css-error').fadeIn();
					$('.spinner').fadeOut();
				}
			}); // post css data

		} else {
			// Error finding Installation
			$('#looking-for-installation-error').fadeIn();
			$('.spinner').fadeOut();
		}
	}); // get version data
}

// Event handler for "start whitelabeling" button - Do some validation before running whiteLabel()
$('button#whitelabel-now').click(function(e){
	e.preventDefault();
	if (validPath != true) {
		alert('Couldn\'t find Mautic. Make sure your path is correct.')
		return false;
	}
	if ( validDomain != true ) {
		alert('Couldn\'t find Mautic at the URL you defined.');
		return false;
	}
	if ( $('input#company-name').val() == '' ) {
		alert('Enter a company name.');
		return false;
	}
	if ( !sidebar_logo && $('input#sidebar-logo-file')[0].files.length == 0 ) {
		alert('You have to choose a sidebar logo image.');
		return false;
	}
	if ( !login_logo && $('input#login-logo-file')[0].files.length == 0 ) {
		alert('You have to choose a login logo image.');
		return false;
	}
	$('.spinner').fadeIn();
	whiteLabel();
});

// Fix Window Affix
$(window).scroll(function () {
    $('#right').width('100%');
});

// Reset Colors to Deafults
$('button#reset').click(function(e){
    e.preventDefault();
    updateMauticPrimary('#4e5d9d');
    updateMauticHover('#3d497b');
    updateLogoBg('#4e5d9d');
    updateSidebarBg('#1d232b');
    updateSidebarSubmenuBg('#171c22');
    updateSidebarLink('#9e9e9e');
    updateSidebarLinkHover('#ffffff');
    updateActiveIcon('#fdb933');
    updateSidebarDivider('#202830');
    updateSubmenuBulletBg('#222a32');
    updateSubmenuBulletShadow('#1a2026');
    updateDivderLeft(50);
    updateSidebarLogoWidth(130);
    updateSidebarLogoMarginTop(10);
    updateSidebarLogoMarginLeft(0);
    updateSidebarLogoMarginRight(0);
    updateLoginLogoWidth(150);
    updateLoginLogoMarginTop(20);
    updateLoginLogoMarginBottom(20);
    $('input#company-name').val('Mautic');
    $('input#footer-prefix').val('All Rights Reserved.');
    $('input#footer').val('');
    updateFooterPreview();
    sidebar_logo = null;
    $('#sidebar-logo-file').val(null);
    $('img#mautic-logo').attr('src', 'images/sidebar.png');
    $('div#sidebar-logo-loaded').hide();
    $('div#sidebar-logo-upload').fadeIn();
    $('span.sidebar-logo-error').hide();
    login_logo = null;
    $('#login-logo-file').val(null);
    $('img#login-logo').attr('src', 'images/login.png');
    $('div#login-logo-loaded').hide();
    $('div#login-logo-upload').fadeIn();
    $('span.login-logo-error').hide();
    favicon = null;
    $('#favicon-file').val(null);
    $('img#favicon-preview').attr('src', 'images/favicon.ico');
    $('link[rel="icon"]').attr('href', 'images/favicon.ico');
    $('div#favicon-loaded').hide();
    $('div#favicon-upload').fadeIn();
    $('span.favicon-error').hide();
});

// Reset Colors to Deafults
$('button#load-config').click(function(e){
    e.preventDefault();
	$.get( window.location.href, { q: 'config-files' }, function(config_files) {
		console.log(config_files);
		$('#open-config .modal-body').html('');
		if ( config_files.length > 0 ) {
			$.each(config_files, function(k,v){
	    		$('#open-config .modal-body').append('<a href="'+window.location.href+'/assets/'+v+'" data-file="'+v+'"><i class="fa fa-file-code-o" aria-hidden="true"></i>&nbsp; '+v+'</a>');
			});
		} else {
			$('#open-config .modal-body').append('<p>No files found.</p>');
		}
		$('#open-config').modal('show');
    });
    
});

// Event listener for closing loaded logos
$('div#sidebar-logo-loaded a, div#login-logo-loaded a, div#favicon-loaded a').click(function(e){
    e.preventDefault();
    $(this).parent().parent().hide();
    $(this).parent().parent().prev('div').fadeIn();
    if ( $(this).parent().parent().attr('id') == 'sidebar-logo-loaded' ) {
        sidebar_logo = null;
        $('#sidebar-logo-file').val('');
        $('img#mautic-logo').attr('src', 'images/sidebar.png');
    } else if ( $(this).parent().parent().attr('id') == 'login-logo-loaded' ) {
        login_logo = null;
        $('#login-logo-file').val('');
        $('img#login-logo').attr('src', 'images/login.png');
    } else if ( $(this).parent().parent().attr('id') == 'favicon-loaded' ) {
        favicon = null;
        $('#favicon-file').val('');
        $('img#favicon-preview').attr('src', 'images/favicon.ico');
        $('link[rel="icon"]').attr('href', 'images/favicon.ico');
    }
});


/*
|--------------------------------------------------------------------------
| Document Loaded
|--------------------------------------------------------------------------
*/

$(document).ready(function(){

    // Start terminal message
	$('#waiting-for-input').fadeIn();
    $('#waiting-for-input span.dots').addClass('blink');

    // Show/hide support message based on window height
    if (window.innerHeight > 750 ) {
        $('li.support').fadeIn(2000);
    }
    $( window ).resize(function() {
        if (window.innerHeight > 750 ) {
            $('li.support').fadeIn(2000);
        } else {
            $('li.support').fadeOut(500);
        }
    });

    // Initiate Color Dropdowns
    $("input#mautic-primary").spectrum({
        preferredFormat: "hex",
        showInput: true,
        color: mautic_primary,
        showButtons: false
    });
    $("input#mautic-hover").spectrum({
        preferredFormat: "hex",
        showInput: true,
        color: mautic_hover,
        showButtons: false
    });
    $("input#logo-bg").spectrum({
        preferredFormat: "hex",
        showInput: true,
        color: logo_bg,
        showButtons: false
    });
    $("input#sidebar-bg").spectrum({
        preferredFormat: "hex",
        showInput: true,
        color: sidebar_bg,
        showButtons: false
    });
    $("input#sidebar-submenu-bg").spectrum({
        preferredFormat: "hex",
        showInput: true,
        color: sidebar_submenu_bg,
        showButtons: false
    });
    $("input#sidebar-link").spectrum({
        preferredFormat: "hex",
        showInput: true,
        color: sidebar_link,
        showButtons: false
    });
    $("input#sidebar-link-hover").spectrum({
        preferredFormat: "hex",
        showInput: true,
        color: sidebar_link_hover,
        showButtons: false
    });
    $("input#active-icon").spectrum({
        preferredFormat: "hex",
        showInput: true,
        color: active_icon,
        showButtons: false
    });
    $("input#sidebar-divider").spectrum({
        preferredFormat: "hex",
        showInput: true,
        color: sidebar_divider,
        showButtons: false
    });
    $("input#submenu-bullet-bg").spectrum({
        preferredFormat: "hex",
        showInput: true,
        color: submenu_bullet_bg,
        showButtons: false
    });
    $("input#submenu-bullet-shadow").spectrum({
        preferredFormat: "hex",
        showInput: true,
        color: submenu_bullet_shadow,
        showButtons: false
    });

	// Affix console/login preview for large screens
	if (window.innerWidth >= 995) {
		$('#right').affix({
		  offset: {
		    top: 40,
		  }
		});
	}

    // Load data from assets/config.json
    loadJsonConfig();

}); // $(document).ready
