<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Menu;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderFlowTest extends TestCase
{
    use RefreshDatabase;

    private function makeMenu(array $attributes = []): Menu
    {
        $category = Category::create([
            'name' => 'Makanan',
            'is_active' => true,
        ]);

        return Menu::create(array_merge([
            'category_id' => $category->id,
            'name' => 'Nasi Goreng',
            'price' => 25000,
            'stock' => 10,
            'is_available' => true,
        ], $attributes));
    }

    public function test_customer_can_browse_menu(): void
    {
        $menu = $this->makeMenu();

        $this->actingAs(User::factory()->create())
            ->get(route('menus.index'))
            ->assertOk()
            ->assertSee($menu->name);
    }

    public function test_customer_can_add_menu_to_cart(): void
    {
        $menu = $this->makeMenu();
        $customer = User::factory()->create();

        $this->actingAs($customer)
            ->post(route('cart.store', $menu), ['quantity' => 2])
            ->assertRedirect();

        $this->actingAs($customer)
            ->get(route('cart.index'))
            ->assertOk()
            ->assertSee($menu->name)
            ->assertSee('50.000');
    }

    public function test_customer_can_checkout_and_stock_is_reduced(): void
    {
        $menu = $this->makeMenu();
        $customer = User::factory()->create();

        $this->actingAs($customer)
            ->post(route('cart.store', $menu), ['quantity' => 2]);

        $response = $this->actingAs($customer)
            ->post(route('orders.store'), [
                'customer_name' => 'Budi',
                'phone' => '08123456789',
                'address' => 'Jl. Mawar No. 1',
                'notes' => 'Pedas',
            ]);

        $order = Order::first();

        $response->assertRedirect(route('orders.show', $order));

        $this->assertDatabaseHas('orders', [
            'user_id' => $customer->id,
            'status' => 'pending',
            'total' => 50000,
        ]);

        $this->assertDatabaseHas('order_items', [
            'menu_id' => $menu->id,
            'quantity' => 2,
            'subtotal' => 50000,
        ]);

        $this->assertSame(8, $menu->fresh()->stock);
    }

    public function test_customer_cannot_checkout_with_empty_cart(): void
    {
        $this->actingAs(User::factory()->create())
            ->post(route('orders.store'), [
                'customer_name' => 'Budi',
                'phone' => '08123456789',
                'address' => 'Jl. Mawar No. 1',
            ])
            ->assertRedirect(route('cart.index'));

        $this->assertDatabaseCount('orders', 0);
    }

    public function test_customer_can_cancel_order_and_stock_is_restored(): void
    {
        $menu = $this->makeMenu();
        $customer = User::factory()->create();

        $this->actingAs($customer)->post(route('cart.store', $menu), ['quantity' => 3]);

        $this->actingAs($customer)->post(route('orders.store'), [
            'customer_name' => 'Budi',
            'phone' => '08123456789',
            'address' => 'Jl. Mawar No. 1',
        ]);

        $order = Order::first();

        $this->actingAs($customer)
            ->post(route('orders.cancel', $order))
            ->assertRedirect();

        $this->assertSame('dibatalkan', $order->fresh()->status);
        $this->assertSame(10, $menu->fresh()->stock);
    }

    public function test_customer_cannot_view_other_customer_order(): void
    {
        $menu = $this->makeMenu();
        $owner = User::factory()->create();
        $other = User::factory()->create();

        $this->actingAs($owner)->post(route('cart.store', $menu), ['quantity' => 1]);
        $this->actingAs($owner)->post(route('orders.store'), [
            'customer_name' => 'Budi',
            'phone' => '08123456789',
            'address' => 'Jl. Mawar No. 1',
        ]);

        $order = Order::first();

        $this->actingAs($other)
            ->get(route('orders.show', $order))
            ->assertForbidden();
    }

    public function test_cannot_add_out_of_stock_menu(): void
    {
        $menu = $this->makeMenu(['stock' => 0]);

        $this->actingAs(User::factory()->create())
            ->post(route('cart.store', $menu))
            ->assertSessionHas('error');
    }
}
