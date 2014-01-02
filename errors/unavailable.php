<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<?php $path = preg_replace('#/[^/]+\.php5?$#', '', isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : (isset($_SERVER['ORIG_SCRIPT_NAME']) ? $_SERVER['ORIG_SCRIPT_NAME'] : '')) ?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="title" content="thunderwolf project"/>
    <meta name="robots" content="index, follow"/>
    <meta name="description" content="thunderwolf project"/>
    <meta name="keywords" content="thunderwolf project"/>
    <meta name="language" content="en"/>
    <title>thunderwolf project</title>

    <link rel="shortcut icon" href="/favicon.ico"/>
    <link rel="stylesheet" type="text/css" media="screen" href="<?php echo $path ?>/tw/css/screen.css"/>
    <!--[if lt IE 7.]>
    <link rel="stylesheet" type="text/css" media="screen" href="<?php echo $path ?>/tw/css/ie.css"/>
    <![endif]-->

</head>
<body>
<div class="twContainer">
    <a title="thunderwolf website" href="http://www.thunderwolf.net/"><img alt="Thunderwolf" class="twLogo"
                                                                           src="<?php echo $path ?>/tw/images/twLogo.png"/></a>

    <div class="twMessageContainer twAlert">
        <img alt="page not found" class="twMessageIcon" src="<?php echo $path ?>/tw/images/icons/tools48.png"
             height="48" width="48"/>

        <div class="twMessageWrap">
            <h1>Website Temporarily Unavailable</h1>
            <h5>Please try again in a few seconds...</h5>
        </div>
    </div>

    <dl class="twMessageInfo">
        <dt>What's next</dt>
        <dd>
            <ul class="twIconList">
                <li class="twReloadMessage"><a href="javascript:window.location.reload()">Try again: Reload Page</a>
                </li>
            </ul>
        </dd>
    </dl>
</div>
</body>
</html>
