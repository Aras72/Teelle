<?php

namespace App\Models;

use App\Models\Concerns\HasPublicUlid;
use Illuminate\Database\Eloquent\Model;

class MediaAsset extends Model
{
    use HasPublicUlid;

    protected $fillable = ['uploaded_by', 'disk', 'path', 'original_name', 'mime', 'width', 'height', 'checksum', 'status', 'alt_text'];
}
