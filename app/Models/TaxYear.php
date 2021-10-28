<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaxYear extends Model
{
    use SoftDeletes;

    protected $fillable = ['start_year'];

    // Relationships

    /**
     * One-to-many relationship with Incomes
     *
     * @return HasMany
     */
    public function incomes(): HasMany
    {
        return $this->hasMany(Income::class);
    }

    // Attributes

    /**
     * Return tax year string e.g. 2025/26
     *
     * @return Attribute
     */
    protected function taxYearString(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->start_year . '/' . $this->end_year
        );
    }

    /**
     * Return the end year of the tax year
     *
     * @return Attribute
     */
    protected function endYear(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->start_year + 1
        );
    }

    /**
     * Return start date of the tax year
     *
     * @return Attribute
     */
    protected function startDate(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->start_year . '-04-06'
        );
    }

    /**
     * Return end date of the tax year
     *
     * @return Attribute
     */
    protected function endDate(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->end_year . '-04-05'
        );
    }
}
