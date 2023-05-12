<?php

namespace App\Services\Seo;

use App\Services\Seo\Og;
use App\Models\Course;

class Meta
{   
    private $_title;
    private $_description;
    private $_keywords;
    private $_canonical;
    private $_course;

    public function __construct($title, $description, $keywords, $canonical, ?Course $course)
    {
        $this->_title       = $title;
        $this->_description = $description;
        $this->_keywords    = $keywords;
        $this->_canonical   = $canonical;
        $this->_course      = $course;
    }

    public function getTitle(): string
    {
        return '<title>' . $this->_title . '</title>';
    }
    
    public function getDescription(): string
    {
        return '<meta name="description" content="' . $this->_description . '">';
    }

    public function getKeywords(): string
    {
        return '<meta name="keywords" content="' . $this->_keywords . '">';
    }

    public function getCanonical(): string
    {
        return '<link rel="canonical" href="' . $this->_canonical . '">';
    }

    public function getOg(): Og
    {
        return new Og(
            $this->_title, 
            $this->_description, 
            $this->_canonical,
            $this->_course
        );
    }
}