<?php

namespace App\Interfaces\Page;

interface PersonalPageInterface
{
    public const PERSONAL_COURSES_VIEW     = 'personal.courses.index';
    public const PERSONAL_SCHEDULE_VIEW    = 'personal.schedule.index';
    public const PERSONAL_LITERATURES_VIEW = 'personal.literatures.index';
    public const PERSONAL_SETTINGS_VIEW    = 'personal.settings.index';
    public const PERSONAL_PAYMENTS_VIEW    = 'personal.payments.index';

    public function getCoursesPage();
    public function getSchedulePage();
    public function getLiteraturesPage();
    public function getSettingsPage();
    public function getPaymentsPage();
}