<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Status extends Model {

    /**
     * Page Statuses.
     */

    const CRITICAL = -1000;
    const WARNING  = 0;
    const HEALTHY  = 1000;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['service_id', 'response', 'description'];

    /**
     * Check when last watchdog was run.
     *
     * @return Carbon
     */
    public function lastChecked()
    {
        // Stub
    }

    /**
     * Check if the service is a HTTP service.
     *
     * @return boolean
     */
    public function isHttp()
    {
        if ($this->exists) {
            return $this->service->type === 'http';
        }

        return false;
    }

    /**
     * Checks to see is the response is ok.
     *
     * @return boolean
     */
    public function isResponseOk()
    {
        if ( ! $this->exists) {
            return false;
        }

        if ($this->isHttp()) {
            return $this->response === 200;
        }

        return false;
    }

    /**
     * Get the last entry in the status table.
     *
     * @return Illuminate\Database\Query\Builder
     */
    public function scopeRecent($query, $limit = 10)
    {
        return $query->take($limit)->orderBy('created_at', 'desc');
    }

    /**
     * Return the relating service.
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}