<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Bot extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'name', 'instruction', 'origin_url', 'instruction_files', 'is_default'];

    protected $casts = [
        'instruction_files' => 'array',
        'is_default' => 'boolean',
    ];

    /**
     * Bootstrap the model and its traits.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($bot) {
            if (empty($bot->key)) {
                $bot->key = (string) Str::uuid();
            }
        });
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'key';
    }
}
