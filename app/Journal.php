<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Cycle;
use App\JournalDetail;
use App\Taxpayer;
use App\JournalProduction;
use App\JournalTransaction;
use App\JournalAccountMovement;
use App\JournalSim;

class Journal extends Model
{


    /**
     * Get the details for the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function details()
    {
        return $this->hasMany(JournalDetail::class);
    }

    /**
     * Get the journalSimulations for the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function journalSimulations()
    {
        return $this->hasMany(JournalSim::class);
    }

    /**
     * Get the cycle that owns the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cycle()
    {
        return $this->belongsTo(Cycle::class);
    }

    /**
     * Get the taxPayer that owns the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function taxPayer()
    {
        return $this->belongsTo(Taxpayer::class);
    }

    /**
     * Get the productions for the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productions()
    {
        return $this->hasMany(JournalProduction::class);
    }

    /**
     * Get the transactions for the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions()
    {
        return $this->hasMany(JournalTransaction::class);
    }

    /**
     * Get the accountMovements for the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function accountMovements()
    {
        return $this->hasMany(JournalAccountMovement::class);
    }
}