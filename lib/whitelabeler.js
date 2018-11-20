
var validPath = false;
var validDomain = false;

/*
|--------------------------------------------------------------------------
| Initialize color dropdowns
|--------------------------------------------------------------------------
*/
$("input.sidebar_background").spectrum({
    preferredFormat: "hex",
    showInput: true,
    color: sidebar_background,
    showButtons: false
});

$("input.mautic_primary").spectrum({
    preferredFormat: "hex",
    showInput: true,
    color: mautic_primary,
    showButtons: false
});

$("input.mautic_hover").spectrum({
    preferredFormat: "hex",
    showInput: true,
    color: mautic_hover,
    showButtons: false
});

/*
|--------------------------------------------------------------------------
| Color dropdown events
|--------------------------------------------------------------------------
*/
$("input.sidebar_background").on('move.spectrum change.spectrum', function(e, tinycolor) {
	$('div#logo').css('background', tinycolor.toHexString());
	sidebar_background = tinycolor.toHexString();
});

$("input.mautic_primary").on('move.spectrum change.spectrum', function(e, tinycolor) {
	$('.panel-heading, button.btn-default, button#login_button').css({
		'background-color': tinycolor.toHexString(),
		'border-color': tinycolor.toHexString()
	});
	$('.panel-primary').css('border-color', tinycolor.toHexString());
	$('a.mautic-link, a#forgot').css('color', tinycolor.toHexString());
	mautic_primary = tinycolor.toHexString();
});

$("input.mautic_hover").on('move.spectrum change.spectrum', function(e, tinycolor) {
	$('a.mautic-hover').css('color', tinycolor.toHexString());
	mautic_hover = tinycolor.toHexString();
});

/*
|--------------------------------------------------------------------------
| Input change event to look for Mautic compatible version
|--------------------------------------------------------------------------
*/
$('body').on('change', 'input#mautic-path', function() {
	var element = $(this);
	$('small.path-fail, small.path-success').hide();
	$.get( window.location.href, { q: 'version', path: $('input#mautic-path').val() }, function(data) {
		if ( data == 0 ) {
			validPath = false;
			$(element).parent('div').children('small.path-fail').fadeIn();
		} else {
			$('span.version').text(data);
			validPath = true;
			$(element).parent('div').children('small.path-success').fadeIn();
		}
	});
});

/*
|--------------------------------------------------------------------------
| Input change event to look for Mautic URL.
|--------------------------------------------------------------------------
*/
$('body').on('change', 'input#mautic-url', function() {
	var element = $(this);
	$('small.url-fail, small.url-success').hide();
	$.get( window.location.href, { q: 'url', url: encodeURIComponent($('input#mautic-url').val()) }, function(data) {
		if( data == 1 ) {
			validDomain = true;
			$(element).parent('div').children('small.url-success').fadeIn();
		} else {
			validDomain = false;
			$(element).parent('div').children('small.url-fail').fadeIn();
		}
	});
});

