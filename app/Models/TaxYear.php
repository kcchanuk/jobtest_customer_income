<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaxYear extends Model
{
    use SoftDeletes;

    protected $fillable = ['start_year'];

    // Relationships
    public function incomes()
    {
        return $this->hasMany(Income::class);
    }

    // Attributes
    public function getTaxYearStringAttribute()
    {
        return $this->start_year . '/' . $this->end_year;
    }

    public function getEndYearAttribute()
    {
        return $this->start_year + 1;
    }

    public function getStartDateAttribute()
    {
        return $this->start_year . '-04-06';
    }

    public function getEndDateAttribute()
    {
        return $this->end_year . '-04-05';
    }
}
