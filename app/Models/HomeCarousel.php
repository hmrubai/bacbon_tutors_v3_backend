<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeCarousel extends Model
{
    use HasFactory;

    protected $table = 'home_carousels';

    protected $fillable = [
        'title',
        'description',
        'thumbnail',
        'background_color',
        'has_button',
        'button_text',
        'button_link',
        'sorting_order',
        'is_active'
    ];

    protected $casts = [
        'has_button' => 'boolean',
        'is_active' => 'boolean',
    ];
}
