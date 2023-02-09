<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProposalIndustriSubmission extends Model
{
    use HasFactory, SoftDeletes, Uuids;

    protected $fillable = [
        'user_id',
        'type_of_proposal',
        'phone_number',
        'admin_name',
        'position',
        'application_file',
        'gpu',
        'ram',
        'storage',
        'leader_name',
        'pic',
        'institution',
        'duration',
        'data_description',
        'shared_data',
        'activity_plan',
        'collaboration_plan',
        'research_fee',
        'docker_image',
        'collaboration_file',
        'adhoc_file',
        'institution_file',
        'proposal_file',
        'anggaran_file',
        'term_and_condition',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
