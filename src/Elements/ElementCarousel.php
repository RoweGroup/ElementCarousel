<?php

namespace Antlion\ElementCarousel\Elements;

use Antlion\ElementCarousel\Controllers\ElementCarouselController;
use Antlion\ElementCarousel\Models\CarouselSlide;
use DNADesign\Elemental\Models\BaseElement;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\NumericField;
use SilverStripe\Forms\ToggleCompositeField;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RelationEditor;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;
use TractorCow\Colorpicker\Forms\ColorField;
use SilverStripe\Forms\FieldGroup;

class ElementCarousel extends BaseElement
{
  private static $table_name = 'ElementCarousel';
  private static $description = 'Carousel';
  private static $singular_name = 'carousel';
  private static $plural_name = 'carousels';
  private static $icon = 'font-icon-block-carousel';

  private static $controller_class = ElementCarouselController::class;

  private static $inline_editable = false;

  private static $db = [
    // 'Height'          => 'Enum("auto,short,medium,tall,full","tall")',
    'SlideAppearance' => 'Enum("Slide,Cover","Slide")',

    // Card settings
    'CardHorizontalAlign' => 'Enum("left,center,right","left")',
    'CoverFormat'         => 'Enum("9/8,4/3,16/9,3/4","3/4")',
    'CoverPadding'        => 'Enum("none,20px,40px,60px","60px")',
    'CoverVerticalAlign'  => 'Enum("top,middle,bottom","bottom")',

    // Appearance
    'Theme'           => 'Enum("inherit,light,dark","inherit")',
    'BackgroundColor' => 'Varchar(20)',

    // Cover slide gradient overlay (column defaults keep the original black 90% gradient)
    'CoverGradient'   => 'Boolean(1)',
    'OverlayColor'    => 'Varchar(20)',
    'OverlayOpacity'  => 'Int(90)',

    'Loop'            => 'Boolean',
    'Speed'           => 'Int',
    'SpaceBetween'    => 'Int',
    'SlidesPerView'   => 'Int',   // desktop
    'SlidesPerViewMd' => 'Int',   // tablet
    'SlidesPerViewSm' => 'Int',   // mobile
    'CenteredSlides'  => 'Boolean',
    'FreeMode'        => 'Boolean',
    'Pagination'      => 'Boolean',
    'Navigation'      => 'Boolean',
    'Scrollbar'       => 'Boolean',
    'MouseWheel'      => 'Boolean',
    'Autoplay'        => 'Boolean',
    'AutoplayDelay'   => 'Int',
    'PauseButton'     => 'Boolean(1)',
    'Lazy'            => 'Boolean',
  ];

  public function populateDefaults()
    {
        $this->owner->Speed         = 600;
        $this->owner->SpaceBetween  = 20;
        $this->SlidesPerView        = 5;
        $this->SlidesPerViewMd      = 3;
        $this->SlidesPerViewSm      = 1;
        $this->owner->Pagination    = true;
        $this->owner->Navigation    = true;
        $this->owner->Loop          = true;
        $this->owner->Autoplay      = true;
        $this->owner->AutoplayDelay = 5000;
        $this->owner->PauseButton   = true;
        $this->Theme                = 'inherit';
        $this->CoverGradient        = true;
        $this->OverlayOpacity       = 90;
        parent::populateDefaults();
    }

  private static $has_many = [
    'Slides' => CarouselSlide::class,
  ];

  private static $owns = [
        'Slides',
    ];

