<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pipeline\Pipeline;

class Brands extends Model
{
    use HasFactory;
    use \Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    protected $hidden = [
        'id'
    ];

    protected $fillable = [
        'uuid',
        'title',
        'slug',
    ];

    public function products()
    {
        return $this->hasMany(Products::class, 'metadata->brand', 'uuid');
    }

    public function getAllBrands($relationships = [])
    {
        return app(Pipeline::class)
            ->send($this->newQuery()->with($relationships))
            ->through([
                \App\QueryFilters\Page::class,
                \App\QueryFilters\Sort::class,
            ])
            ->thenReturn()
            ->paginate(\request()->has('limit') ? \request()->get('limit') : env('PAGINATION_LIMIT', 10));
    }

    public function getBrandByUuid($uuid, $relationships = [])
    {
        return $this->newQuery()->with($relationships)->where('uuid', '=', $uuid)->firstOrFail();
    }

    public function createBrand($data)
    {
        return $this->newQuery()->create($data);
    }

    public function updateBrandByUuid($uuid, $data)
    {
        $brandDetails = $this->newQuery()->where('uuid', '=', $uuid)->firstOrFail();
        return $this->newQuery()->find($brandDetails->id)->updateOrFail($data);
    }

    public function deleteBrandByUuid($uuid)
    {
        if ($this->newQuery()->where('uuid', '=', $uuid)->exists()) {
            return $this->newQuery()->where('uuid', '=', $uuid)->delete();
        }

        throw new ModelNotFoundException();
    }
}
