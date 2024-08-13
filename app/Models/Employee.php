<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Employee extends Model
{
    protected $table = 'employees';

    protected $fillable = [
        'name',
        'phone',
        'division_id',
        'position',
        'image'
    ];


    public $incrementing = false;
    protected $keyType = 'string';

    public static function boot()
    {
        parent::boot();

        static::creating(function ($employee) {
            $employee->id = Str::uuid();
        });
    }


    protected $hidden = [
        'created_at',
        'updated_at',
    ];


    public function division()
    {
        return $this->belongsTo(Division::class, 'division_id');
    }
}
