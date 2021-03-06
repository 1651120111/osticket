<?php
header("Content-Type: text/html; charset=UTF-8");
header("Content-Security-Policy: frame-ancestors ".$cfg->getAllowIframes().";");

$title = ($ost && ($title=$ost->getPageTitle()))
    ? $title : ('osTicket :: '.__('Staff Control Panel'));

if (!isset($_SERVER['HTTP_X_PJAX'])) { ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html<?php
if (($lang = Internationalization::getCurrentLanguage())
        && ($info = Internationalization::getLanguageInfo($lang))
        && (@$info['direction'] == 'rtl'))
    echo ' dir="rtl" class="rtl"';
if ($lang) {
    echo ' lang="' . Internationalization::rfc1766($lang) . '"';
}
?>>
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="pragma" content="no-cache" />
    <meta http-equiv="x-pjax-version" content="<?php echo GIT_VERSION; ?>">
    <title><?php echo Format::htmlchars($title); ?></title>
    <!--[if IE]>
    <style type="text/css">
        .tip_shadow { display:block !important; }
    </style>
    <![endif]-->
    <script type="text/javascript" src="<?php echo ROOT_PATH; ?>js/jquery-3.4.0.min.js?f1e9e88"></script>
    <link rel="stylesheet" href="<?php echo ROOT_PATH ?>css/thread.css?f1e9e88" media="all"/>
    <link rel="stylesheet" href="<?php echo ROOT_PATH ?>scp/css/scp.css?f1e9e88" media="all"/>
    <link rel="stylesheet" href="<?php echo ROOT_PATH; ?>css/redactor.css?f1e9e88" media="screen"/>
    <link rel="stylesheet" href="<?php echo ROOT_PATH ?>scp/css/typeahead.css?f1e9e88" media="screen"/>
    <link type="text/css" href="<?php echo ROOT_PATH; ?>css/ui-lightness/jquery-ui-1.10.3.custom.min.css?f1e9e88"
         rel="stylesheet" media="screen" />
    <link rel="stylesheet" href="<?php echo ROOT_PATH ?>css/jquery-ui-timepicker-addon.css?f1e9e88" media="all"/>
    <link type="text/css" rel="stylesheet" href="<?php echo ROOT_PATH; ?>css/font-awesome.min.css?f1e9e88"/>
    <!--[if IE 7]>
    <link rel="stylesheet" href="<?php echo ROOT_PATH; ?>css/font-awesome-ie7.min.css?f1e9e88"/>
    <![endif]-->
    <link type="text/css" rel="stylesheet" href="<?php echo ROOT_PATH ?>scp/css/dropdown.css?f1e9e88"/>
    <link type="text/css" rel="stylesheet" href="<?php echo ROOT_PATH; ?>css/loadingbar.css?f1e9e88"/>
    <link type="text/css" rel="stylesheet" href="<?php echo ROOT_PATH; ?>css/flags.css?f1e9e88"/>
    <link type="text/css" rel="stylesheet" href="<?php echo ROOT_PATH; ?>css/select2.min.css?f1e9e88"/>
    <link type="text/css" rel="stylesheet" href="<?php echo ROOT_PATH; ?>css/rtl.css?f1e9e88"/>
    <link type="text/css" rel="stylesheet" href="<?php echo ROOT_PATH ?>scp/css/translatable.css?f1e9e88"/>
    <!-- Favicons -->
    <link rel="icon" type="image/png" href="<?php echo ROOT_PATH ?>images/oscar-favicon-32x32.png" sizes="32x32" />
    <link rel="icon" type="image/png" href="<?php echo ROOT_PATH ?>images/oscar-favicon-16x16.png" sizes="16x16" />

    <?php
    if($ost && ($headers=$ost->getExtraHeaders())) {
        echo "\n\t".implode("\n\t", $headers)."\n";
    }
    ?>
    <script src="https://www.gstatic.com/firebasejs/7.8.2/firebase-app.js"></script>
    <!-- The core Firebase JS SDK is always required and must be listed first -->

    <script src="https://www.gstatic.com/firebasejs/7.8.2/firebase-messaging.js"></script>
    <!-- TODO: Add SDKs for Firebase products that you want to use
         https://firebase.google.com/docs/web/setup#available-libraries -->
    <script src="https://www.gstatic.com/firebasejs/7.8.2/firebase-analytics.js"></script>
<script>
  // Your web app's Firebase configuration
  var firebaseConfig = {
    apiKey: "AIzaSyCRpAcL8q7r4ubSAmFWjS4hKrvQnllUM0Q",
    authDomain: "thiennhan677-ff5d4.firebaseapp.com",
    databaseURL: "https://thiennhan677-ff5d4.firebaseio.com",
    projectId: "thiennhan677-ff5d4",
    storageBucket: "thiennhan677-ff5d4.appspot.com",
    messagingSenderId: "546763358671",
    appId: "1:546763358671:web:8c94188de47dc30a1fd2ba",
    measurementId: "G-DLGKMZ9R85"
  };
  // Initialize Firebase
  firebase.initializeApp(firebaseConfig);
  firebase.analytics();



    // Retrieve Firebase Messaging object.
    const messaging = firebase.messaging();

    // Add the public key generated from the console here.
    messaging.usePublicVapidKey("BEUNzZXvlJ00KLsgkoatvaUYSA6IoiXs2NWSYaSYcBYidzgVcvihAq1xHXSsuUgWlGmvLnaAjeVHw9D6OLRgvNs");
 console.log(messaging.getToken());
    Notification.requestPermission().then((permission)=>{
        if (permission === 'granted') {

            messaging.getToken().then((currentToken) => {
                if (currentToken) {

                    document.getElementById('token').innerHTML = currentToken;
                }
                else {
                    // Show permission request.
                    console.log('No Instance ID token available. Request permission to generate one.');
                    // Show permission UI
                  }
            }).catch((err) => {
                  console.log('An error occurred while retrieving token. ', err);
                });

        }else{
            console.log("khong the");
        }
    });

    messaging.onMessage(function(payLoad){
        console.log("On message: ",payLoad);
        var obj = JSON.parse(payLoad.data.notification);
        var notification = new Notification(obj.title,{
            icon: obj.icon,
            body: obj.body
        });
    });</script>
</head>

<body>
<div id="token"></div>
<div id="container">
    <?php
    if($ost->getError())
        echo sprintf('<div id="error_bar">%s</div>', $ost->getError());
    elseif($ost->getWarning())
        echo sprintf('<div id="warning_bar">%s</div>', $ost->getWarning());
    elseif($ost->getNotice())
        echo sprintf('<div id="notice_bar">%s</div>', $ost->getNotice());
    ?>
    <div id="header">
        <p id="info" class="pull-right no-pjax"><?php echo sprintf(__('Welcome, %s.'), '<strong>'.$thisstaff->getFirstName().'</strong>'); ?>
           <?php
            if($thisstaff->isAdmin() && !defined('ADMINPAGE')) { ?>
            | <a href="<?php echo ROOT_PATH ?>scp/admin.php" class="no-pjax"><?php echo __('Admin Panel'); ?></a>
            <?php }else{ ?>
            | <a href="<?php echo ROOT_PATH ?>scp/index.php" class="no-pjax"><?php echo __('Agent Panel'); ?></a>
            <?php } ?>
            | <a href="<?php echo ROOT_PATH ?>scp/profile.php"><?php echo __('Profile'); ?></a>
            | <a href="<?php echo ROOT_PATH ?>scp/logout.php?auth=<?php echo $ost->getLinkToken(); ?>" class="no-pjax"><?php echo __('Log Out'); ?></a>
        </p>
        <a href="<?php echo ROOT_PATH ?>scp/index.php" class="no-pjax" id="logo">
            <span class="valign-helper"></span>
            <img src="<?php echo ROOT_PATH ?>scp/logo.php?<?php echo strtotime($cfg->lastModified('staff_logo_id')); ?>" alt="osTicket &mdash; <?php echo __('Customer Support System'); ?>"/>
        </a>
    </div>
    <div id="pjax-container" class="<?php if ($_POST) echo 'no-pjax'; ?>">
<?php } else {
    header('X-PJAX-Version: ' . GIT_VERSION);
    if ($pjax = $ost->getExtraPjax()) { ?>
    <script type="text/javascript">
    <?php foreach (array_filter($pjax) as $s) echo $s.";"; ?>
    </script>
    <?php }
    foreach ($ost->getExtraHeaders() as $h) {
        if (strpos($h, '<script ') !== false)
            echo $h;
    } ?>
    <title><?php echo ($ost && ($title=$ost->getPageTitle()))?$title:'osTicket :: '.__('Staff Control Panel'); ?></title><?php
} # endif X_PJAX ?>
    <ul id="nav">
<?php include STAFFINC_DIR . "templates/navigation.tmpl.php"; ?>
    </ul>
    <?php include STAFFINC_DIR . "templates/sub-navigation.tmpl.php"; ?>

        <div id="content">
        <?php if($errors['err']) { ?>
            <div id="msg_error"><?php echo $errors['err']; ?></div>
        <?php }elseif($msg) { ?>
            <div id="msg_notice"><?php echo $msg; ?></div>
        <?php }elseif($warn) { ?>
            <div id="msg_warning"><?php echo $warn; ?></div>
        <?php }
        foreach (Messages::getMessages() as $M) { ?>
            <div class="<?php echo strtolower($M->getLevel()); ?>-banner"><?php
                echo (string) $M; ?></div>
<?php   } ?>
