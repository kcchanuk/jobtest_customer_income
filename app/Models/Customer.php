<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'email', 'utr', 'dob', 'phone', 'profile_pic_filename'];

    protected $visible = ['id', 'name', 'email', 'utr', 'dob', 'phone', 'profile_pic_url'];

    protected $appends = ['profile_pic_url'];

    // Relationships
    public function incomes()
    {
        return $this->hasMany(Income::class);
    }

    // Attributes
    public function getProfilePicUrlAttribute()
    {
        if (!empty($this->profile_pic_filename)) {
            // A symbolic link "public/storage" which points to the "storage/app/public" directory
            // is assumed to be created already
            // Storage path convention is "customers/{customer_id}/{profile_pic_filename}
            // e.g. "customers/8/filename.jpg"
            return Storage::url('customers/' . $this->id . '/' . $this->profile_pic_filename);
        } else {
            return null;
        }
    }

    // Non-static Functions
    public function storeProfilePic(UploadedFile $file)
    {
        // Get random profile pic filename
        $filename = static::getRandomProfilePicFilename($file->extension());

        // Store the profile pic file
        // It is standardized to store under folder named after the customer ID
        $file->storeAs('public/customers/' . $this->id, $filename);

        // save the profile pic filename
        $this->profile_pic_filename = $filename;
        $this->save();

        return true;
    }

    // Static Functions
    public static function getRandomProfilePicFilename(string $extension)
    {
        do {
            $filename = Str::random(40) . '.' . $extension;
        } while (static::where('profile_pic_filename', $filename)->exists());

        return $filename;
    }
}
