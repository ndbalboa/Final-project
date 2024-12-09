<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DocumentTypesSeeder extends Seeder
{
    public function run()
    {
        // Sample document types
        $documentTypes = [
            'Travel Order',
            'Special Order',
            'Office Order',
            'Notice of Meeting',
            'COA Circular',
            'Budget Circular',
            'CHED Circular',
        ];

        // Insert document types into the 'document_types' table
        foreach ($documentTypes as $type) {
            DB::table('document_types')->insert([
                'document_type' => $type,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
