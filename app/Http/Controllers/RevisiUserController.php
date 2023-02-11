<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\ProposalIndustriSubmission;
use Illuminate\Database\QueryException;

class RevisiUserController extends Controller
{
    public function revisiUserIndustri(Request $request, $id_user)
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
            $submission = ProposalIndustriSubmission::where('id', $id_user)
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

            if ($request->term_and_condition === "agree") {
                $term_and_condition = 1;
            } else {
                $term_and_condition = 0;
            }

            ProposalIndustriSubmission::where('id', $id_user)
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
}
