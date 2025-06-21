<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('talk_proposals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('speaker_id')->constrained('users')->index();
            $table->string('title');
            $table->text('description');
            $table->string('presentation_pdf')->nullable();
            $table->enum('status', ['submitted', 'under_review', 'accepted', 'rejected'])->default('submitted')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('talk_proposals');
    }
};
