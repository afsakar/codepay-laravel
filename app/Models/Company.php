<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class Company extends Model
{
    use HasFactory;

    const STATUS = [
        'active' => 'Active',
        'inactive' => 'Inactive',
    ];

    const IMAGE_PATH = 'company-logos/';

    protected $fillable = [
        'name',
        'owner',
        'tc_number',
        'tel_number',
        'gsm_number',
        'fax_number',
        'email',
        'address',
        'tax_office',
        'tax_number',
        'status',
        'logo',
    ];

    public function getStatusColorAttribute()
    {
        return [
            'active' => 'green',
            'inactive' => 'red',
        ][$this->status];
    }

    public function getCompanyLogoAttribute()
    {
        return $this->logo != "" || $this->logo != null ? Storage::url($this->logo) : defaultImage();
    }

    public static function boot() {
        parent::boot();

        static::deleting(function($company) {
            File::delete($company->logo);
        });

        static::updated(function($company) {
            if ($company->logo != null) {
                File::delete($company->logo);
            }
        });
    }
}
