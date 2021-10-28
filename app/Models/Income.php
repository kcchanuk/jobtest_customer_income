<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function tax_year()
    {
        return $this->belongsTo(TaxYear::class);
    }

    // Attributes
    public function getIncomeFileUrlAttribute()
    {
        if (!empty($this->income_filename)) {
            // A symbolic link "public/storage" which points to the "storage/app/public" directory
            // is assumed to be created already
            // Storage path convention is "customers/{customer_id}/incomes/{profile_pic_filename}
            // e.g. "customers/8/incomes/filename.jpg"
            return Storage::url('customers/' . $this->id . '/incomes/' . $this->income_filename);
        } else {
            return null;
        }
    }

    public function getTaxYearStringAttribute()
    {
        return $this->tax_year->tax_year_string;
    }

    // Non-static Functions
    public function storeIncomeFile(UploadedFile $file)
    {
        // Get random income filename
        $filename = static::getRandomIncomeFilename($file->extension());

        // Store the income file
        // It is standardized to store in "incomes" folder under the folder named after the customer ID
        $file->storeAs('public/customers/' . $this->id . '/incomes', $filename);

        // save the income filename
        $this->income_filename = $filename;
        $this->save();

        return true;
    }

    // Static Functions
    public static function getRandomIncomeFilename(string $extension)
    {
        do {
            $filename = Str::random(40) . '.' . $extension;
        } while (static::where('income_filename', $filename)->exists());

        return $filename;
    }
}
