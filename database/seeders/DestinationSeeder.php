<?php

namespace Database\Seeders;

use App\Models\Destination;
use Illuminate\Database\Seeder;

class DestinationSeeder extends Seeder
{
    public function run()
    {
        $destinations = DestinationData::getDestinations();

        foreach ($destinations as $destinationData) {
            // Remove 'images' key as it is not a column in destinations table
            $dataToCreate = $destinationData;
            unset($dataToCreate['images']);
            
            try {
                Destination::create($dataToCreate);
            } catch (\Exception $e) {
                $this->command->error('Error creating destination ' . $dataToCreate['name'] . ': ' . $e->getMessage());
            }
        }
    }
}