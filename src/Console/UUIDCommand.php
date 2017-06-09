<?php

namespace Laravel\Passport\Console;

use Laravel\Passport\Client;
use Laravel\Passport\Passport;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Ramsey\Uuid\Uuid as UUID;
use MysqlUuid\Uuid as MysqlUuid;

class UUIDCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'passport:uuid';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate version 4 UUIDs for existing Passport clients';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->comment('Checking for `uuid` column in `oauth_clients`...');

        if (!Schema::hasColumn('oauth_clients', 'uuid')) {
            Schema::table('oauth_clients', function(Blueprint $table) {
                $table->char('uuid', 32)->unique()->after('name')->nullable();
            });

            $this->line('✓ Created new column `uuid` in `oauth_clients`.');
        } else {
            $this->line('✓ OK.');
        }

        $this->comment('Generating reordered UUID v1 for Passport clients...');

        $clients = Client::where('uuid', null)->get();

        foreach($clients as $client) {
            $reordered = new MysqlUuid(UUID::uuid1()->toString());
            $client->uuid = str_replace('-', '', $reordered->toFormat(new ReorderedString()));
            $client->save();
        }

        $this->line('✓ Done.');
    }
}
