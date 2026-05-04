<?php

namespace Tests\Feature\Admin;

use App\Filament\Pages\ThemeCustomizer;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ThemeCustomizerTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create();
        $this->admin->assignRole('super_admin');
    }

    public function test_admin_can_access_theme_customizer(): void
    {
        Setting::updateOrCreate(['key' => 'theme_primary_color'], ['value' => '#F59E0B', 'type' => 'text', 'group' => 'theme', 'label' => 'Primary']);
        Setting::updateOrCreate(['key' => 'theme_primary_hover'],  ['value' => '#D97706', 'type' => 'text', 'group' => 'theme', 'label' => 'Hover']);
        Setting::updateOrCreate(['key' => 'theme_accent_color'],   ['value' => '#FFE8A3', 'type' => 'text', 'group' => 'theme', 'label' => 'Accent']);
        Setting::updateOrCreate(['key' => 'theme_font_sans'],      ['value' => 'Inter',   'type' => 'text', 'group' => 'theme', 'label' => 'Font Sans']);
        Setting::updateOrCreate(['key' => 'theme_font_display'],   ['value' => 'Playfair Display', 'type' => 'text', 'group' => 'theme', 'label' => 'Font Display']);

        Livewire::actingAs($this->admin)
            ->test(ThemeCustomizer::class)
            ->assertStatus(200);
    }

    public function test_presets_constant_has_required_keys(): void
    {
        foreach (ThemeCustomizer::PRESETS as $key => $preset) {
            $this->assertArrayHasKey('label',        $preset, "{$key} missing label");
            $this->assertArrayHasKey('primary',      $preset, "{$key} missing primary");
            $this->assertArrayHasKey('hover',        $preset, "{$key} missing hover");
            $this->assertArrayHasKey('accent',       $preset, "{$key} missing accent");
            $this->assertArrayHasKey('font_sans',    $preset, "{$key} missing font_sans");
            $this->assertArrayHasKey('font_display', $preset, "{$key} missing font_display");
        }

        $this->assertCount(6, ThemeCustomizer::PRESETS);
    }

    private function seedThemeSettings(): void
    {
        Setting::updateOrCreate(['key' => 'theme_primary_color'], ['value' => '#F59E0B', 'type' => 'text', 'group' => 'theme', 'label' => 'Primary']);
        Setting::updateOrCreate(['key' => 'theme_primary_hover'],  ['value' => '#D97706', 'type' => 'text', 'group' => 'theme', 'label' => 'Hover']);
        Setting::updateOrCreate(['key' => 'theme_accent_color'],   ['value' => '#FFE8A3', 'type' => 'text', 'group' => 'theme', 'label' => 'Accent']);
        Setting::updateOrCreate(['key' => 'theme_font_sans'],      ['value' => 'Inter',   'type' => 'text', 'group' => 'theme', 'label' => 'Font Sans']);
        Setting::updateOrCreate(['key' => 'theme_font_display'],   ['value' => 'Playfair Display', 'type' => 'text', 'group' => 'theme', 'label' => 'Font Display']);
    }

    public function test_apply_preset_updates_form_state(): void
    {
        $this->seedThemeSettings();

        $component = Livewire::actingAs($this->admin)
            ->test(ThemeCustomizer::class)
            ->call('applyPreset', 'rose_bakery');

        $preset = ThemeCustomizer::PRESETS['rose_bakery'];
        $component->assertSet('theme_primary_color', $preset['primary'])
                  ->assertSet('theme_font_sans',     $preset['font_sans']);
    }

    public function test_save_persists_theme_to_database(): void
    {
        $this->seedThemeSettings();

        Livewire::actingAs($this->admin)
            ->test(ThemeCustomizer::class)
            ->call('applyPreset', 'forest_artisan')
            ->call('save');

        $this->assertDatabaseHas('settings', [
            'key'   => 'theme_primary_color',
            'value' => ThemeCustomizer::PRESETS['forest_artisan']['primary'],
        ]);
        $this->assertDatabaseHas('settings', [
            'key'   => 'theme_font_sans',
            'value' => ThemeCustomizer::PRESETS['forest_artisan']['font_sans'],
        ]);
    }
}
