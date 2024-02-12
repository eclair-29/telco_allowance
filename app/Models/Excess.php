<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Excess extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function assignee()
    {
        return $this->belongsTo(Assignee::class);
    }

    public function series()
    {
        return $this->belongsTo(Series::class);
    }
}
