<?php

namespace App\Services\Seo;

use App\Models\Course;
use App\Interfaces\Seo\OgInterface;

class Og implements OgInterface
{
    private $_title;
    private $_description;
    private $_url;
    private $_ogType;
    private $_price;

    public function __construct($title, $description, $url, ?Course $course) 
    {
        $this->_title       = $title;
        $this->_description = $description;
        $this->_url         = $url;
        $this->_ogType      = $course ? self::OG_TYPE_PRODUCT : self::OG_TYPE_WEBSITE;
        $this->_price       = $course ? $course->price : 0;
    }

    public function getType(): string
    {
        return '<meta property="og:type" content="' . $this->_ogType . '" />';
    }

    public function getTitle(): string
    {
        return '<meta property="og:title" content="' . $this->_title . '"/>';
    }

    public function getDescription(): string
    {
        return '<meta property="og:description" content="' . $this->_description . '"/>';
    }

    public function getUrl(): string
    {
        return '<meta property="og:url" content="' . $this->_url . '" />';
    }

    public function getImage(): string
    {
        return '<meta property="og:image" content="' . asset(self::BADGE_IMAGE) . '"/>';
    }

    public function getImageWidth(): string
    {
        return '<meta property="og:image:width" content="' . self::OG_IMAGE_WIDTH . '"/>';
    }

    public function getImageHeight(): string
    {
        return '<meta property="og:image:height" content="' . self::OG_IMAGE_HEIGHT . '"/>';
    }

    public function getAmount(): string
    {
        return '<meta property="og:product:price:amount" content="' . $this->_price . '"/>';
    }

    public function getCurrency(): string
    {
        return '<meta property="og:product:price:currency" content="' . self::CURRENCY . '"/>';
    }
}