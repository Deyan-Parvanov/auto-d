<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Listing extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'make',
        'model',
        'year',
        'engine_type',
        'horsepower',
        'total_kilometers',
        'color',
        'city',
        'price',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(
            \App\Models\User::class,
            'by_user_id'
        );
    }

    public function scopeMostRecent(Builder $query): Builder
    {
        return $query->orderByDesc('created_at');
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query->when(
            $filters['priceFrom'] ?? false,
            fn ($query, $value) => $query->where('price', '>=', $value)
        )->when(
            $filters['priceTo'] ?? false,
            fn ($query, $value) => $query->where('price', '<=', $value)
        )->when(
            $filters['make'] ?? false,
            fn ($query, $value) => $query->where('make', '=', $value)
        )->when(
            $filters['engine'] ?? false,
            fn ($query, $value) => $query->where('engine_type', '=', $value)
        )->when(
            $filters['kmFrom'] ?? false,
            fn ($query, $value) => $query->where('total_kilometers', '>=', $value)
        )->when(
            $filters['kmTo'] ?? false,
            fn ($query, $value) => $query->where('total_kilometers', '<=', $value)
        );
    }
}
