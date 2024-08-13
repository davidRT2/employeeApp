<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use SebastianBergmann\CodeUnit\FunctionUnit;
use Illuminate\Support\Str;

class Division extends Model
{
    protected $table = 'divisions';

    protected $fillable = [
        'name'
    ];

    protected $keyType = 'string';
    public $incrementing = false;

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
        });
    }

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    /**
     * Scope a query to search by name.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $name
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearchByName(Builder $query, $name)
    {
        return $query->where('name', 'like', "%{$name}%");
    }

    public function employee()
    {
        return $this->hasMany(Employee::class, 'division_id');
    }
}
