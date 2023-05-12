<?php

namespace App\Interfaces\Page;

interface XmlPageInterface
{
    public const SITEMAP_VIEW = 'pages.xml.sitemap';
    public const SHOP_VIEW    = 'pages.xml.shop';
    
    public function getSitemapPage();
    public function getShopPage();
}