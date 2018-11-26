<!doctype html>
<html amp lang="{$lang}" dir="ltr">
<head id="meta" {block name="ogp"}{include file="section/brick/ogp-protocol.tpl"}{/block}>
    <meta charset="utf-8">
    <title itemprop="headline">{block name="title"}{/block}</title>
    <meta name="description" content="{capture name="description"}{block name="description"}{/block}{/capture}{$smarty.capture.description}">
    <meta itemprop="description" content="{$smarty.capture.description}">
    <meta name="robots" content="{google_tools tools='robots'}">
    {strip}{include file="section/loop/lang.tpl" amp=true iso={$lang}}{/strip}
    {*{if {module type="news"} eq true}<link rel="alternate" type="application/rss+xml" href="{$url}/news_{$lang}_rss.xml" title="RSS">{/if}*}
    <meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1">
    {include file="section/brick/socials.tpl" title=$smarty.capture.title description=$smarty.capture.description}
    {if $domainData != null && $domainData.tracking_domain != ''}{$domainData.tracking_domain}{else}{if $googleTools_webmaster != ''}<meta name="google-site-verification" content="{$googleTools_webmaster}">{/if}{/if}
    <link rel="icon" type="image/png" href="{$url}/skin/{$theme}/img/favicon.png" />
    {include file="section/brick/google-font.tpl" fonts=['Roboto'=>'300,400,600,400italic','Raleway'=>'300,500']}
    {if $browser !== 'IE'}<link rel="preload" href="https://fonts.googleapis.com/icon?family=Material+Icons" as="style">{/if}
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    {if $browser !== 'IE'}<link rel="preload" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" crossorigin="anonymous" as="style">{/if}
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
    <style amp-boilerplate>{literal}body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-o-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}</style><noscript><style amp-boilerplate>body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}{/literal}</style></noscript>
    <style amp-custom>
        {block name="stylesheet"}{/block}
    </style>
    <script async src="https://cdn.ampproject.org/v0.js"></script>
    <script async custom-element="amp-sidebar" src="https://cdn.ampproject.org/v0/amp-sidebar-0.1.js"></script>
    <script async custom-element="amp-accordion" src="https://cdn.ampproject.org/v0/amp-accordion-0.1.js"></script>
    <script async custom-element="amp-user-notification" src="https://cdn.ampproject.org/v0/amp-user-notification-0.1.js"></script>
    <script async custom-element="amp-youtube" src="https://cdn.ampproject.org/v0/amp-youtube-0.1.js"></script>
    {block name="amp-script"}{/block}
    {google_tools tools='amp-analytics'}
</head>
<body id="{capture name="bodyId"}{block name="body:id"}layout{/block}{/capture}{$smarty.capture.bodyId}" class="{$bodyClass}{if $touch} touchscreen{/if}" itemscope itemtype="http://schema.org/{block name="webType"}WebPage{/block}" itemref="meta">
{if isset($analytics)}{$analytics}{/if}
{include file="amp/section/brick/cookie-consent.tpl"}
{include file="amp/section/header.tpl"}
{block name="breadcrumb"}{if isset($smarty.get.controller) && $smarty.get.controller != "home"}{include file="section/brick/breadcrumb.tpl" icon="home" amp=true}{/if}{/block}
{block name="main:before"}{/block}
{block name="main"}
    <main id="content">
        {block name="article:before"}{/block}
        {block name='article'}
            <article class="container" itemprop="mainContentOfPage" itemscope itemtype="http://schema.org/WebPageElement">
                {block name='article:content'}{/block}
            </article>
        {/block}
        {block name="article:after"}{/block}
    </main>
{/block}
{block name="main:after"}{/block}
{include file="amp/section/footer.tpl" adjust="clip" blocks=['sitemap','about','contact']}
</body>
</html>