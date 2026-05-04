<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class ThemeSeeder extends Seeder
{
    /**
     * Available theme presets.
     * To switch theme, change $active to the desired key.
     */
    private string $active = 'amber_classic';

    private array $themes = [
        'amber_classic' => [
            'label'               => 'Amber Classic (Default)',
            'theme_primary_color' => '#F59E0B',
            'theme_primary_hover' => '#D97706',
            'theme_accent_color'  => '#FFE8A3',
            'theme_bg_color'      => '#FFFEF9',
            'theme_text_color'    => '#1C1917',
            'theme_navbar_bg'     => '#FFFFFF',
            'theme_footer_bg'     => '#1C1917',
            'theme_card_bg'       => '#FFFFFF',
            'theme_border_color'  => '#E7E5E4',
            'theme_btn_text_color'=> '#FFFFFF',
            'theme_hero_from'     => '#FEF3C7',
            'theme_hero_to'       => '#FFFEF9',
            'theme_font_sans'     => 'Inter',
            'theme_font_display'  => 'Playfair Display',
            'theme_font_size'     => 'base',
            'theme_border_radius' => 'rounded',
            'theme_card_shadow'   => 'shadow-sm',
        ],
        'rose_bakery' => [
            'label'               => 'Rose Bakery — Hangat & Feminin',
            'theme_primary_color' => '#F43F5E',
            'theme_primary_hover' => '#E11D48',
            'theme_accent_color'  => '#FFE4E6',
            'theme_bg_color'      => '#FFF5F7',
            'theme_text_color'    => '#1C1917',
            'theme_navbar_bg'     => '#FFFFFF',
            'theme_footer_bg'     => '#881337',
            'theme_card_bg'       => '#FFFFFF',
            'theme_border_color'  => '#FECDD3',
            'theme_btn_text_color'=> '#FFFFFF',
            'theme_hero_from'     => '#FFE4E6',
            'theme_hero_to'       => '#FFF5F7',
            'theme_font_sans'     => 'Poppins',
            'theme_font_display'  => 'Cormorant Garamond',
            'theme_font_size'     => 'base',
            'theme_border_radius' => 'rounded-2xl',
            'theme_card_shadow'   => 'shadow-md',
        ],
        'forest_artisan' => [
            'label'               => 'Forest Artisan — Natural & Organik',
            'theme_primary_color' => '#16A34A',
            'theme_primary_hover' => '#15803D',
            'theme_accent_color'  => '#DCFCE7',
            'theme_bg_color'      => '#F0FDF4',
            'theme_text_color'    => '#14532D',
            'theme_navbar_bg'     => '#FFFFFF',
            'theme_footer_bg'     => '#14532D',
            'theme_card_bg'       => '#FFFFFF',
            'theme_border_color'  => '#BBF7D0',
            'theme_btn_text_color'=> '#FFFFFF',
            'theme_hero_from'     => '#DCFCE7',
            'theme_hero_to'       => '#F0FDF4',
            'theme_font_sans'     => 'Nunito',
            'theme_font_display'  => 'Lora',
            'theme_font_size'     => 'base',
            'theme_border_radius' => 'rounded-xl',
            'theme_card_shadow'   => 'shadow',
        ],
        'midnight_luxury' => [
            'label'               => 'Midnight Luxury — Elegan & Premium',
            'theme_primary_color' => '#7C3AED',
            'theme_primary_hover' => '#6D28D9',
            'theme_accent_color'  => '#EDE9FE',
            'theme_bg_color'      => '#FAF5FF',
            'theme_text_color'    => '#1C1917',
            'theme_navbar_bg'     => '#FFFFFF',
            'theme_footer_bg'     => '#2E1065',
            'theme_card_bg'       => '#FFFFFF',
            'theme_border_color'  => '#DDD6FE',
            'theme_btn_text_color'=> '#FFFFFF',
            'theme_hero_from'     => '#EDE9FE',
            'theme_hero_to'       => '#FAF5FF',
            'theme_font_sans'     => 'Lato',
            'theme_font_display'  => 'Merriweather',
            'theme_font_size'     => 'base',
            'theme_border_radius' => 'rounded-lg',
            'theme_card_shadow'   => 'shadow-lg',
        ],
        'ocean_fresh' => [
            'label'               => 'Ocean Fresh — Segar & Modern',
            'theme_primary_color' => '#0EA5E9',
            'theme_primary_hover' => '#0284C7',
            'theme_accent_color'  => '#E0F2FE',
            'theme_bg_color'      => '#F0F9FF',
            'theme_text_color'    => '#0C4A6E',
            'theme_navbar_bg'     => '#FFFFFF',
            'theme_footer_bg'     => '#0C4A6E',
            'theme_card_bg'       => '#FFFFFF',
            'theme_border_color'  => '#BAE6FD',
            'theme_btn_text_color'=> '#FFFFFF',
            'theme_hero_from'     => '#E0F2FE',
            'theme_hero_to'       => '#F0F9FF',
            'theme_font_sans'     => 'Roboto',
            'theme_font_display'  => 'Montserrat',
            'theme_font_size'     => 'base',
            'theme_border_radius' => 'rounded-xl',
            'theme_card_shadow'   => 'shadow-sm',
        ],
        'terracotta' => [
            'label'               => 'Terracotta — Hangat & Rustic',
            'theme_primary_color' => '#C2410C',
            'theme_primary_hover' => '#9A3412',
            'theme_accent_color'  => '#FFEDD5',
            'theme_bg_color'      => '#FFF7ED',
            'theme_text_color'    => '#431407',
            'theme_navbar_bg'     => '#FFFFFF',
            'theme_footer_bg'     => '#431407',
            'theme_card_bg'       => '#FFFFFF',
            'theme_border_color'  => '#FED7AA',
            'theme_btn_text_color'=> '#FFFFFF',
            'theme_hero_from'     => '#FFEDD5',
            'theme_hero_to'       => '#FFF7ED',
            'theme_font_sans'     => 'Open Sans',
            'theme_font_display'  => 'Raleway',
            'theme_font_size'     => 'base',
            'theme_border_radius' => 'rounded',
            'theme_card_shadow'   => 'shadow',
        ],
    ];

    public function run(): void
    {
        $theme = $this->themes[$this->active];

        $this->command->info("Applying theme: {$theme['label']}");

        $labels = [
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

        foreach ($theme as $key => $value) {
            if ($key === 'label') continue;
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'type' => 'text', 'group' => 'theme', 'label' => $labels[$key]]
            );
        }

        cache()->forget('app_settings');

        $this->command->info('✅ Theme applied successfully!');
        $this->command->newLine();
        $this->command->line('Available themes:');
        foreach ($this->themes as $key => $t) {
            $marker = $key === $this->active ? '→' : ' ';
            $this->command->line("  {$marker} {$key}: {$t['label']}");
        }
    }
}
