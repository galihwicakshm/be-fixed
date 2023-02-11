<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Revisian;
use Illuminate\Http\Request;
use App\Notifications\Revisi;
use App\Notifications\AddRevisi;
use App\Helpers\ResponseFormatter;
use App\Models\ProposalSubmission;
use App\Http\Controllers\Controller;
use App\Models\ProposalNonTASubmission;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use App\Models\ProposalIndustriSubmission;
use Illuminate\Support\Facades\Notification;

class RevisiController extends Controller
{
    public function tambahRevisi(Request $request)
    {

        $validate = Validator::make($request->all(), [

            'catatan' => [
                'required',
            ]
        ]);

        if ($validate->fails()) {
            $data = [
                'validation_errors' => $validate->errors(),
            ];

            return ResponseFormatter::validation_error('Validation Errors', $data);
        }

        try {
            $revisi = Revisian::create([
                'user_id' => $request->user_id,
                'proposal_id' => $request->proposal_id,
                'catatan' => $request->catatan,
            ]);

            $data = [
                'data' => $revisi
            ];


            $data_industri = Revisian::join('proposal_industri_submissions', 'proposal_industri_submissions.id', '=', 'revisians.proposal_id')->where('proposal_industri_submissions.id', $request->proposal_id);

            $data_submission = Revisian::join('proposal_submissions', 'proposal_submissions.id', '=', 'revisians.proposal_id')->where('proposal_submissions.id', $request->proposal_id);

            $data_nonta = Revisian::join('proposal_non_t_a_submissions', 'proposal_non_t_a_submissions.id', '=', 'revisians.proposal_id')->where('proposal_non_t_a_submissions.id', $request->proposal_id);

            $data_industri->update([
                'status' => 'Revision'
            ]);

            $data_submission->update([
                'status' => 'Revision'
            ]);

            $data_nonta->update([
                'status' => 'Revision'
            ]);




            $user = User::where('id', $request->user_id)->select('email')->get();
            $userName = User::join('user_profiles', 'user_profiles.user_id', '=', 'users.id')->where('users.id', $request->user_id)->get();

            $jenisInd =
                Revisian::join('proposal_industri_submissions', 'proposal_industri_submissions.id', '=', 'revisians.proposal_id')->where('revisians.user_id', $request->user_id)->select('proposal_industri_submissions.type_of_proposal')->where('proposal_industri_submissions.id', $request->proposal_id)->get();
            $jenisSub =
                Revisian::join('proposal_submissions', 'proposal_submissions.id', '=', 'revisians.proposal_id')->where('revisians.user_id', $request->user_id)->select('proposal_submissions.type_of_proposal')->where('proposal_submissions.id', $request->proposal_id)->get();
            $jenisNonTA =
                Revisian::join('proposal_non_t_a_submissions', 'proposal_non_t_a_submissions.id', '=', 'revisians.proposal_id')->where('revisians.user_id', $request->user_id)->select('proposal_non_t_a_submissions.type_of_proposal')->where('proposal_non_t_a_submissions.id', $request->proposal_id)->get();

            $jenis = '';

            if ($jenisSub->count() > 0) {
                $jenis = $jenis . $jenisSub[0]->type_of_proposal;
            } else if ($jenisInd->count() > 0) {
                $jenis = $jenis . $jenisInd[0]->type_of_proposal;
            } else if ($jenisNonTA->count() > 0) {
                $jenis = $jenis . $jenisNonTA[0]->type_of_proposal;
            }

            $dataEmail = [
                'greeting' => 'Halo, ' . $userName[0]->first_name,
                'proposal' => 'Jenis Penelitian : ' . $jenis,
                'revisi' => 'Berikut ini adalah revisi anda :',
                'body' => $request->catatan,

            ];


            Notification::route('mail', $user)->notify(new Revisi($dataEmail));

            return ResponseFormatter::success('Success store Revisi', $data, $dataEmail);
        } catch (QueryException $error) {
            $data = [
                'error' => $error
            ];

            return ResponseFormatter::error(500, 'Query Error', $data);
        }
    }

    public function showAdmin()
    {


        $proposalRevisi = Revisian::All();

        $data = [
            'proposal_revisi' => $proposalRevisi,
        ];

        return ResponseFormatter::success('All Proposal Revisi', $data);
    }

