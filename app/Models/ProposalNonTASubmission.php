<?php

namespace App\Models;

use App\Traits\Uuids;
use Laravel\Sanctum\Sanctum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;


class ProposalNonTASubmission extends Model
{
    use HasFactory, SoftDeletes, Uuids;

    protected $table = 'proposal_non_t_a_submissions';
    protected $fillable = [
        'user_id',
        'type_of_proposal',
        'phone_number',
        'educational_level',
        'study_program',
        'application_file',
        'gpu',
        'ram',
        'storage',
        'peneliti',
        'partner',
        'duration',
        'research_title',
        'short_description',
        'data_description',
        'shared_data',
        'activity_plan',
        'output_plan',
        'previous_experience',
        'research_fee',
        'docker_image',
        'proposal_file',
        'anggaran_file',
        'term_and_condition',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function revisian()
    {
        return $this->belongsTo(Revisian::class);
    }
}
