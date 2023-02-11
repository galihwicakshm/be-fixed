<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Jobs\ApproveEmailJob;
use App\Jobs\RevisionEmailJob;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Notifications\ProposalMasuk;
use App\Models\ProposalNonTASubmission;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;

class ProposalNonTASubmissionController extends Controller
{
    // Get all proposal non TA submission
    public function store(Request $request)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'phone_number' => [
                    'required',
                    'numeric',
                ],
                'educational_level' => [
                    'required',
                ],
                'study_program' => [
                    'required',
                ],
                'application_file' => [
                    'required',
                    'file',
                    'mimes:pdf',
                ],
                'gpu' => [
                    'required',
                    'numeric',
                    'gt:0',
                ],
                'ram' => [
                    'required',
                    'numeric',
                    'gt:0',
                ],
                'storage' => [
                    'required',
                    'numeric',
                    'gt:0',
                ],
                'peneliti' => [
                    'required',
                ],
                'duration' => [
                    'required',
                    'numeric',
                    'gt:0',
                ],
                'research_title' => [
                    'required',
                ],
                'short_description' => [
                    'required',
                ],
                'data_description' => [
                    'required',
                ],
                'shared_data' => [
                    'required',
                ],
                'activity_plan' => [
                    'required',
                ],
                'output_plan' => [
                    'required',
                ],
                'research_fee' => [
                    'required',
                    'numeric',
                ],
                'proposal_file' => [
                    'required',
                    'file',
                    'mimes:pdf',
                ],
                'anggaran_file' => [
                    'required',
                    'file',
                    'mimes:pdf',
                ],
                'term_and_condition' => [
                    'required',
                ],
                'docker_image' => [
                    'required',
                    'file',
                    'mimes:zip',
                ],
            ]
        );

        if ($validate->fails()) {
            $data = [
                'validation_errors' => $validate->errors(),
            ];

            return ResponseFormatter::validation_error('Validation Errors', $data);
        }

        try {
            if ($request->hasFile('proposal_file')) {
                $file = $request->file('proposal_file');
                $extension = $file->getClientOriginalExtension();
                $newName = Str::random(40) . '.' . $extension;

                $file->storeAs('proposal', $newName, 'minio');
                $link = $newName;
            } else {
                $data = [
                    'validation_errors' => [
                        'proposal_file' => 'File tidak ditemukan.'
                    ]
                ];
                return ResponseFormatter::validation_error('Error Proposal File', $data);
            }

            if ($request->hasFile('application_file')) {
                $file = $request->file('application_file');
                $extension = $file->getClientOriginalExtension();
                $newName = Str::random(40) . '.' . $extension;

                $file->storeAs('application_dgx', $newName, 'minio');
                $linkDGX = $newName;
            } else {
                $data = [
                    'validation_errors' => [
                        'application_file' => 'File tidak ditemukan.'
                    ]
                ];
                return ResponseFormatter::validation_error('Error Proposal File', $data);
            }

            if ($request->hasFile('anggaran_file')) {
                $file = $request->file('anggaran_file');
                $extension = $file->getClientOriginalExtension();
                $newName = Str::random(40) . '.' . $extension;

                $file->storeAs('anggaran', $newName, 'minio');
                $linkAnggaran = $newName;
            } else {
                $data = [
                    'validation_errors' => [
                        'anggaran_file' => 'File tidak ditemukan.'
                    ]
                ];
                return ResponseFormatter::validation_error('Error Proposal File', $data);
            }

            if ($request->shared_data === "yes") {
                $shared_data = 1;
            } else {
                $shared_data = 0;
            }

            if ($request->term_and_condition === "agree") {
                $term_and_condition = 1;
            } else {
                $term_and_condition = 0;
            }

            $submission = ProposalNonTASubmission::create([
                'type_of_proposal' => $request->type_of_proposal,
                'user_id' => auth()->user()->id,
                'phone_number' => $request->phone_number,
                'educational_level' => $request->educational_level,
                'study_program' => $request->study_program,
                'application_file' => $linkDGX,
                'gpu' => $request->gpu,
                'ram' => $request->ram,
                'storage' => $request->storage,
                'partner' => $request->partner,
                'peneliti' => $request->peneliti,
                'duration' => $request->duration,
                'research_title' => $request->research_title,
                'short_description' => $request->short_description,
                'data_description' => $request->data_description,
                'shared_data' => $shared_data,
                'activity_plan' => $request->activity_plan,
                'output_plan' => $request->output_plan,
                'previous_experience' => $request->previous_experience,
                'docker_image' => $request->docker_image,
                'research_fee' => (int)$request->research_fee,
                'proposal_file' => $link,
                'anggaran_file' => $linkAnggaran,
                'term_and_condition' => $term_and_condition,
                'status' => 'Pending',
            ]);

            $data = [
                'submission' => $submission
            ];
            $nama = User::join('user_profiles', 'user_profiles.user_id', '=', 'users.id')->where('users.id', auth()->user()->id)->get();

            $dataSubmit = [
                'greeting' => 'Halo Admin!',
                'perkenalan' => 'Telah Masuk Proposal Baru!',
                'proposal' => 'Jenis Penelitian : Penelitian Non TA',
                'nama' => 'Nama : ' . $nama[0]->first_name,
            ];

            Notification::route('mail', 'anadabayu83@gmail.com')->notify(new ProposalMasuk($dataSubmit));
            return ResponseFormatter::success('Success Store Submission', $data);
        } catch (QueryException $error) {
            $data = [
                'error' => $error
            ];

            return ResponseFormatter::error(500, 'Query Error', $data);
        }
    }

    public function approved(Request $request, $id)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'appr_description' => [
                    'required',
                ],



            ]
        );

        if ($validate->fails()) {
            $data = [
                'validation_errors' => $validate->errors(),
            ];

            return ResponseFormatter::validation_error('Validation Errors', $data);
        }

        ProposalNonTASubmission::where('id', $id)
            ->update([
                'status' => 'Approved'
            ]);

        $proposal = ProposalNonTASubmission::where('id', $id)
            ->first();

        $user = User::where('id', $proposal->user_id)
            ->with('user_profile')
            ->first();

        $checkLastName = $user->user_profile->last_name === null ? "" : " " . $user->user_profile->last_name;

        $details = [
            "subject" => env('SUBJECT_APPROVE_PROPOSAL'),
            "body" => $request->appr_description,
            "name" => $user->user_profile->first_name . $checkLastName,
            "email" => $user->email
        ];

        dispatch(new ApproveEmailJob($details));

        return ResponseFormatter::success('Success Approved Submission');
    }

    public function rejected($id)
    {
        ProposalNonTASubmission::where('id', $id)
            ->update([
                'status' => 'Rejected'
            ]);

        $proposal = ProposalNonTASubmission::where('id', $id)
            ->first();

        // $user = User::where('id', $proposal->user_id)
        //     ->with('user_profile')
        //     ->first();

        // $checkLastName = $user->user_profile->last_name === null ? "" : " ".$user->user_profile->last_name;

        // $details = [
        //     "subject" => env('SUBJECT_APPROVE_PROPOSAL'),
        //     "body" => $request->appr_description,
        //     "name" => $user->user_profile->first_name . $checkLastName,
        //     "email" => $user->email
        // ];

        // dispatch(new ApproveEmailJob($details));

        return ResponseFormatter::success('Success Rejected Submission');
    }

    public function revision(Request $request, $id)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'rev_description' => [
                    'required',
                ],
            ]
        );

        if ($validate->fails()) {
            $data = [
                'validation_errors' => $validate->errors(),
            ];

            return ResponseFormatter::validation_error('Validation Errors', $data);
        }

        ProposalNonTASubmission::where('id', $id)
            ->update([
                'status' => 'Revision',
                'rev_description' => $request->rev_description,
            ]);

        $proposal = ProposalNonTASubmission::where('id', $id)
            ->first();

        $user = User::where('id', $proposal->user_id)
            ->with('user_profile')
            ->first();


        $checkLastName = $user->user_profile->last_name === null ? "" : " " . $user->user_profile->last_name;

        $details = [
            "subject" => env('SUBJECT_REVISION_PROPOSAL'),
            "body" => $request->rev_description,
            "name" => $user->user_profile->first_name . $checkLastName,
            "email" => $user->email
        ];


        dispatch(new RevisionEmailJob($details));

        return ResponseFormatter::success('Success Revision Submission');
    }

    public function finished($id)
    {
        ProposalNonTASubmission::where('id', $id)
            ->update([
                'status' => 'Finished'
            ]);

        return ResponseFormatter::success('Success Finished Submission');
    }

    public function showAll()
    {
        $submission = ProposalNonTASubmission::orderBy('id', 'DESC')
            ->with('user')
            ->get();

        $data = [
            'submission' => $submission
        ];

        return ResponseFormatter::success('All Submission', $data);
    }

    public function showAllUser()
    {
        $submission = ProposalNonTASubmission::where('user_id', auth()->user()->id)
            ->orderBy('id', 'DESC')
            ->with('user')
            ->get();

        $data = [
            'submission' => $submission
        ];

        return ResponseFormatter::success('All Submission', $data);
    }

    public function show($id)
    {
        $submission = ProposalNonTASubmission::where('id', $id)
            ->with('user')
            ->first();

        $data = [
            'submission' => $submission
        ];

        return ResponseFormatter::success('Submission ' . $id, $data);
    }

    public function update(Request $request, $id)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'phone_number' => [
                    'required',
                    'numeric',
                ],
                'educational_level' => [
                    'required',
                ],
                'study_program' => [
                    'required',
                ],
                'application_file' => [
                    'required',
                    'file',
                    'mimes:pdf',
                ],
                'gpu' => [
                    'required',
                    'numeric',
                    'gt:0',
                ],
                'ram' => [
                    'required',
                    'numeric',
                    'gt:0',
                ],
                'storage' => [
                    'required',
                    'numeric',
                    'gt:0',
                ],
                'peneliti' => [
                    'required',
                ],
                'partner' => [
                    'required',
                ],

                'duration' => [
                    'required',
                    'numeric',
                    'gt:0',
                ],
                'research_title' => [
                    'required',
                ],
                'short_description' => [
                    'required',
                ],
                'data_description' => [
                    'required',
                ],
                'shared_data' => [
                    'required',
                ],
                'activity_plan' => [
                    'required',
                ],
                'output_plan' => [
                    'required',
                ],
                'research_fee' => [
                    'required',
                    'numeric',
                ],
                'proposal_file' => [
                    'required',
                    'file',
                    'mimes:pdf',
                ],
                'anggaran_file' => [
                    'required',
                    'file',
                    'mimes:pdf',
                ],
            ]
        );

        if ($validate->fails()) {
            $data = [
                'validation_errors' => $validate->errors(),
            ];

            return ResponseFormatter::validation_error('Validation Errors', $data);
        }

        try {
            $submission = ProposalNonTASubmission::where('id', $id)
                ->first();

            if ($request->hasFile('application_file')) {
                $file = $request->file('application_file');
                $extension = $file->getClientOriginalExtension();
                $newName = Str::random(40) . '.' . $extension;

                $file->storeAs('application_dgx', $newName, 'minio');
                $linkDGX = $newName;
            } else {
                $linkDGX = $submission->application_file;
            }

            if ($request->hasFile('proposal_file')) {
                $file = $request->file('proposal_file');
                $extension = $file->getClientOriginalExtension();
                $newName = Str::random(40) . '.' . $extension;

                $file->storeAs('proposal', $newName, 'minio');
                $link = $newName;
            } else {
                $link = $submission->proposal_file;
            }

            if ($request->hasFile('anggaran_file')) {
                $file = $request->file('anggaran_file');
                $extension = $file->getClientOriginalExtension();
                $newName = Str::random(40) . '.' . $extension;

                $file->storeAs('anggaran', $newName, 'minio');
                $linkAnggaran = $newName;
            } else {
                $data = [
                    'validation_errors' => [
                        'anggaran_file' => 'File tidak ditemukan.'
                    ]
                ];
                return ResponseFormatter::validation_error('Error Proposal File', $data);
            }

            if ($request->shared_data === "yes") {
                $shared_data = 1;
            } else {
                $shared_data = 0;
            }



            if ($request->term_and_condition === "agree") {
                $term_and_condition = 1;
            } else {
                $term_and_condition = 0;
            }

            ProposalNonTASubmission::where('id', $id)
                ->update([
                    'type_of_proposal' => $request->type_of_proposal,
                    'phone_number' => $request->phone_number,
                    'educational_level' => $request->educational_level,
                    'study_program' => $request->study_program,
                    'application_file' => $linkDGX,
                    'gpu' => $request->gpu,
                    'ram' => $request->ram,
                    'storage' => $request->storage,
                    'partner' => $request->partner,
                    'peneliti' => $request->peneliti,
                    'duration' => $request->duration,
                    'research_title' => $request->research_title,
                    'short_description' => $request->short_description,
                    'data_description' => $request->data_description,
                    'shared_data' => $shared_data,
                    'activity_plan' => $request->activity_plan,
                    'output_plan' => $request->output_plan,
                    'previous_experience' => $request->previous_experience,
                    'docker_image' => $request->docker_image,
                    'research_fee' => $request->research_fee,
                    'proposal_file' => $link,
                    'anggaran_file' => $linkAnggaran,
                    'term_and_condition' => $term_and_condition,
                    'status' => 'Pending',
                    'rev_description' => null,
                ]);

            $data = [
                'submission' => $submission
            ];

            return ResponseFormatter::success('Success Update Submission', $data);
        } catch (QueryException $error) {
            $data = [
                'error' => $error
            ];

            return ResponseFormatter::error(500, 'Query Error', $data);
        }
    }

    public function destroy($id)
    {
        $submission = ProposalNonTASubmission::where('id', $id)->first();
        $submission->forceDelete();

        return ResponseFormatter::success('Success Delete Submission ' . $id);
    }

    public function readFile($filename)
    {
        $response = Storage::disk('minio')->response('proposal/' . $filename);

        return $response;
    }

    public function readFileApplication($filename)
    {
        $response = Storage::disk('minio')->response('application_dgx/' . $filename);

        return $response;
    }

    public function readFileAnggaran($filename)
    {
        $response = Storage::disk('minio')->response('anggaran/' . $filename);

        return $response;
    }
}
