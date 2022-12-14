<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Company extends Eloquent
{
    use HasFactory;
    protected $fillable = [
        'website',
        'name',
        'founded',
        'size_min',
        'size_max',
        'locality',
        'region',
        'country',
        'industry',
        'linkedin_url',
    ];
    protected $primaryKey = 'id';

    // Display companies' listing ordered by size - ascending or descending.
    public function indexBySize($order)
    {
     return $this->project(['_id' => 0, 'updated_at' => 0, 'created_at' => 0])->orderBy('size_min', $order)->get();
    }

    // Display companies' listing ordered by foundation date - ascending or descending.
    public function indexByFounded($order)
    {
     return $this->project(['_id' => 0, 'updated_at' => 0, 'created_at' => 0])->orderBy('founded', $order)->get();
    }

    // Display following data: Number of companies in each industry, 
    // Number of companies in each size range, 
    // Number of companies in each year of creation
    public function indexByAnalytics()
    {
        $industry =  $this->raw()->aggregate([['$group' => ['_id' => '$industry', 'count' => ['$sum' => 1]]],
        ['$sort' => ['_id' => 1]]])->toArray();
        $size =  $this->raw()->aggregate([['$group' => ['_id' => ['size_min' => '$size_min', 'size_max' => '$size_max'],'count' => ['$sum' => 1]]],
        ['$sort' => ['_id' => 1]]])->toArray();
        $founded =  $this->raw()->aggregate([['$group' => ['_id' => '$founded', 'count' => ['$sum' => 1]]], 
        ['$sort' => ['_id' => 1]]])->toArray();

        return [
            'industry' => $industry,
            'size' => $size,
            'founded' => $founded,
        ];
    }
}

