<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SyncState extends Model
{
    protected $table = 'sync_states'; // matches your DB table

    protected $fillable = [
        'context',
        'last_synced_at',
    ];
}
