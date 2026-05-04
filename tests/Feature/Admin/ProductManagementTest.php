<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_created_with_slug(): void
    {
        $product = Product::factory()->create(['name' => 'Roti Manis Coklat']);
        $this->assertEquals('roti-manis-coklat', $product->slug);
    }

    public function test_product_update(): void
    {
        $product = Product::factory()->create(['name' => 'Old Name']);
        $product->update(['name' => 'New Name']);

        $this->assertDatabaseHas('products', ['id' => $product->id, 'name' => 'New Name']);
    }

    public function test_product_soft_delete(): void
    {
        $product = Product::factory()->create();
        $product->delete();

        $this->assertSoftDeleted('products', ['id' => $product->id]);
    }

    public function test_admin_can_update_stock(): void
    {
        $product = Product::factory()->create(['stock' => 100]);

        $product->decrementStock(10);
        $this->assertEquals(90, $product->fresh()->stock);

        $product->incrementStock(20);
        $this->assertEquals(110, $product->fresh()->stock);
    }

    public function test_super_admin_can_access_panel(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('super_admin');

        $this->assertTrue($admin->canAccessPanel(app('filament')->getPanel('admin')));
    }
}
