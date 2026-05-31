<?php

namespace Tests\Feature;

use App\Models\Medicine;
use App\Models\Sale;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SaleStockTest extends TestCase
{
    use RefreshDatabase;

    public function test_sale_creation_reduces_stock_and_blocks_over_quantity_sales(): void
    {
        $user = User::factory()->create();
        $supplier = Supplier::create([
            'name' => 'Main Supplier',
            'contact' => '09171234567',
            'address' => 'Main Street',
        ]);

        $medicine = Medicine::create([
            'name' => 'Paracetamol',
            'description' => 'Pain relief',
            'price' => 10.00,
            'stock' => 5,
            'supplier_id' => $supplier->id,
        ]);

        $this->actingAs($user)
            ->post(route('sales.store'), [
                'medicine_id' => $medicine->id,
                'quantity' => 2,
                'customer_name' => 'Walk-in Customer',
            ])
            ->assertRedirect(route('records.sales'));

        $this->assertDatabaseHas('sales', [
            'medicine_id' => $medicine->id,
            'quantity' => 2,
            'medicine_name' => 'Paracetamol',
        ]);

        $this->assertSame(3, $medicine->fresh()->stock);

        $this->actingAs($user)
            ->post(route('sales.store'), [
                'medicine_id' => $medicine->id,
                'quantity' => 10,
                'customer_name' => 'Walk-in Customer',
            ])
            ->assertSessionHasErrors(['quantity']);

        $this->assertSame(3, $medicine->fresh()->stock);
        $this->assertSame(1, Sale::query()->count());
    }
}