    public function showRevisiProposalAdmin($id_proposal)
    {

        // $proposal_jenisNon  = ProposalNonTASubmission::select('type_of_proposal')->where('id', $id_proposal)->get();
        // $proposal_jenis  = ProposalSubmission::select('type_of_proposal')->where('id', $id_proposal)->get();
        // $proposal_jenisInd  = ProposalIndustriSubmission::select('type_of_proposal')->where('id', $id_proposal)->get();
        // $proposal = Revisian::where('proposal_id', $id_proposal)->select('id', 'proposal_id', 'catatan', 'created_at as tanggal_revisi')->get();
        // $email = User::join('revisians', 'revisians.user_id', 'users.id')->select('users.id AS user_id', 'users.email', 'user_profiles.first_name as nama')->join('user_profiles', 'user_profiles.user_id', '=', 'users.id')->where('proposal_id', $id_proposal)->limit(1)->get();

        $proposal = Revisian::join('user_profiles', 'user_profiles.user_id', '=', 'revisians.user_id')->select('revisians.id', 'revisians.proposal_id', 'revisians.user_id', 'user_profiles.first_name', 'revisians.catatan', 'revisians.created_at as tanggal_revisi')->where('proposal_id', $id_proposal)->with('user:id,email')->get();



        // $jenisproposal = NULL;

        // if ($proposal_jenisNon != '[]') {
        //     $jenisproposal = $proposal_jenisNon;
        // } else if ($proposal_jenis != '[]') {
        //     $jenisproposal  = $proposal_jenis;
        // } else {
        //     $jenisproposal =  $proposal_jenisInd;
        // }

        $data = [
            // 'user' => $email,
            // 'jenis_proposal' => $jenisproposal,
            'proposal_revisi' => $proposal,
        ];

        return ResponseFormatter::success('Proposal Revisi Success', $data);
    }


    public function showRevisiProposalUser($id_proposal)
    {

        // $proposal_jenisNon  = ProposalNonTASubmission::select('type_of_proposal')->where('id', $id_proposal)->get();
        // $proposal_jenis  = ProposalSubmission::select('type_of_proposal')->where('id', $id_proposal)->get();
        // $proposal_jenisInd  = ProposalIndustriSubmission::select('type_of_proposal')->where('id', $id_proposal)->get();
        // $proposal = Revisian::where('proposal_id', $id_proposal)->select('id', 'proposal_id', 'catatan', 'created_at as tanggal_revisi')->get();
        // $email = User::join('revisians', 'revisians.user_id', 'users.id')->select('users.id AS user_id', 'users.email', 'user_profiles.first_name as nama')->join('user_profiles', 'user_profiles.user_id', '=', 'users.id')->where('proposal_id', $id_proposal)->get();

        // $jenisproposal = NULL;

        // if ($proposal_jenisNon != '[]') {
        //     $jenisproposal = $proposal_jenisNon;
        // } else if ($proposal_jenis != '[]') {
        //     $jenisproposal  = $proposal_jenis;
        // } else {
        //     $jenisproposal =  $proposal_jenisInd;
        // }


        $proposal = Revisian::join('user_profiles', 'user_profiles.user_id', '=', 'revisians.user_id')->select('revisians.id', 'revisians.proposal_id', 'revisians.user_id', 'user_profiles.first_name', 'revisians.catatan', 'revisians.created_at as tanggal_revisi')->where('proposal_id', $id_proposal)->with('user:id,email')->get();

        $data = [
            // 'user' => $email,
            // 'jenis_proposal' => $jenisproposal,
            'proposal_revisi' => $proposal,
        ];


        return ResponseFormatter::success('Proposal Revisi Success', $data);
    }


    // public function fixRevisi($id_proposal)
    // {
    //     $proposal_jenisNon  = ProposalNonTASubmission::select('type_of_proposal')->where('id', $id_proposal)->get();
    //     $proposal_jenis  = ProposalSubmission::select('type_of_proposal', 'id')->where('id', $id_proposal)->get();
    //     $proposal_jenisInd  = ProposalIndustriSubmission::select('type_of_proposal')->where('id', $id_proposal)->get();
    //     $proposal = Revisian::where('proposal_id', $id_proposal)->select('id', 'proposal_id', 'catatan', 'created_at as tanggal_revisi')->get();
    //     $email = User::join('revisians', 'revisians.user_id', 'users.id')->select('users.id AS user_id', 'users.email', 'user_profiles.first_name as nama', 'revisians.proposal_id as id')->join('user_profiles', 'user_profiles.user_id', '=', 'users.id')->where('proposal_id', $id_proposal)->get();

    //     $jenisproposal = NULL;

    //     if ($proposal_jenisNon != '[]') {
    //         $jenisproposal = $proposal_jenisNon;
    //     } else if ($proposal_jenis != '[]') {
    //         $jenisproposal  = $proposal_jenis;
    //     } else {
    //         $jenisproposal =  $proposal_jenisInd;
    //     }

    //     $data = [
    //         'user' => $email,
    //         'jenis_proposal' => $jenisproposal,
    //         'proposal_revisi' => $proposal,
    //     ];

    //     return ResponseFormatter::success('Proposal Revisi Success', $data);
    // }
}
