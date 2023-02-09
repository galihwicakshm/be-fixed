<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProposalSubmission extends Model
{
    use HasFactory, SoftDeletes, Uuids;

    protected $fillable = [
        'user_id',
        'type_of_proposal',
        'phone_number',
        'educational_level',
        'application_file',
        'study_program',
        'gpu',
        'ram',
        'storage',
        'partner',
        'duration',
        'research_field',
        'short_description',
        'data_description',
        'shared_data',
        'activity_plan',
        'output_plan',
        'previous_experience',
        'docker_image',
        'research_fee',
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
