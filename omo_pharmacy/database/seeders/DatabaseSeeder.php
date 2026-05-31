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
        // Create test user
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

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

        Medicine::create([
            'name' => 'Loratadine 10mg',
            'description' => 'Non-drowsy antihistamine for allergy relief',
            'price' => 9.75,
            'stock' => 210,
            'supplier_id' => $supplier2->id,
        ]);

        Medicine::create([
            'name' => 'Mefenamic Acid 500mg',
            'description' => 'Pain reliever for moderate pain',
            'price' => 18.00,
            'stock' => 160,
            'supplier_id' => $supplier1->id,
        ]);

        Medicine::create([
            'name' => 'Amlodipine 5mg',
            'description' => 'Used to treat high blood pressure',
            'price' => 14.25,
            'stock' => 95,
            'supplier_id' => $supplier3->id,
        ]);

        Medicine::create([
            'name' => 'Losartan 50mg',
            'description' => 'Angiotensin receptor blocker for hypertension',
            'price' => 16.50,
            'stock' => 140,
            'supplier_id' => $supplier3->id,
        ]);

        Medicine::create([
            'name' => 'Metformin 500mg',
            'description' => 'Blood sugar control medicine for type 2 diabetes',
            'price' => 20.00,
            'stock' => 175,
            'supplier_id' => $supplier1->id,
        ]);

        Medicine::create([
            'name' => 'Simvastatin 20mg',
            'description' => 'Cholesterol-lowering medication',
            'price' => 19.50,
            'stock' => 80,
            'supplier_id' => $supplier2->id,
        ]);

        Medicine::create([
            'name' => 'Salbutamol Inhaler',
            'description' => 'Relief for asthma and bronchospasm',
            'price' => 120.00,
            'stock' => 60,
            'supplier_id' => $supplier3->id,
        ]);

        Medicine::create([
            'name' => 'Vitamin C 500mg',
            'description' => 'Vitamin supplement for immune support',
            'price' => 7.25,
            'stock' => 320,
            'supplier_id' => $supplier2->id,
        ]);

        Medicine::create([
            'name' => 'Cetaphil Moisturizing Lotion',
            'description' => 'Skin hydration and barrier support',
            'price' => 145.00,
            'stock' => 55,
            'supplier_id' => $supplier1->id,
        ]);

        Medicine::create([
            'name' => 'Multivitamins',
            'description' => 'Daily nutritional supplement',
            'price' => 35.00,
            'stock' => 230,
            'supplier_id' => $supplier2->id,
        ]);

        // Create sales
        Sale::create([
            'medicine_id' => $medicine1->id,
            'quantity' => 2,
            'total_price' => 25.00,
            'sale_date' => now()->subDays(3),
        ]);

        Sale::create([
            'medicine_id' => $medicine2->id,
            'quantity' => 1,
            'total_price' => 25.00,
            'sale_date' => now()->subDays(2),
        ]);

        Sale::create([
            'medicine_id' => $medicine3->id,
            'quantity' => 3,
            'total_price' => 47.25,
            'sale_date' => now()->subDays(1),
        ]);

        Sale::create([
            'medicine_id' => $medicine4->id,
            'quantity' => 5,
            'total_price' => 42.50,
            'sale_date' => now(),
        ]);
    }
}
