<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    public $timestamps = false;

    protected $fillable = [
        'title',
        'description',
        'color',
        'user_id',
        'created_at'
    ];


    public function addCategory(string $title, string $description, string $color, int $user_id)
    {
        $newCategory = static::create([
            'title' => $title,
            'description' => $description,
            'color' => $color,
            'user_id' => $user_id,
            'created_at' => date(DATE_ATOM)
        ]);

        if($newCategory) {
            $data = [
                'title' => $title,
                'description' => $description,
                'color' => $color,
                'id' => $newCategory->id
            ];
            return ['status' => 202, 'category' => $data];
        }
        return ['status' => 500, 'id' => null];
    }
}
