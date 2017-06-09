<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOauthPersonalAccessClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oauth_personal_access_clients', function (Blueprint $table) {
            $table->increments('id');
            $table->char('client_id', 32);
            $table->timestamps();
        });
        
        DB::statement('ALTER TABLE `oauth_personal_access_clients` ROW_FORMAT=COMPRESSED;');
        Schema::table('oauth_personal_access_clients', function ($table) {
            $table->index('client_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('oauth_personal_access_clients');
    }
}
