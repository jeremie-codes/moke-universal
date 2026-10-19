<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Setting extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'site_name',
        'owner_name',
        'job_title',
        'bio',
        'email',
        'phone',
        'address',
        'facebook',
        'instagram',
        'linkedin',
        'github',
        'youtube',
        'whatsapp',
        'copyright',
        'is_maintenance',
    ];

    protected $casts = [
        'is_maintenance' => 'boolean',
    ];

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('logo')
            ->singleFile()
            ->useDisk('public');

        $this
            ->addMediaCollection('favicon')
            ->singleFile()
            ->useDisk('public');

        $this
            ->addMediaCollection('avatar')
            ->singleFile()
            ->useDisk('public');

        $this
            ->addMediaCollection('hero')
            ->singleFile()
            ->useDisk('public');
    }
}
