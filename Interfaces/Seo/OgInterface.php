<?php

namespace App\Interfaces\Seo;

interface OgInterface
{
    public const OG_TYPE_WEBSITE = 'website';
    public const OG_TYPE_PRODUCT = 'og:product';
    public const CURRENCY        = 'RUB';

    public const BADGE_IMAGE     = 'images/badge.jpg';
    public const OG_IMAGE_WIDTH  = 400;
    public const OG_IMAGE_HEIGHT = 400;

    public function getType(): string;

    public function getTitle(): string;

    public function getDescription(): string;

    public function getUrl(): string;

    public function getImage(): string;

    public function getImageWidth(): string;

    public function getImageHeight(): string;

    public function getAmount(): string;

    public function getCurrency(): string;
}