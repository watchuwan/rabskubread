<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_slug_auto_generated(): void
    {
        $category = Category::factory()->create(['name' => 'Roti Tawar']);
        $this->assertEquals('roti-tawar', $category->slug);
    }

    public function test_category_hierarchical_parent_child(): void
    {
        $parent = Category::factory()->create();
        $child  = Category::factory()->create(['parent_id' => $parent->id]);

        $this->assertEquals($parent->id, $child->parent_id);
    }

    public function test_category_active_scope(): void
    {
        Category::factory()->create(['is_active' => true]);
        Category::factory()->create(['is_active' => false]);

        $this->assertEquals(1, Category::active()->count());
    }

    public function test_admin_can_access_panel(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('super_admin');
        $this->assertTrue($admin->canAccessPanel(app('filament')->getPanel('admin')));
    }
}
