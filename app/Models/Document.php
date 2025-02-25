<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_image',
        'document_type',
        'approval',
        'approved_by',
        'user_id',
        'status',
    ];
    protected $casts = [
        'status' => 'boolean',
        'approval' => 'boolean',
    ];
    
}
