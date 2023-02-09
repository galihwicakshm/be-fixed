<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProposalNontaSubmissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proposal_non_t_a_submissions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->string('type_of_proposal');
            $table->string('phone_number');
            $table->string('educational_level');
            $table->string('study_program');
            $table->string('application_file');
            $table->string('gpu');
            $table->string('ram');
            $table->string('storage');
            $table->string('peneliti');
            $table->string('partner');
            $table->string('duration');
            $table->string('research_title');
            $table->text('short_description');
            $table->text('data_description');
            $table->boolean('shared_data');
            $table->text('activity_plan');
            $table->text('output_plan');
            $table->text('previous_experience')->nullable();
            $table->integer('research_fee')->nullable();
            $table->string('docker_image');
            $table->string('proposal_file');
            $table->string('anggaran_file');
            $table->boolean('term_and_condition');
            $table->string('status');
            $table->text('rev_description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('proposal_nonta_submissions');
    }
}
