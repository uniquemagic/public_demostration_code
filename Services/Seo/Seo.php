<?php

namespace App\Services\Seo;

use App\Models\Course;
use App\Services\Seo\Meta;
use App\Interfaces\Route\ExternalRouteInterface;
use App\Interfaces\Seo\MetaInterface;
use App\Interfaces\Seo\Course\WithCourseMetaInterface;
use Routes;

class Seo implements ExternalRouteInterface, MetaInterface, WithCourseMetaInterface
{
    private $_routeName = null;
    private $_crudeMeta = null;
    private $_course    = null;
    private $_meta      = null;

    public function __construct($routeName, Course $course = null)
    {
        $this->_routeName = isset($routeName) ? $routeName : Routes::getCurrentRouteName();
        $this->_crudeMeta = $this->combineRoutesAndMeta();
        $this->_course    = $course;
        $this->_meta = new Meta(
            $this->getCrudeTitle(),
            $this->getCrudeDescription(),
            $this->getCrudeKeywords(),
            $this->getCrudeCanonical(),
            $this->getCrudeCourse(),
        );
    }

    public function combineRoutesAndMeta(): array
    {
        return array_combine(ExternalRouteInterface::EXTERNAL_ROUTES, MetaInterface::ROUTES_META);
    }

    public function getCrudeTitle(): ?string
    {
        return 
        (
            $this->_course
                ? $this->getCrudeCourseTitle()
                : $this->_crudeMeta[$this->_routeName][self::TITLE]
        ) . self::TITLE_SUFFIX;
    }

    public function getCrudeDescription(): ?string
    {
        return 
        (
            $this->_course
                ? $this->getCrudeCourseDescription()
                : $this->_crudeMeta[$this->_routeName][self::DESCRIPTION]
        );
    }

    public function getCrudeKeywords(): ?string
    {
        return 
        (
            $this->_course
                ? $this->getCrudeCourseKeywords()
                : $this->_crudeMeta[$this->_routeName][self::KEYWORDS]
        );
    }

    public function getCrudeCanonical(): string
    {
        return self::BASE_CANONICAL . Routes::getCurrentPathName();
    }

    public function getCrudeCourse(): ?Course
    {
        return $this->_course;
    }

    public function getCrudeCourseTitle(): string
    {
        return self::COURSE_TITLE_PREFIX . self::SPACE . ($this->_course->id == 1 ? '' : self::COURSE) . self::SPACE . $this->_course->name;
    }

    public function getCrudeCourseDescription(): string
    {
        return $this->_course->description;
    }

    public function getCrudeCourseKeywords(): string
    {
        return $this->_course->name . self::COMMA . self::SPACE . $this->_course->getTagsKeywords();
    }

    public function getMeta(): Meta
    {
        return $this->_meta;
    }
}