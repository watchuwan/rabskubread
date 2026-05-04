<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductResourceTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create();
        $this->admin->assignRole('super_admin');
    }

    public function test_admin_can_access_products_list(): void
    {
        $this->actingAs($this->admin)
            ->get('/admin/products')
            ->assertStatus(200);
    }

    public function test_product_slug_auto_generated(): void
    {
        $product = Product::factory()->create(['name' => 'Roti Tawar Gandum']);
        $this->assertEquals('roti-tawar-gandum', $product->slug);
    }

    public function test_product_soft_delete(): void
    {
        $product = Product::factory()->create();
        $product->delete();

        $this->assertSoftDeleted('products', ['id' => $product->id]);
        $this->assertNull(Product::find($product->id));
    }

    public function test_product_in_stock_accessor(): void
    {
        $inStock  = Product::factory()->create(['stock' => 5]);
        $outStock = Product::factory()->create(['stock' => 0]);

        $this->assertTrue($inStock->in_stock);
        $this->assertFalse($outStock->in_stock);
    }

    public function test_product_low_stock_accessor(): void
    {
        $product = Product::factory()->create(['stock' => 3, 'low_stock_threshold' => 5]);
        $this->assertTrue($product->low_stock);
    }

    public function test_decrement_stock(): void
    {
        $product = Product::factory()->create(['stock' => 10]);
        $result  = $product->decrementStock(3);

        $this->assertTrue($result);
        $this->assertEquals(7, $product->fresh()->stock);
    }

    public function test_decrement_stock_fails_if_insufficient(): void
    {
        $product = Product::factory()->create(['stock' => 2]);
        $result  = $product->decrementStock(5);

        $this->assertFalse($result);
        $this->assertEquals(2, $product->fresh()->stock);
    }

    public function test_increment_stock(): void
    {
        $product = Product::factory()->create(['stock' => 5]);
        $product->incrementStock(3);

        $this->assertEquals(8, $product->fresh()->stock);
    }
}
