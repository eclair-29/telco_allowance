<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignee extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function loan()
    {
        return $this->hasOne(Loan::class);
    }

    public function excesses()
    {
        return $this->hasMany(Excess::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }
}