  public function getCMSFields()
  {
    $fields = parent::getCMSFields();
        $gridConfig = GridFieldConfig_RelationEditor::create();
        $gridConfig->addComponent(new GridFieldOrderableRows('SortOrder'));
        $slidesGrid = GridField::create(
            'Content',
            'Content',
            $this->owner->Slides(),
            $gridConfig
        );
        $fields->addFieldToTab('Root.Main', $slidesGrid);
        $fields->removeByName ([
            'Loop',
            'SortOrder',  
            'ParentID', 
            'Theme', 
            'Align', 
            'OverlayOpacity', 
            'StartDate', 
            'EndDate',
            'Speed',
            'SpaceBetween',
            'SlidesPerView',
            'SlidesPerViewMd',
            'SlidesPerViewSm',
            'CenteredSlides',
            'FreeMode',
            'Pagination',
            'Navigation',
            'Scrollbar',
            'MouseWheel',
            'Autoplay',
            'AutoplayDelay',
            'PauseButton',
            'Lazy',
            'Slides',
            'SlideAppearance',
            'CardHorizontalAlign',
            'CoverFormat',
            'CoverPadding',
            'CoverVerticalAlign',
            'BackgroundColor',
            'CoverGradient',
            'OverlayColor',
        ]);
    $fields->addFieldsToTab('Root.Main', [
      // Padding and vertical alignment show only when Slide appearance is Cover
      ToggleCompositeField::create(
                'CardSettings',
                'Card Settings',
                [
                    DropdownField::create(
                        'SlideAppearance',
                        'Slide appearance',
                        [
                            'Slide' => 'Slide',
                            'Cover' => 'Cover slide',
                        ]
                    ),
                    DropdownField::create('CoverFormat', 'Format', [
                        '9/8'  => '9:8 (block)',
                        '4/3'  => '4:3 (landscape)',
                        '16/9' => '16:9 (widescreen)',
                        '3/4'  => '3:4 (portrait)',
                    ])->setDescription('Image aspect ratio for both Slide and Cover slides.'),
                    DropdownField::create('CardHorizontalAlign', 'Horizontal alignment', [
                        'left'   => 'Left',
                        'center' => 'Center',
                        'right'  => 'Right',
                    ]),
                    DropdownField::create('CoverVerticalAlign', 'Vertical alignment', [
                        'top'    => 'Top',
                        'middle' => 'Middle',
                        'bottom' => 'Bottom',
                    ])->displayIf('SlideAppearance')->isEqualTo('Cover')->end(),
                    DropdownField::create('CoverPadding', 'Content padding', [
                        'none' => 'None',
                        '20px' => 'Small (20px)',
                        '40px' => 'Medium (40px)',
                        '60px' => 'Large (60px)',
                    ])->displayIf('SlideAppearance')->isEqualTo('Cover')->end(),
                    ColorField::create('BackgroundColor', 'Background Color')
                        ->setDescription('Card background. Shows on Cover slides that have no image.'),
                    DropdownField::create('Theme', 'Text Theme', [
                        'inherit' => 'Default',
                        'light'   => 'Light (dark text)',
                        'dark'    => 'Dark (white text)',
                    ])->setDescription('Sets the card text color'),
                    CheckboxField::create('CoverGradient', 'Gradient overlay')
                        ->displayIf('SlideAppearance')->isEqualTo('Cover')->end(),
                    ColorField::create('OverlayColor', 'Gradient Color')
                        ->setDescription('Defaults to black')
                        ->displayIf('SlideAppearance')->isEqualTo('Cover')->end(),
                    NumericField::create('OverlayOpacity', 'Gradient Opacity (0–100)')
                        ->setDescription('Opacity at the bottom of the card; the top fades to a light tint.')
                        ->displayIf('SlideAppearance')->isEqualTo('Cover')->end(),
                ]
            )->setStartClosed(true),
      ToggleCompositeField::create(
                'SliderSettings',
                'Slider Settings',
                [
                    FieldGroup::create(
                        'Slides Per View', 
                        NumericField::create('SlidesPerView',   'Slides per view (desktop)'),
                        NumericField::create('SlidesPerViewMd', 'Slides per view (tablet ≥ 640px)'),
                        NumericField::create('SlidesPerViewSm', 'Slides per view (mobile < 640px)'),
                    ),
                    NumericField::create('SpaceBetween', 'Space between slides (px)'),
                    CheckboxField::create('Loop', 'Loop'),
                    CheckboxField::create('Pagination', 'Pagination'),
                    CheckboxField::create('Navigation', 'Navigation (prev/next arrows)'),
                    CheckboxField::create('Scrollbar', 'Scrollbar'),
                    CheckboxField::create('MouseWheel', 'MouseWheel'),
                    CheckboxField::create('Lazy', 'Lazy images'),  
                    CheckboxField::create('CenteredSlides', 'Centered slides'),
                    CheckboxField::create('FreeMode', 'Free mode (drag slides)'),
                    
                    CheckboxField::create('Autoplay', 'Autoplay'),
                    NumericField::create('AutoplayDelay', 'Autoplay delay (ms)')
                        ->setDescription('Used only when Autoplay is enabled.'),
                    CheckboxField::create('PauseButton', 'Show pause button')
                        ->setDescription('Lets visitors stop the autoplay (accessibility, WCAG 2.2.2).'),
                    NumericField::create('Speed', 'Transition speed (ms)'),
                ]
            )->setStartClosed(false)
    ]);

    return $fields;
  }

