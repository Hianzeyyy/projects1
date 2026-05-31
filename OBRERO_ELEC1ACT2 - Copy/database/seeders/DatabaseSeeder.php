<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Medicine;
use App\Models\Supplier;
use App\Models\Inventory;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create medicines
        $med1 = Medicine::create([
            'name' => 'Paracetamol 500mg',
            'category' => 'Analgesic',
            'manufacturer' => 'PharmaCorp',
            'price' => 5.99,
            'description' => 'Pain relief and fever reducer',
            'expiry_date' => '2026-12-31',
        ]);

        $med2 = Medicine::create([
            'name' => 'Amoxicillin 250mg',
            'category' => 'Antibiotic',
            'manufacturer' => 'MediLab',
            'price' => 12.50,
            'description' => 'Bacterial infection treatment',
            'expiry_date' => '2026-09-15',
        ]);

        $med3 = Medicine::create([
            'name' => 'Cetirizine 10mg',
            'category' => 'Antihistamine',
            'manufacturer' => 'AllergyMed',
            'price' => 8.75,
            'description' => 'Allergy relief medication',
            'expiry_date' => '2027-03-20',
        ]);

        // Create suppliers
        $sup1 = Supplier::create([
            'name' => 'MediSupply Co.',
            'email' => 'contact@medisupply.com',
            'phone' => '+1-555-0101',
            'address' => '123 Medical Plaza, Healthcare City',
        ]);

        $sup2 = Supplier::create([
            'name' => 'Global Pharma Distributors',
            'email' => 'sales@globalpharma.com',
            'phone' => '+1-555-0202',
            'address' => '456 Distribution Center, Pharma District',
        ]);

        // Create inventory
        Inventory::create([
            'medicine_id' => $med1->id,
            'medicine_name' => $med1->name,
            'quantity' => 500,
            'batch_number' => 'BATCH001',
            'expiry_date' => '2026-12-31',
            'supplier_id' => $sup1->id,
            'supplier_name' => $sup1->name,
            'reorder_level' => 100,
        ]);

        Inventory::create([
            'medicine_id' => $med2->id,
            'medicine_name' => $med2->name,
            'quantity' => 250,
            'batch_number' => 'BATCH002',
            'expiry_date' => '2026-09-15',
            'supplier_id' => $sup2->id,
            'supplier_name' => $sup2->name,
            'reorder_level' => 50,
        ]);

        Inventory::create([
            'medicine_id' => $med3->id,
            'medicine_name' => $med3->name,
            'quantity' => 80,
            'batch_number' => 'BATCH003',
            'expiry_date' => '2027-03-20',
            'supplier_id' => $sup1->id,
            'supplier_name' => $sup1->name,
            'reorder_level' => 100,
        ]);
    }
}