$(document).ready(function(){
	/*
	|--------------------------------------------------------------------------
	| Check to see if parent folder is root of compatible Mautic version
	|--------------------------------------------------------------------------
	*/
	$('input#sidebar-background').val(sidebar_background);
	$('input#mautic-primary').val(mautic_primary);
	$('input#mautic-hover').val(mautic_hover);
	$('#waiting-for-input span.dots').addClass('blink');
	$('input#mautic-url').val(window.location.href);

	$.get( window.location.href, { q: 'version', path: $('input#mautic-path').val() }, function(data) {
		if (data == 0) {
			$('input#mautic-path').parent('div').children('small.path-fail').fadeIn();
			validPath = false;
		} else {
			$('span.version').text(data);
			validPath = true;
			$('input#mautic-path').parent('div').children('small.path-success').fadeIn();
		}
	});
	$.get( window.location.href, { q: 'url', url: encodeURIComponent($('input#mautic-url').val()) }, function(data) {
		if (data == 1) {
			validDomain = true;
			$('input#mautic-url').parent('div').children('small.url-success').fadeIn();
		} else {
			$('input#mautic-url').parent('div').children('small.url-fail').fadeIn();
			validDomain = false;
		}
	});

	/*
	|--------------------------------------------------------------------------
	| Affix console/login preview for large screens
	|--------------------------------------------------------------------------
	*/
	if (window.innerWidth >= 995) {
		$('#right').affix({
		  offset: {
		    top: 40,
		  }
		});
	}
	$('#waiting-for-input').fadeIn();
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
	    	} else if (  $(input).attr('id') == 'favicon'  ) {
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
$("input#sidebar-logo-file, input#login-logo-file, input#favicon").change(function(){
	readImg(this);
	if ( $(this).attr('id') == 'sidebar-logo-file' ) {
		$('#sidebar-margin-top').val(0);
	} else if ( $(this).attr('id') == 'login-logo-file' ) {
		$('#login-margin-top, #login-margin-bottom').val(0);
	}
});

/*
|--------------------------------------------------------------------------
| Event listener for sidebar logo width preview
|--------------------------------------------------------------------------
*/
$('body').on('keyup change', 'input#logo-sidebar-width', function(){
	$('img#mautic-logo').css('width', $(this).val()+'px');
});

/*
|--------------------------------------------------------------------------
| Event listener for login logo width preview
|--------------------------------------------------------------------------
*/
$('body').on('keyup change', 'input#logo-login-width', function(){
	$('#login_preview img#login-logo, div.mautic-logo').css('width', $(this).val()+'px');
});

/*
|--------------------------------------------------------------------------
| Event listener for logo margin previews
|--------------------------------------------------------------------------
*/
$('body').on('keyup change', 'input.margintop, input.marginright, input.marginbottom, input.marginleft', function(){
	if ($(this).attr('data-logo')=='sidebar') {
		$('img#mautic-logo').css($(this).attr('data-margin'), $(this).val()+'px');
	} else if ($(this).attr('data-logo')=='login') {
		$('img#login-logo, div.mautic-logo').css($(this).attr('data-margin'), $(this).val()+'px');
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
|  Run whitelabel functions
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
	$.get( window.location.href, { q: 'version', path: path }, function(versionData){
		// version data success
		if (versionData != 0) {
			$("#console").animate({ scrollTop: $('#console').prop("scrollHeight")}, 500);
			$('#looking-for-installation-success').fadeIn();
			// css stuff
			$('#updating-css').fadeIn();
			$('#updating-css span.dots').addClass('blink');
			console.log(versionData);
			$.post(window.location.href+'?q=css', {
				path: path,
				version: versionData,
				sidebar_background: sidebar_background,
				mautic_primary: mautic_primary,
				mautic_hover: mautic_hover
			}, function(cssData) {
				if(cssData) {
					// css data success
					if(cssData=='CSS files updated with new colors.') {
						console.log(cssData);
						$("#console").animate({ scrollTop: $('#console').prop("scrollHeight")}, 500);
						$('#updating-css span.dots').removeClass('blink');
						$('#updating-css-success').fadeIn();
						// update company name
						$('#updating-companyname').fadeIn();
						$('#updating-companyname span.dots').addClass('blink');
						$.post(window.location.href+'?q=companyname', {
							path: path,
							version:versionData,
							company_name: $('input#company-name').val()
						}, function(companyNameData) {
							// Success
							if (companyNameData == 'Updated company name.') {
								$("#console").animate({ scrollTop: $('#console').prop("scrollHeight")}, 500);
								$('#updating-companyname span.dots').removeClass('blink');
								$('#updating-companyname-success').fadeIn();
								// Update logos
								$('#updating-images').fadeIn();
								$('#updating-images span.dots').addClass('blink');
								data = new FormData();
								data.append('mautic_path', path);
								data.append('mautic_url', $('#mautic-url').val());
								data.append('version', versionData);
								data.append('sidebar_logo', $('#sidebar-logo-file')[0].files[0]);
								data.append('sidebar_logo_width', $('#logo-sidebar-width').val());
								data.append('sidebar_margin_top', $('#sidebar-margin-top').val());
								data.append('sidebar_margin_right', $('#sidebar-margin-right').val());
								data.append('sidebar_margin_left', $('#sidebar-margin-left').val());
							    data.append('login_logo', $('#login-logo-file')[0].files[0]);
							    data.append('login_logo_width', $('#logo-login-width').val());
							    data.append('login_margin_top', $('#login-margin-top').val());
							    data.append('login_margin_bottom', $('#login-margin-bottom').val());
							    data.append('favicon', $('input#favicon')[0].files[0]);
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
							        	$('#updating-images span.dots').removeClass('blink');
										$('#updating-images-success').fadeIn();
										$("#console").animate({ scrollTop: $('#console').prop("scrollHeight")}, 500);
										console.log(imageData);
										// Regenerate Muatic Assets
										$('#regenerating').fadeIn();
										$('#regenerating span.dots').addClass('blink');
										$.get(window.location.href, { q: "assets", assets: "regenerate", path: path } )
										  .done(function(assetsData) {
										  	$('#regenerating span.dots').removeClass('blink');
										  	$('#regenerating-success').fadeIn();
										  	$("#console").animate({ scrollTop: $('#console').prop("scrollHeight")}, 500);
										    console.log(assetsData);
										    // Clearing Cache
										    $('#clearing span.dots').addClass('blink');
										    $('#clearing').fadeIn();
											$.get(window.location.href, { q: "assets", assets: "clear", path: path } )
											  .done(function(assetsData) {
											  	$('#clearing span.dots').removeClass('blink');
											  	$('#clearing-success').fadeIn();
											  	$("#console").animate({ scrollTop: $('#console').prop("scrollHeight")}, 500);
											    console.log(assetsData);
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
								$('#updating-companyname-error').append(': '+companyNameData).fadeIn();
								$('.spinner').fadeOut();
							}
						}); // post company name data

					// css data error
					} else if ( cssData == 'Unable to find app.css in your Mautic installation.' ||
								cssData == 'Unable to find libraries.css in your Mautic installation.' ) {
						$('#updating-css-error').append(': '+cssData).fadeIn();
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

// Event handler for "start whitelabeling" button
// Do some basic validation first
$('button').click(function(e){
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
	if (!$('#sidebar-logo-file')[0].files[0]) {
		alert('You have to choose a sidebar logo image.');
		return false;
	}
	if (!$('#login-logo-file')[0].files[0]) {
		alert('You have to choose a login logo image.');
		return false;
	}
	$('.spinner').fadeIn();
	whiteLabel();
});
