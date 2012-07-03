<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    {if !empty($_TITLE)}
        <title>{$_TITLE}</title>
    {/if}

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    {if !empty($_META_DESCRIPTION)}
        <meta name="description" content="{$_META_DESCRIPTION}" />
    {/if}
    {if !empty($_META_KEYWORDS)}
        <meta name="keywords" content="{$_META_KEYWORDS}" />
    {/if}

    {* <script type="text/javascript" src="/js/jquery-1.4.2.min.js"></script> prefer google version *}
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    {* This will add an extra JS call to your webserver for a template specific JS file. If you
    do not need this functionality, you can comment out this line *}
    <script type="text/javascript" src="/load/js/controller{$_PARAM_VALUE_INDICATOR}{$_CONTROLLER}{$_PARAM_SEPARATOR}view{$_PARAM_VALUE_INDICATOR}{$_VIEW}"></script>
        
            
    <script type="text/javascript">
        var base_url = "{$_BASE_URL}";
    </script>
            
            
	<link href="/css/default.css" rel="stylesheet" type="text/css" />
    {* This will add an extra CSS call to your webserver for a template specific CSS file. If you
    do not need this functionality, you can comment out this line *}    
    <link href="/load/css/controller{$_PARAM_VALUE_INDICATOR}{$_CONTROLLER}{$_PARAM_SEPARATOR}view{$_PARAM_VALUE_INDICATOR}{$_VIEW}" rel="stylesheet" type="text/css" media="all" />
	
</head>


<body>
	<div class="main">

