<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('password')->nullable()->change();
            $table->string('api_token', 80)->after('password')
                ->unique()
                ->nullable()
                ->default(null);
            $table->foreignId('current_connected_account_id')->nullable();
            $table->timestamp('banned_at')->nullable();
            $table->auditableWithDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('current_connected_account_id');
            $table->dropColumn('api_token');
            $table->dropColumn('banned_at');
            $table->dropAuditableWithDeletes();
        });
    }
}
