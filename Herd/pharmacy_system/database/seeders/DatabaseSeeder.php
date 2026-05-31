<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Supplier;
use App\Models\Medicine;
use App\Models\Sale;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create test user only if not exists
        if (!User::where('email', 'test@example.com')->exists()) {
            User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);
        }

        // Create suppliers
        $supplier1 = Supplier::create([
            'name' => 'MediCore Distributors',
            'contact' => '+63 912 345 6789',
            'address' => '123 Health Avenue, Manila',
        ]);

        $supplier2 = Supplier::create([
            'name' => 'PharmaPro Supplies',
            'contact' => '+63 917 555 1234',
            'address' => '456 Medical Plaza, Quezon City',
        ]);

        $supplier3 = Supplier::create([
            'name' => 'Global MedTech Inc.',
            'contact' => '+63 922 888 9999',
            'address' => '789 Pharma Street, Makati',
        ]);

        $supplier4 = Supplier::create([
            'name' => 'HealthFirst Pharma',
            'contact' => '+63 933 111 2222',
            'address' => '101 Wellness Road, Pasig',
        ]);

        $supplier5 = Supplier::create([
            'name' => 'BioGenix Solutions',
            'contact' => '+63 944 333 4444',
            'address' => '202 Biotech Lane, Taguig',
        ]);

        // Create medicines
        $medicine1 = Medicine::create([
            'name' => 'Paracetamol 500mg',
            'description' => 'Pain reliever and fever reducer',
            'price' => 12.50,
            'stock' => 250,
            'supplier_id' => $supplier1->id,
        ]);

        $medicine2 = Medicine::create([
            'name' => 'Amoxicillin 250mg',
            'description' => 'Antibiotic for bacterial infections',
            'price' => 25.00,
            'stock' => 120,
            'supplier_id' => $supplier1->id,
        ]);

        $medicine3 = Medicine::create([
            'name' => 'Ibuprofen 400mg',
            'description' => 'Anti-inflammatory pain reliever',
            'price' => 15.75,
            'stock' => 180,
            'supplier_id' => $supplier2->id,
        ]);

        $medicine4 = Medicine::create([
            'name' => 'Cetirizine 10mg',
            'description' => 'Antihistamine for allergies',
            'price' => 8.50,
            'stock' => 300,
            'supplier_id' => $supplier2->id,
        ]);

        $medicine5 = Medicine::create([
            'name' => 'Omeprazole 20mg',
            'description' => 'Proton pump inhibitor for acid reflux',
            'price' => 22.00,
            'stock' => 45,
            'supplier_id' => $supplier3->id,
        ]);

        $medicine6 = Medicine::create([
            'name' => 'Metformin 500mg',
            'description' => 'Oral diabetes medicine',
            'price' => 18.00,
            'stock' => 100,
            'supplier_id' => $supplier4->id,
        ]);

        $medicine7 = Medicine::create([
            'name' => 'Loperamide 2mg',
            'description' => 'Anti-diarrheal medication',
            'price' => 10.00,
            'stock' => 80,
            'supplier_id' => $supplier5->id,
        ]);

        $medicine8 = Medicine::create([
            'name' => 'Amlodipine 5mg',
            'description' => 'Antihypertensive medication',
            'price' => 20.00,
            'stock' => 60,
            'supplier_id' => $supplier5->id,
        ]);

        // Create sales
        Sale::create([
            'medicine_id' => $medicine1->id,
            'medicine_name' => $medicine1->name,
            'quantity' => 2,
            'unit_price' => $medicine1->price,
            'total_amount' => 25.00,
            'total_price' => 25.00,
            'customer_name' => 'Juan Dela Cruz',
            'sale_date' => now()->subDays(3),
        ]);

        Sale::create([
            'medicine_id' => $medicine2->id,
            'medicine_name' => $medicine2->name,
            'quantity' => 1,
            'unit_price' => $medicine2->price,
            'total_amount' => 25.00,
            'total_price' => 25.00,
            'customer_name' => 'Maria Santos',
            'sale_date' => now()->subDays(2),
        ]);

        Sale::create([
            'medicine_id' => $medicine3->id,
            'medicine_name' => $medicine3->name,
            'quantity' => 3,
            'unit_price' => $medicine3->price,
            'total_amount' => 47.25,
            'total_price' => 47.25,
            'customer_name' => 'Pedro Reyes',
            'sale_date' => now()->subDays(1),
        ]);
    }
}
