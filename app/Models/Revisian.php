<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuids;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\Sanctum;

class Revisian extends Model
{
    use HasApiTokens, HasFactory, SoftDeletes, Uuids;
    protected $fillable = [
        'catatan',
        'proposal_id',
        'user_id',
    ];



    public function tokens()
    {
        return $this->morphMany(Sanctum::$personalAccessTokenModel, 'tokenable', "tokenable_type", "tokenable_id");
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function proposalnonta()
    {
        return $this->belongsTo(ProposalNonTASubmission::class);
    }
}
