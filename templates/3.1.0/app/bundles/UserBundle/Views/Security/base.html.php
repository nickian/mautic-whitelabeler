<?php

/*
 * @copyright   2014 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <title><?php echo $view['slots']->get('pageTitle', '{{company_name}}'); ?></title>
    <meta name="robots" content="noindex, nofollow" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="icon" type="image/x-icon" href="<?php echo $view['assets']->getUrl('media/images/favicon.ico'); ?>" />
    <link rel="apple-touch-icon" href="<?php echo $view['assets']->getUrl('media/images/apple-touch-icon.png'); ?>" />
    <?php $view['assets']->outputSystemStylesheets(); ?>
    <?php echo $view->render('MauticCoreBundle:Default:script.html.php'); ?>
    <?php $view['assets']->outputHeadDeclarations(); ?>
</head>
<body>
<section id="main" role="main">
    <div class="container" style="margin-top:100px;">
        <div class="row">
            <div class="col-lg-4 col-lg-offset-4">
                <div class="panel" name="form-login">
                    <div class="panel-body">
                        <div class="mautic-logo img-circle mb-md text-center" style="width:{{login_logo_width}}px;">
                            <img src="<?php echo $view['assets']->getUrl('{{login_logo}}') ?>" style="width:{{login_logo_width}}px; margin:{{login_logo_margin_top}}px 0 {{login_logo_margin_bottom}}px 0;" />
                        </div>
                        <div id="main-panel-flash-msgs">
                            <?php echo $view->render('MauticCoreBundle:Notification:flashes.html.php'); ?>
                        </div>
                        <?php $view['slots']->output('_content'); ?>
                    </div>
                </div>
            </div>
        </div>
         <div class="row">
            <div class="col-lg-4 col-lg-offset-4 text-center text-muted">
                &copy; <?php echo date('Y');?> {{company_name}}{{footer_prefix}}
                <p style="margin-top:1em;">{{footer}}</p>
            </div>
        </div>
    </div>
</section>
<?php echo $view['security']->getAuthenticationContent(); ?>
</body>
</html>
