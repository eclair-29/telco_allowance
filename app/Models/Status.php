<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    public function plans()
    {
        return $this->hasMany(Plan::class);
    }

    public function assignees()
    {
        return $this->hasMany(Assignee::class);
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
