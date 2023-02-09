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
use App\Notifications\SubmitProposal;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\ProposalIndustriSubmission;
use Illuminate\Support\Facades\Notification;

class ProposalIndustriSubmissionController extends Controller
{
    public function store(Request $request)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'phone_number' => [
                    'required',
                    'numeric',
                ],
                'admin_name' => [
                    'required',
                ],
                'position' => [
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
                'leader_name' => [
                    'required',
                ],
                'pic' => [
                    'required',
                ],
                'institution' => [
                    'required',
                ],
                'duration' => [
                    'required',
                    'numeric',
                    'gt:0',
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
                'collaboration_plan' => [
                    'required',
                ],
                'research_fee' => [
                    'required',
                    'numeric',
                ],
                'docker_image' => [
                    'required',
                    'file',
                    'mimes:zip',
                ],
                'collaboration_file' => [
                    'required',
                    'file',
                    'mimes:pdf',
                ],
                'adhoc_file' => [
                    'required',
                    'file',
                    'mimes:pdf',
                ],
                'institution_file' => [
                    'required',
                    'file',
                    'mimes:pdf',
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

            ]
        );

        if ($validate->fails()) {
            $data = [
                'validation_errors' => $validate->errors(),
            ];

            return ResponseFormatter::validation_error('Validation Errors', $data);
        }

        try {
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

            if ($request->hasFile('docker_image')) {
                $file = $request->file('docker_image');
                $extension = $file->getClientOriginalExtension();
                $newName = Str::random(40) . '.' . $extension;

                $file->storeAs('docker', $newName, 'minio');
                $linkDocker = $newName;
            } else {
                $data = [
                    'validation_errors' => [
                        'docker_image' => 'File tidak ditemukan.'
                    ]
                ];
                return ResponseFormatter::validation_error('Error Docker Image', $data);
            }

            if ($request->hasFile('collaboration_file')) {
                $file = $request->file('collaboration_file');
                $extension = $file->getClientOriginalExtension();
                $newName = Str::random(40) . '.' . $extension;

                $file->storeAs('collaboration', $newName, 'minio');
                $linkCollaboration = $newName;
            } else {
                $data = [
                    'validation_errors' => [
                        'collaboration_file' => 'File tidak ditemukan.'
                    ]
                ];
                return ResponseFormatter::validation_error('Error Collaboration File', $data);
            }

            if ($request->hasFile('adhoc_file')) {
                $file = $request->file('adhoc_file');
                $extension = $file->getClientOriginalExtension();
                $newName = Str::random(40) . '.' . $extension;

                $file->storeAs('adhoc', $newName, 'minio');
                $linkAdhoc = $newName;
            } else {
                $data = [
                    'validation_errors' => [
                        'adhoc_file' => 'File tidak ditemukan.'
                    ]
                ];
                return ResponseFormatter::validation_error('Error Adhoc File', $data);
            }

            if ($request->hasFile('institution_file')) {
                $file = $request->file('institution_file');
                $extension = $file->getClientOriginalExtension();
                $newName = Str::random(40) . '.' . $extension;

                $file->storeAs('institution', $newName, 'minio');
                $linkInstitution = $newName;
            } else {
                $data = [
                    'validation_errors' => [
                        'institution_file' => 'File tidak ditemukan.'
                    ]
                ];
                return ResponseFormatter::validation_error('Error Institution File', $data);
            }

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

            $submission = ProposalIndustriSubmission::create([
                'type_of_proposal' => $request->type_of_proposal,
                'user_id' => auth()->user()->id,
                'phone_number' => $request->phone_number,
                'admin_name' => $request->admin_name,
                'position' => $request->position,
                'application_file' => $linkDGX,
                'gpu' => $request->gpu,
                'ram' => $request->ram,
                'storage' => $request->storage,
                'leader_name' => $request->leader_name,
                'pic' => $request->pic,
                'institution' => $request->institution,
                'duration' => $request->duration,
                'data_description' => $request->data_description,
                'shared_data' => $shared_data,
                'activity_plan' => $request->activity_plan,
                'collaboration_plan' => $request->collaboration_plan,
                'research_fee' => (int)$request->research_fee,
                'docker_image' => $request->docker_image,
                'collaboration_file' => $linkCollaboration,
                'adhoc_file' => $linkAdhoc,
                'institution_file' => $linkInstitution,
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
                'proposal' => 'Jenis Penelitian : Penelitian Industri',
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

        ProposalIndustriSubmission::where('id', $id)
            ->update([
                'status' => 'Approved'
            ]);

        $proposal = ProposalIndustriSubmission::where('id', $id)
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
        ProposalIndustriSubmission::where('id', $id)
            ->update([
                'status' => 'Rejected'
            ]);

        $proposal = ProposalIndustriSubmission::where('id', $id)
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

        ProposalIndustriSubmission::where('id', $id)
            ->update([
                'status' => 'Revision',
                'rev_description' => $request->rev_description,
            ]);

        $proposal = ProposalIndustriSubmission::where('id', $id)
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
        ProposalIndustriSubmission::where('id', $id)
            ->update([
                'status' => 'Finished'
            ]);

        return ResponseFormatter::success('Success Finished Submission');
    }

    public function showAll()
    {
        $submission = ProposalIndustriSubmission::orderBy('id', 'DESC')
            ->with('user')
            ->get();

        $data = [
            'submission' => $submission
        ];

        return ResponseFormatter::success('All Submission', $data);
    }

    public function showAllUser()
    {
        $submission = ProposalIndustriSubmission::where('user_id', auth()->user()->id)
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
        $submission = ProposalIndustriSubmission::where('id', $id)
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
                'admin_name' => [
                    'required',
                ],
                'position' => [
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
                'leader_name' => [
                    'required',
                ],
                'pic' => [
                    'required',
                ],
                'institution' => [
                    'required',
                ],
                'duration' => [
                    'required',
                    'numeric',
                    'gt:0',
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
                'collaboration_plan' => [
                    'required',
                ],
                'research_fee' => [
                    'required',
                    'numeric',
                ],
                'docker_image' => [
                    'required',
                    'file',
                    'mimes:zip',
                ],
                'collaboration_file' => [
                    'required',
                    'file',
                    'mimes:pdf',
                ],
                'adhoc_file' => [
                    'required',
                    'file',
                    'mimes:pdf',
                ],
                'institution_file' => [
                    'required',
                    'file',
                    'mimes:pdf',
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
            $submission = ProposalIndustriSubmission::where('id', $id)
                ->first();

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

            if ($request->hasFile('docker_image')) {
                $file = $request->file('docker_image');
                $extension = $file->getClientOriginalExtension();
                $newName = Str::random(40) . '.' . $extension;

                $file->storeAs('docker', $newName, 'minio');
                $linkDocker = $newName;
            } else {
                $data = [
                    'validation_errors' => [
                        'docker_image' => 'File tidak ditemukan.'
                    ]
                ];
                return ResponseFormatter::validation_error('Error Docker Image', $data);
            }

            if ($request->hasFile('collaboration_file')) {
                $file = $request->file('collaboration_file');
                $extension = $file->getClientOriginalExtension();
                $newName = Str::random(40) . '.' . $extension;

                $file->storeAs('collaboration', $newName, 'minio');
                $linkCollaboration = $newName;
            } else {
                $data = [
                    'validation_errors' => [
                        'collaboration_file' => 'File tidak ditemukan.'
                    ]
                ];
                return ResponseFormatter::validation_error('Error Collaboration File', $data);
            }

            if ($request->hasFile('adhoc_file')) {
                $file = $request->file('adhoc_file');
                $extension = $file->getClientOriginalExtension();
                $newName = Str::random(40) . '.' . $extension;

                $file->storeAs('adhoc', $newName, 'minio');
                $linkAdhoc = $newName;
            } else {
                $data = [
                    'validation_errors' => [
                        'adhoc_file' => 'File tidak ditemukan.'
                    ]
                ];
                return ResponseFormatter::validation_error('Error Adhoc File', $data);
            }

            if ($request->hasFile('institution_file')) {
                $file = $request->file('institution_file');
                $extension = $file->getClientOriginalExtension();
                $newName = Str::random(40) . '.' . $extension;

                $file->storeAs('institution', $newName, 'minio');
                $linkInstitution = $newName;
            } else {
                $data = [
                    'validation_errors' => [
                        'institution_file' => 'File tidak ditemukan.'
                    ]
                ];
                return ResponseFormatter::validation_error('Error Institution File', $data);
            }

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

            ProposalIndustriSubmission::where('id', $id)
                ->update([
                    'type_of_proposal' => $request->type_of_proposal,
                    'user_id' => auth()->user()->id,
                    'phone_number' => $request->phone_number,
                    'admin_name' => $request->admin_name,
                    'position' => $request->position,
                    'application_file' => $linkDGX,
                    'gpu' => $request->gpu,
                    'ram' => $request->ram,
                    'storage' => $request->storage,
                    'leader_name' => $request->leader_name,
                    'pic' => $request->pic,
                    'duration' => $request->duration,
                    'data_description' => $request->data_description,
                    'shared_data' => $shared_data,
                    'activity_plan' => $request->activity_plan,
                    'collaboration_plan' => $request->collaboration_plan,
                    'research_fee' => (int)$request->research_fee,
                    'docker_image' => $request->docker_image,
                    'collaboration_file' => $linkCollaboration,
                    'adhoc_file' => $linkAdhoc,
                    'institution_file' => $linkInstitution,
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
        $submission = ProposalIndustriSubmission::where('id', $id)->first();
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

    // read collaboration file
    public function readCollaborationFile($filename)
    {
        $response = Storage::disk('minio')->response('collaboration/' . $filename);

        return $response;
    }

    // read adhoc file
    public function readAdhocFile($filename)
    {
        $response = Storage::disk('minio')->response('adhoc/' . $filename);

        return $response;
    }

    // read institutional file
    public function readInstitutionalFile($filename)
    {
        $response = Storage::disk('minio')->response('institutional/' . $filename);

        return $response;
    }
}
