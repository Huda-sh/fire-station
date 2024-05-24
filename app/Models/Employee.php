<?php

namespace App\Models;

use App\Enums\EmployeeLevel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static whereIn(string $string, array $array)
 */
class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'level',
        'call_id',
    ];

    protected $casts = [
        'level' => EmployeeLevel::class
    ];

    public function call(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Call::class);
    }
}