  public function getType()
  {
    return _t(__class__ . '.BlockType', 'Carousel');
  }

    public function CardHorizontalAlignClass(): string
    {
        return match ($this->CardHorizontalAlign) {
            'center' => 'align-center text-center',
            'right'  => 'align-right text-right',
            'left'   => 'align-left text-left',
            default  => '',
        };
    }

    public function CoverVerticalAlignClass(): string
    {
        return match ($this->CoverVerticalAlign) {
            'top'    => 'align-top',
            'middle' => 'align-middle',
            default  => 'align-bottom',
        };
    }

    public function CoverPaddingClass(): string
    {
        return match ($this->CoverPadding) {
            'none'  => '',
            '20px'  => 'p-20',
            '40px'  => 'p-40',
            default => 'p-60',
        };
    }

    public function ThemeClass(): string
    {
        $classes = match ($this->Theme) {
            'light' => 'carousel-light',
            'dark'  => 'carousel-dark',
            default => '',
        };

        if ($this->CardBackgroundRGB()) {
            $classes .= ' carousel-card-bg';
        }

        if (!$this->CoverGradient) {
            $classes .= ' carousel-no-gradient';
        }

        return trim($classes);
    }

    // Inline style string for the outer carousel wrapper; cards read these custom properties
    public function CarouselStyle(): string
    {
        $parts = [];

        if ($rgb = $this->CardBackgroundRGB()) {
            $parts[] = '--card-bg-rgb: ' . $rgb;
        }

        if ($this->CoverGradient) {
            $overlay = $this->hexToRgb((string) $this->OverlayColor);
            if ($overlay) {
                $parts[] = '--overlay-rgb: ' . implode(', ', $overlay);
            }
            $parts[] = '--overlay-opacity: ' . $this->OverlayOpacityCss();
        }

        return $parts ? implode('; ', $parts) . ';' : '';
    }

    // 0–100 int -> 0–1 float string
    public function OverlayOpacityCss(): string
    {
        $pct = max(0, min(100, (int) $this->OverlayOpacity));
        return (string) round($pct / 100, 2);
    }

    // "r, g, b" so the CSS can use it for both solid and translucent backgrounds
    public function CardBackgroundRGB(): ?string
    {
        $rgb = $this->hexToRgb((string) $this->BackgroundColor);
        return $rgb ? implode(', ', $rgb) : null;
    }

    private function hexToRgb(string $hex): ?array
    {
        $hex = ltrim(trim($hex), '#');

        if (strlen($hex) === 3) {
            $hex = "{$hex[0]}{$hex[0]}{$hex[1]}{$hex[1]}{$hex[2]}{$hex[2]}";
        }

        if (!preg_match('/^[0-9a-fA-F]{6}$/', $hex)) {
            return null;
        }

        return [
            hexdec(substr($hex, 0, 2)),
            hexdec(substr($hex, 2, 2)),
            hexdec(substr($hex, 4, 2)),
        ];
    }

