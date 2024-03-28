<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $table = 'activities';

    public $timestamps = false;

    protected $fillable = [
        'title',
        'description',
        'color',
        'user_id',
        'created_at',
        'elapsed_time',
        'priority',
        'category_id'
    ];

    public function addActivity(string $title, string $description, string $color, int $user_id, float $elapsed_time, int $priority, int $category_id)
    {
        $newActivity = static::create([
            'title' => $title,
            'description' => $description,
            'color' => $color,
            'user_id' => $user_id,
            'created_at' => date(DATE_ATOM),
            'elapsed_time' => $elapsed_time,
            'priority' => $priority,
            'category_id' => $category_id
        ]);

        if($newActivity) {
            $data = [
                'title' => $title,
                'description' => $description,
                'color' => $color,
                'user_id' => $user_id,
                'created_at' => date(DATE_ATOM),
                'elapsed_time' => $elapsed_time,
                'priority' => $priority,
                'category_id' => $category_id,
                'id' => $newActivity->id
            ];
            return ['status' => 202, 'activity' => $data];
        }
        return ['status' => 500, 'id' => null];
    }
}
