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
         Schema::create('talk_proposal_revisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('talk_proposal_id')->constrained()->onDelete('cascade');
            $table->json('changes');
            $table->foreignId('changed_by')->constrained('users');
            $table->timestamp('changed_at');
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
        Schema::dropIfExists('talk_proposal_revisions');
    }
};