    /**
     * Value for the CSS aspect-ratio property on slide images.
     */
    public function CoverAspectRatioCss(): string
    {
        return in_array($this->CoverFormat, ['9/8', '4/3', '16/9', '3/4'], true)
            ? $this->CoverFormat
            : '3/4';
    }

//   public function getSimpleClassName()
//   {
//     return 'element-carousel';
//   }
    /**
     * Build a Swiper options array from the DB config.
     */
    public function getCarouselOptions(): array
    {
        $o = [
            'effect'          => 'slide',
            'loop'            => (bool) $this->owner->Loop,
            'speed'           => (int)  ($this->owner->Speed ?: 600),
            'spaceBetween' => (int)($this->SpaceBetween ?: 0),
            'centeredSlides' => (bool)$this->owner->CenteredSlides,
            'breakpoints' => [
                0    => ['slidesPerView' => (int)($this->SlidesPerViewSm ?: 1)],
                640  => ['slidesPerView' => (int)($this->SlidesPerViewMd ?: 2)],
                1024 => ['slidesPerView' => (int)($this->SlidesPerView   ?: 3)],
            ],
        ];

        if ($this->owner->SlidesPerView) {
            $o['slidesPerView'] = (int) $this->owner->SlidesPerView;
        }
        
        if ($this->owner->FreeMode) {
            $o['freeMode'] = (bool) $this->owner->FreeMode;
        }

        if ($this->owner->Pagination) {
            $o['pagination'] = [
                'el'        => '.swiper-pagination',
                'clickable' => true,
            ];
        }
        if ($this->owner->Navigation) {
            $o['navigation'] = [
                'nextEl' => '.swiper-button-next',
                'prevEl' => '.swiper-button-prev',
            ];
        }
        if ($this->owner->Scrollbar) {
            $o['scrollbar'] = [
                'el'   => '.swiper-scrollbar',
                'hide' => false,
            ];
        }
        if ($this->owner->MouseWheel) {
            $o['mousewheel'] = (bool) $this->owner->MouseWheel;
        }
        if ($this->owner->Autoplay) {
            $o['autoplay'] = [
                'delay'               => (int)($this->owner->AutoplayDelay ?: 5000),
                'disableOnInteraction'=> false,
                'pauseOnMouseEnter'   => true,
            ];
        }
        if ($this->owner->Autoplay && $this->owner->PauseButton) {
            $o['pauseButton'] = true; // theme JS adds a pause/play button
        }

        // keyboard + screen reader support (WCAG 2.1.1, 4.1.2): theme JS adds arrow-key control
        // and holds autoplay while keyboard focus is inside; Swiper labels it as a carousel region
        $o['keyboardControl'] = true;
        $o['a11y'] = [
            'containerRole'                   => 'region',
            'containerRoleDescriptionMessage' => 'carousel',
            'containerMessage'                => ($this->ShowTitle && trim((string) $this->Title) !== '' ? trim(strip_tags((string) $this->Title)) : 'Carousel'),
            'itemRoleDescriptionMessage'      => 'slide',
        ];
        if ($this->owner->Lazy) {
            $o['lazy'] = [
                'loadPrevNext' => true,
            ];
        }
        return $o;
    }

    /**
     * JSON for template injection.
     */
    public function getCarouselOptionsJSON(): string
    {
        return json_encode($this->getCarouselOptions(), JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP);
    }

    /**
     * Cache key for the front-end render: changes whenever this element's own
     * settings change, or the slide list is added to, removed from, reordered,
     * or one of its members is edited.
     */
    public function getSlidesCacheKey(): string
    {
        $slides = $this->Slides()->sort('SortOrder');

        return md5(implode('|', [
            $this->ID,
            $this->LastEdited,
            $slides->count(),
            implode('-', $slides->column('ID')),
            $slides->max('LastEdited'),
        ]));
    }
}
