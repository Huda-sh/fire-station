<?php

namespace App\Models;

use App\Enums\CallPriority;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Call extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'priority',
        'duration',
    ];

    protected $casts = [
        'priority' => CallPriority::class
    ];

    public function employee(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Employee::class);
    }
}
