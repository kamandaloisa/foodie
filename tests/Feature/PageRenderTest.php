<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PageRenderTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_pages_render(): void
    {
        $customer = User::factory()->create(['role' => User::ROLE_CUSTOMER]);
        $category = Category::create(['name' => 'Makanan', 'is_active' => true]);
        $menu = Menu::create([
            'category_id' => $category->id,
            'name' => 'Bakso',
            'price' => 20000,
            'stock' => 10,
            'is_available' => true,
        ]);

        $this->actingAs($customer)->get(route('dashboard'))->assertOk();
        $this->actingAs($customer)->get(route('menus.index'))->assertOk();
        $this->actingAs($customer)->get(route('menus.show', $menu))->assertOk();
        $this->actingAs($customer)->get(route('cart.index'))->assertOk();
        $this->actingAs($customer)->get(route('orders.index'))->assertOk();
    }

    public function test_admin_pages_render(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $category = Category::create(['name' => 'Makanan', 'is_active' => true]);
        $menu = Menu::create([
            'category_id' => $category->id,
            'name' => 'Bakso',
            'price' => 20000,
            'stock' => 10,
            'is_available' => true,
        ]);

        $this->actingAs($admin)->get(route('admin.dashboard'))->assertOk();
        $this->actingAs($admin)->get(route('admin.categories.index'))->assertOk();
        $this->actingAs($admin)->get(route('admin.categories.create'))->assertOk();
        $this->actingAs($admin)->get(route('admin.categories.edit', $category))->assertOk();
        $this->actingAs($admin)->get(route('admin.menus.index'))->assertOk();
        $this->actingAs($admin)->get(route('admin.menus.create'))->assertOk();
        $this->actingAs($admin)->get(route('admin.menus.edit', $menu))->assertOk();
        $this->actingAs($admin)->get(route('admin.orders.index'))->assertOk();
    }
}
