# ElementCarousel

Elemental Carousel block for SilverStripe.

## Install

`composer require Antlion/element-Carousel`

## Requirements

PHP 8.1+

SilverStripe ^5 || ^6

dnadesign/silverstripe-elemental ^5 || ^6

## What it does

Adds an Carousel Elemental block you can place on pages.

Uses Swiper.js Slider library `https://swiperjs.com/`

## Usage

Ensure your project uses Elemental on the page type(s) you want.

Template/CSS/JS are intentionally minimal—style or enhance as needed in your theme.

## Templating (optional)

Override the default templates in your project theme by copying and editing:

templates/Antlion/ElementCarousel/Elements/ElementCarousel.ss

templates/Antlion/ElementCarousel/Includes/ElementCarouselSlide.ss

Flush after changing templates by appending ?flush=all to a site URL, e.g.:

`https://yoursite.test/?flush=all`
