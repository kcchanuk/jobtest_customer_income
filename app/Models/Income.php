<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Income extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['description', 'amount', 'income_date', 'income_filename'];

    protected $visible = ['id', 'description', 'amount', 'income_date', 'tax_year_string', 'income_file_url'];

    protected $appends = ['tax_year_string', 'income_file_url'];

    // Relationships

    /**
     * Many-to-one relationship with Customer
     *
     * @return BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Many-to-one relationship with Tax year
     *
     * @return BelongsTo
     */
    public function tax_year(): BelongsTo
    {
        return $this->belongsTo(TaxYear::class);
    }

    // Attributes

    /**
     * Get income file URL or return null
     *
     * @return Attribute
     */
    protected function incomeFileUrl(): Attribute
    {
        return Attribute::make(
            get: fn() => !empty($this->income_filename) ?
                // A symbolic link "public/storage" which points to the "storage/app/public" directory
                // is assumed to be created already
                // Storage path convention is "customers/{customer_id}/incomes/{profile_pic_filename}
                // e.g. "customers/8/incomes/filename.jpg"
                Storage::url('customers/' . $this->customer_id . '/incomes/' . $this->income_filename) : null
        );
    }

    /**
     * Return tax year string e.g. 2025/26
     *
     * @return Attribute
     */
    protected function taxYearString(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->tax_year->tax_year_string
        );
    }

    // Non-static Functions

    /**
     * Store income file
     *
     * @param UploadedFile $file
     * @return true
     */
    public function storeIncomeFile(UploadedFile $file): bool
    {
        // Get random income filename
        $filename = static::getRandomIncomeFilename($file->extension());

        // Store the income file
        // It is standardized to store in "incomes" folder under the folder named after the customer ID
        $file->storeAs('public/customers/' . $this->customer_id . '/incomes', $filename);

        // save the income filename
        $this->income_filename = $filename;
        $this->save();

        return true;
    }

    // Static Functions

    /**
     * Generate random income filename
     *
     * @param string $extension
     * @return string
     */
    public static function getRandomIncomeFilename(string $extension)
    {
        do {
            $filename = Str::random(40) . '.' . $extension;
        } while (static::where('income_filename', $filename)->exists());

        return $filename;
    }
}
