<?php

namespace App\Interfaces\Page;

interface ExternalPageInterface
{
    public const WELCOME_INDEX_VIEW = 'pages.welcome.index';
    public const COURSE_INDEX_VIEW  = 'pages.course.index';
    
    public function getWelcomePage();
    public function getCoursePage(string $slug = 'oznakomitelnoe-zanyatie');
}