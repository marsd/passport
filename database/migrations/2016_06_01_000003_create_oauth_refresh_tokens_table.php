<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOauthRefreshTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oauth_refresh_tokens', function (Blueprint $table) {
            $table->string('id', 100)->primary();
            $table->char('access_token_id', 100);
            $table->boolean('revoked');
            $table->dateTime('expires_at')->nullable();
        });
        
        DB::statement('ALTER TABLE `oauth_refresh_tokens` ROW_FORMAT=COMPRESSED;');
        Schema::table('oauth_refresh_tokens', function ($table) {
            $table->index('access_token_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('oauth_refresh_tokens');
    }
}
