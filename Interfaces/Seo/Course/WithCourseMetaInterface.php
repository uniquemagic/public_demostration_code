<?php

namespace App\Interfaces\Seo\Course;

use App\Models\Course;

interface WithCourseMetaInterface
{
    public const COURSE_TITLE_PREFIX = 'Записаться на';
    public const COURSE              = 'курс';
    
    public function getCrudeCourse(): ?Course;
    public function getCrudeCourseTitle(): string;
    public function getCrudeCourseDescription(): string;
    public function getCrudeCourseKeywords(): string;
}