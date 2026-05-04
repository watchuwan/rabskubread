<?php

namespace App\Filament\Pages;

use App\Enums\NavigationGroup;
use App\Models\Setting;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\ViewField;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class ThemeCustomizer extends Page implements HasSchemas
{
    use InteractsWithSchemas;

    protected string $view = 'filament.pages.theme-customizer';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPaintBrush;

    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::Settings;

    protected static ?int $navigationSort = 10;

    public static function getNavigationLabel(): string
    {
        return __('resources.theme_customizer.nav_label');
    }

    public const PRESETS = [
        'amber_classic'   => ['label' => 'Amber Classic',   'emoji' => '🟡', 'primary' => '#F59E0B', 'hover' => '#D97706', 'accent' => '#FFE8A3', 'bg' => '#FFFEF9', 'text' => '#1C1917', 'navbar_bg' => '#FFFFFF', 'footer_bg' => '#1C1917', 'card_bg' => '#FFFFFF', 'border' => '#E7E5E4', 'btn_text' => '#FFFFFF', 'hero_from' => '#FEF3C7', 'hero_to' => '#FFFEF9', 'font_sans' => 'Inter',     'font_display' => 'Playfair Display',  'radius' => 'rounded',    'shadow' => 'shadow-sm', 'font_size' => 'base'],
        'rose_bakery'     => ['label' => 'Rose Bakery',     'emoji' => '🌸', 'primary' => '#F43F5E', 'hover' => '#E11D48', 'accent' => '#FFE4E6', 'bg' => '#FFF5F7', 'text' => '#1C1917', 'navbar_bg' => '#FFFFFF', 'footer_bg' => '#881337', 'card_bg' => '#FFFFFF', 'border' => '#FECDD3', 'btn_text' => '#FFFFFF', 'hero_from' => '#FFE4E6', 'hero_to' => '#FFF5F7', 'font_sans' => 'Poppins',    'font_display' => 'Cormorant Garamond', 'radius' => 'rounded-2xl', 'shadow' => 'shadow-md', 'font_size' => 'base'],
        'forest_artisan'  => ['label' => 'Forest Artisan',  'emoji' => '🌿', 'primary' => '#16A34A', 'hover' => '#15803D', 'accent' => '#DCFCE7', 'bg' => '#F0FDF4', 'text' => '#14532D', 'navbar_bg' => '#FFFFFF', 'footer_bg' => '#14532D', 'card_bg' => '#FFFFFF', 'border' => '#BBF7D0', 'btn_text' => '#FFFFFF', 'hero_from' => '#DCFCE7', 'hero_to' => '#F0FDF4', 'font_sans' => 'Nunito',     'font_display' => 'Lora',              'radius' => 'rounded-xl',  'shadow' => 'shadow',    'font_size' => 'base'],
        'midnight_luxury' => ['label' => 'Midnight Luxury', 'emoji' => '💜', 'primary' => '#7C3AED', 'hover' => '#6D28D9', 'accent' => '#EDE9FE', 'bg' => '#FAF5FF', 'text' => '#1C1917', 'navbar_bg' => '#FFFFFF', 'footer_bg' => '#2E1065', 'card_bg' => '#FFFFFF', 'border' => '#DDD6FE', 'btn_text' => '#FFFFFF', 'hero_from' => '#EDE9FE', 'hero_to' => '#FAF5FF', 'font_sans' => 'Lato',       'font_display' => 'Merriweather',      'radius' => 'rounded-lg',  'shadow' => 'shadow-lg', 'font_size' => 'base'],
        'ocean_fresh'     => ['label' => 'Ocean Fresh',     'emoji' => '🩵', 'primary' => '#0EA5E9', 'hover' => '#0284C7', 'accent' => '#E0F2FE', 'bg' => '#F0F9FF', 'text' => '#0C4A6E', 'navbar_bg' => '#FFFFFF', 'footer_bg' => '#0C4A6E', 'card_bg' => '#FFFFFF', 'border' => '#BAE6FD', 'btn_text' => '#FFFFFF', 'hero_from' => '#E0F2FE', 'hero_to' => '#F0F9FF', 'font_sans' => 'Roboto',     'font_display' => 'Montserrat',        'radius' => 'rounded-xl',  'shadow' => 'shadow-sm', 'font_size' => 'base'],
        'terracotta'      => ['label' => 'Terracotta',      'emoji' => '🧱', 'primary' => '#C2410C', 'hover' => '#9A3412', 'accent' => '#FFEDD5', 'bg' => '#FFF7ED', 'text' => '#431407', 'navbar_bg' => '#FFFFFF', 'footer_bg' => '#431407', 'card_bg' => '#FFFFFF', 'border' => '#FED7AA', 'btn_text' => '#FFFFFF', 'hero_from' => '#FFEDD5', 'hero_to' => '#FFF7ED', 'font_sans' => 'Open Sans',  'font_display' => 'Raleway',           'radius' => 'rounded',     'shadow' => 'shadow',    'font_size' => 'base'],
    ];

    public string $theme_primary_color  = '#F59E0B';
    public string $theme_primary_hover  = '#D97706';
    public string $theme_accent_color   = '#FFE8A3';
    public string $theme_bg_color       = '#FFFEF9';
    public string $theme_text_color     = '#1C1917';
    public string $theme_navbar_bg      = '#FFFFFF';
    public string $theme_footer_bg      = '#1C1917';
    public string $theme_card_bg        = '#FFFFFF';
    public string $theme_border_color   = '#E7E5E4';
    public string $theme_btn_text_color = '#FFFFFF';
    public string $theme_hero_from      = '#FEF3C7';
    public string $theme_hero_to        = '#FFFEF9';
    public string $theme_font_sans      = 'Inter';
    public string $theme_font_display   = 'Playfair Display';
    public string $theme_font_size      = 'base';
    public string $theme_border_radius  = 'rounded';
    public string $theme_card_shadow    = 'shadow-sm';

    public function mount(): void
    {
        $this->theme_primary_color  = Setting::get('theme_primary_color',  '#F59E0B');
        $this->theme_primary_hover  = Setting::get('theme_primary_hover',  '#D97706');
        $this->theme_accent_color   = Setting::get('theme_accent_color',   '#FFE8A3');
        $this->theme_bg_color       = Setting::get('theme_bg_color',       '#FFFEF9');
        $this->theme_text_color     = Setting::get('theme_text_color',     '#1C1917');
        $this->theme_navbar_bg      = Setting::get('theme_navbar_bg',      '#FFFFFF');
        $this->theme_footer_bg      = Setting::get('theme_footer_bg',      '#1C1917');
        $this->theme_card_bg        = Setting::get('theme_card_bg',        '#FFFFFF');
        $this->theme_border_color   = Setting::get('theme_border_color',   '#E7E5E4');
        $this->theme_btn_text_color = Setting::get('theme_btn_text_color', '#FFFFFF');
        $this->theme_hero_from      = Setting::get('theme_hero_from',      '#FEF3C7');
        $this->theme_hero_to        = Setting::get('theme_hero_to',        '#FFFEF9');
        $this->theme_font_sans      = Setting::get('theme_font_sans',      'Inter');
        $this->theme_font_display   = Setting::get('theme_font_display',   'Playfair Display');
        $this->theme_font_size      = Setting::get('theme_font_size',      'base');
        $this->theme_border_radius  = Setting::get('theme_border_radius',  'rounded');
        $this->theme_card_shadow    = Setting::get('theme_card_shadow',    'shadow-sm');

        $this->themeForm->fill([
            'theme_primary_color'  => $this->theme_primary_color,
            'theme_primary_hover'  => $this->theme_primary_hover,
            'theme_accent_color'   => $this->theme_accent_color,
            'theme_bg_color'       => $this->theme_bg_color,
            'theme_text_color'     => $this->theme_text_color,
            'theme_navbar_bg'      => $this->theme_navbar_bg,
            'theme_footer_bg'      => $this->theme_footer_bg,
            'theme_card_bg'        => $this->theme_card_bg,
            'theme_border_color'   => $this->theme_border_color,
            'theme_btn_text_color' => $this->theme_btn_text_color,
            'theme_hero_from'      => $this->theme_hero_from,
            'theme_hero_to'        => $this->theme_hero_to,
            'theme_font_sans'      => $this->theme_font_sans,
            'theme_font_display'   => $this->theme_font_display,
            'theme_font_size'      => $this->theme_font_size,
            'theme_border_radius'  => $this->theme_border_radius,
            'theme_card_shadow'    => $this->theme_card_shadow,
        ]);
    }

    public function themeForm(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Preset picker
                ViewField::make('presets')
                    ->view('filament.pages.theme-presets')
                    ->columnSpanFull(),

                // Colors — Primary
                Section::make(__('resources.theme_customizer.section_colors'))
                    ->schema([
                        ColorPicker::make('theme_primary_color')
                            ->label(__('resources.theme_customizer.primary_color'))
                            ->helperText(__('resources.theme_customizer.primary_color_help'))
                            ->live(),
                        ColorPicker::make('theme_primary_hover')
                            ->label(__('resources.theme_customizer.primary_hover'))
                            ->helperText(__('resources.theme_customizer.primary_hover_help'))
                            ->live(),
                        ColorPicker::make('theme_accent_color')
                            ->label(__('resources.theme_customizer.accent_color'))
                            ->helperText(__('resources.theme_customizer.accent_color_help'))
                            ->live(),
                        ColorPicker::make('theme_bg_color')
                            ->label(__('resources.theme_customizer.bg_color'))
                            ->helperText(__('resources.theme_customizer.bg_color_help'))
                            ->live(),
                        ColorPicker::make('theme_text_color')
                            ->label(__('resources.theme_customizer.text_color'))
                            ->helperText(__('resources.theme_customizer.text_color_help'))
                            ->live(),
                        ColorPicker::make('theme_btn_text_color')
                            ->label(__('resources.theme_customizer.btn_text_color'))
                            ->helperText(__('resources.theme_customizer.btn_text_color_help'))
                            ->live(),
                    ])
                    ->columns(3),

                // Colors — Layout
                Section::make(__('resources.theme_customizer.section_layout_colors'))
                    ->schema([
                        ColorPicker::make('theme_navbar_bg')
                            ->label(__('resources.theme_customizer.navbar_bg'))
                            ->helperText(__('resources.theme_customizer.navbar_bg_help'))
                            ->live(),
                        ColorPicker::make('theme_footer_bg')
                            ->label(__('resources.theme_customizer.footer_bg'))
                            ->helperText(__('resources.theme_customizer.footer_bg_help'))
                            ->live(),
                        ColorPicker::make('theme_card_bg')
                            ->label(__('resources.theme_customizer.card_bg'))
                            ->helperText(__('resources.theme_customizer.card_bg_help'))
                            ->live(),
                        ColorPicker::make('theme_border_color')
                            ->label(__('resources.theme_customizer.border_color'))
                            ->helperText(__('resources.theme_customizer.border_color_help'))
                            ->live(),
                        ColorPicker::make('theme_hero_from')
                            ->label(__('resources.theme_customizer.hero_from'))
                            ->helperText(__('resources.theme_customizer.hero_from_help'))
                            ->live(),
                        ColorPicker::make('theme_hero_to')
                            ->label(__('resources.theme_customizer.hero_to'))
                            ->helperText(__('resources.theme_customizer.hero_to_help'))
                            ->live(),
                    ])
                    ->columns(3),

                // Typography
                Section::make(__('resources.theme_customizer.section_typography'))
                    ->schema([
                        Select::make('theme_font_sans')
                            ->label(__('resources.theme_customizer.font_body'))
                            ->helperText(__('resources.theme_customizer.font_body_help'))
                            ->options([
                                'Inter'     => 'Inter — Modern & Bersih',
                                'Poppins'   => 'Poppins — Bulat & Ramah',
                                'Nunito'    => 'Nunito — Santai & Mudah Dibaca',
                                'Lato'      => 'Lato — Profesional',
                                'Open Sans' => 'Open Sans — Netral',
                                'Roboto'    => 'Roboto — Google Style',
                            ])
                            ->live(),
                        Select::make('theme_font_display')
                            ->label(__('resources.theme_customizer.font_heading'))
                            ->helperText(__('resources.theme_customizer.font_heading_help'))
                            ->options([
                                'Playfair Display'   => 'Playfair Display — Elegan & Klasik',
                                'Merriweather'       => 'Merriweather — Formal & Tegas',
                                'Lora'               => 'Lora — Hangat & Artistik',
                                'Cormorant Garamond' => 'Cormorant Garamond — Mewah',
                                'Montserrat'         => 'Montserrat — Modern & Bold',
                                'Raleway'            => 'Raleway — Stylish',
                            ])
                            ->live(),
                        Select::make('theme_font_size')
                            ->label(__('resources.theme_customizer.font_size'))
                            ->helperText(__('resources.theme_customizer.font_size_help'))
                            ->options([
                                'sm'   => __('resources.theme_customizer.font_size_sm'),
                                'base' => __('resources.theme_customizer.font_size_base'),
                                'lg'   => __('resources.theme_customizer.font_size_lg'),
                            ])
                            ->live(),
                    ])
                    ->columns(3),

                // Shape & Shadow
                Section::make(__('resources.theme_customizer.section_shape'))
                    ->schema([
                        Select::make('theme_border_radius')
                            ->label(__('resources.theme_customizer.border_radius'))
                            ->helperText(__('resources.theme_customizer.border_radius_help'))
                            ->options([
                                'rounded-none' => __('resources.theme_customizer.radius_none'),
                                'rounded'      => __('resources.theme_customizer.radius_small'),
                                'rounded-lg'   => __('resources.theme_customizer.radius_medium'),
                                'rounded-xl'   => __('resources.theme_customizer.radius_large'),
                                'rounded-2xl'  => __('resources.theme_customizer.radius_xlarge'),
                                'rounded-3xl'  => __('resources.theme_customizer.radius_full'),
                            ])
                            ->live(),
                        Select::make('theme_card_shadow')
                            ->label(__('resources.theme_customizer.card_shadow'))
                            ->helperText(__('resources.theme_customizer.card_shadow_help'))
                            ->options([
                                'shadow-none' => __('resources.theme_customizer.shadow_none'),
                                'shadow-sm'   => __('resources.theme_customizer.shadow_small'),
                                'shadow'      => __('resources.theme_customizer.shadow_medium'),
                                'shadow-md'   => __('resources.theme_customizer.shadow_large'),
                                'shadow-lg'   => __('resources.theme_customizer.shadow_xlarge'),
                            ])
                            ->live(),
                    ])
                    ->columns(2),

                // Live preview
                ViewField::make('preview')
                    ->view('filament.pages.theme-preview')
                    ->columnSpanFull(),
            ])
            ->statePath('');
    }

    public function applyPreset(string $key): void
    {
        $preset = self::PRESETS[$key] ?? null;
        if (! $preset) return;

        $this->theme_primary_color  = $preset['primary'];
        $this->theme_primary_hover  = $preset['hover'];
        $this->theme_accent_color   = $preset['accent'];
        $this->theme_bg_color       = $preset['bg'];
        $this->theme_text_color     = $preset['text'];
        $this->theme_navbar_bg      = $preset['navbar_bg'];
        $this->theme_footer_bg      = $preset['footer_bg'];
        $this->theme_card_bg        = $preset['card_bg'];
        $this->theme_border_color   = $preset['border'];
        $this->theme_btn_text_color = $preset['btn_text'];
        $this->theme_hero_from      = $preset['hero_from'];
        $this->theme_hero_to        = $preset['hero_to'];
        $this->theme_font_sans      = $preset['font_sans'];
        $this->theme_font_display   = $preset['font_display'];
        $this->theme_font_size      = $preset['font_size'];
        $this->theme_border_radius  = $preset['radius'];
        $this->theme_card_shadow    = $preset['shadow'];

        $this->themeForm->fill([
            'theme_primary_color'  => $this->theme_primary_color,
            'theme_primary_hover'  => $this->theme_primary_hover,
            'theme_accent_color'   => $this->theme_accent_color,
            'theme_bg_color'       => $this->theme_bg_color,
            'theme_text_color'     => $this->theme_text_color,
            'theme_navbar_bg'      => $this->theme_navbar_bg,
            'theme_footer_bg'      => $this->theme_footer_bg,
            'theme_card_bg'        => $this->theme_card_bg,
            'theme_border_color'   => $this->theme_border_color,
            'theme_btn_text_color' => $this->theme_btn_text_color,
            'theme_hero_from'      => $this->theme_hero_from,
            'theme_hero_to'        => $this->theme_hero_to,
            'theme_font_sans'      => $this->theme_font_sans,
            'theme_font_display'   => $this->theme_font_display,
            'theme_font_size'      => $this->theme_font_size,
            'theme_border_radius'  => $this->theme_border_radius,
            'theme_card_shadow'    => $this->theme_card_shadow,
        ]);
    }

    public function save(): void
    {
        $data = $this->themeForm->getState();

        $meta = [
            'theme_primary_color'  => 'Primary Color',
            'theme_primary_hover'  => 'Primary Hover Color',
            'theme_accent_color'   => 'Accent Color',
            'theme_bg_color'       => 'Background Color',
            'theme_text_color'     => 'Text Color',
            'theme_navbar_bg'      => 'Navbar Background',
            'theme_footer_bg'      => 'Footer Background',
            'theme_card_bg'        => 'Card Background',
            'theme_border_color'   => 'Border Color',
            'theme_btn_text_color' => 'Button Text Color',
            'theme_hero_from'      => 'Hero Gradient From',
            'theme_hero_to'        => 'Hero Gradient To',
            'theme_font_sans'      => 'Font Body',
            'theme_font_display'   => 'Font Heading',
            'theme_font_size'      => 'Font Size',
            'theme_border_radius'  => 'Border Radius',
            'theme_card_shadow'    => 'Card Shadow',
        ];

        foreach ($meta as $key => $label) {
            if (isset($data[$key]) && $data[$key] !== null) {
                Setting::updateOrCreate(
                    ['key' => $key],
                    ['value' => $data[$key], 'type' => 'text', 'group' => 'theme', 'label' => $label]
                );
            }
        }

        cache()->forget('app_settings');

        Notification::make()->title(__('resources.theme_customizer.saved'))->success()->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->label(__('resources.theme_customizer.save_btn'))
                ->icon(Heroicon::OutlinedCheck)
                ->action('save'),
        ];
    }
}
