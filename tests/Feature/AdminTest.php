<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => User::ROLE_ADMIN]);
    }

    public function test_admin_can_create_category(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.categories.store'), [
                'name' => 'Minuman',
                'description' => 'Aneka minuman segar',
                'is_active' => '1',
            ])
            ->assertRedirect(route('admin.categories.index'));

        $this->assertDatabaseHas('categories', [
            'name' => 'Minuman',
            'slug' => 'minuman',
        ]);
    }

    public function test_admin_can_create_menu(): void
    {
        $category = Category::create(['name' => 'Makanan', 'is_active' => true]);

        $this->actingAs($this->admin())
            ->post(route('admin.menus.store'), [
                'category_id' => $category->id,
                'name' => 'Bakso',
                'price' => 20000,
                'stock' => 15,
                'is_available' => '1',
            ])
            ->assertRedirect(route('admin.menus.index'));

        $this->assertDatabaseHas('menus', [
            'name' => 'Bakso',
            'price' => 20000,
            'stock' => 15,
        ]);
    }

    public function test_admin_can_update_order_status(): void
    {
        $category = Category::create(['name' => 'Makanan', 'is_active' => true]);
        $menu = Menu::create([
            'category_id' => $category->id,
            'name' => 'Bakso',
            'price' => 20000,
            'stock' => 10,
            'is_available' => true,
        ]);

        $customer = User::factory()->create();
        $order = $customer->orders()->create([
            'code' => 'FD-TEST-1',
            'customer_name' => 'Budi',
            'phone' => '08123',
            'address' => 'Jl. Mawar',
            'status' => 'pending',
            'total' => 20000,
        ]);
        $order->items()->create([
            'menu_id' => $menu->id,
            'menu_name' => $menu->name,
            'price' => 20000,
            'quantity' => 1,
            'subtotal' => 20000,
        ]);

        $this->actingAs($this->admin())
            ->patch(route('admin.orders.status', $order), ['status' => 'selesai'])
            ->assertRedirect();

        $this->assertSame('selesai', $order->fresh()->status);
    }

    public function test_cancelling_order_restores_stock(): void
    {
        $category = Category::create(['name' => 'Makanan', 'is_active' => true]);
        $menu = Menu::create([
            'category_id' => $category->id,
            'name' => 'Bakso',
            'price' => 20000,
            'stock' => 8,
            'is_available' => true,
        ]);

        $order = User::factory()->create()->orders()->create([
            'code' => 'FD-TEST-2',
            'customer_name' => 'Budi',
            'phone' => '08123',
            'address' => 'Jl. Mawar',
            'status' => 'pending',
            'total' => 40000,
        ]);
        $order->items()->create([
            'menu_id' => $menu->id,
            'menu_name' => $menu->name,
            'price' => 20000,
            'quantity' => 2,
            'subtotal' => 40000,
        ]);

        $this->actingAs($this->admin())
            ->patch(route('admin.orders.status', $order), ['status' => 'dibatalkan']);

        $this->assertSame(10, $menu->fresh()->stock);
    }

    public function test_admin_cannot_delete_category_with_menus(): void
    {
        $category = Category::create(['name' => 'Makanan', 'is_active' => true]);
        Menu::create([
            'category_id' => $category->id,
            'name' => 'Bakso',
            'price' => 20000,
            'stock' => 10,
            'is_available' => true,
        ]);

        $this->actingAs($this->admin())
            ->delete(route('admin.categories.destroy', $category))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }

    public function test_admin_dashboard_shows_stats(): void
    {
        $this->actingAs($this->admin())
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Admin Dashboard');
    }
}
