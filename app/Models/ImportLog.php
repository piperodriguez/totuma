<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImportLog extends Model
{
    protected $fillable = [
        'filename',
        'status',
        'processed_count',
        'skipped_count',
        'duplicates_count',
        'error_message',
        'total_points_count',
    ];

}
