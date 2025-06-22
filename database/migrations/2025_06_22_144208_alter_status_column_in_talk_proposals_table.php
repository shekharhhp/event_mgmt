<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AlterStatusColumnInTalkProposalsTable extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE talk_proposals MODIFY COLUMN status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE talk_proposals MODIFY COLUMN status VARCHAR(255)");
    }
}
