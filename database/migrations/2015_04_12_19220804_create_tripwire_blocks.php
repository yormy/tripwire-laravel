<?php

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        $tablename = config('tripwire.database_tables.tripwire_blocks');
        Schema::create($tablename, function (Blueprint $table) {
            $table->id();
            $table->string('xid')->unique(); // customizable ?
            $table->boolean('ignore')->default(false);
            $table->string('type')->default('HACK-ATTEMPT');
            $table->json('reasons')->nullable(); // ie 404, xss, swear, manual

            $table->string('blocked_ip', 30);
            $table->unsignedBigInteger('blocked_user_id')->nullable();
            $table->string('blocked_user_type')->nullable();
            $table->string('blocked_browser_fingerprint')->nullable();
            $table->integer('blocked_repeater')->nullable();

            $table->string('internal_comments')->nullable();

            $table->string('response_message')->nullable();

            $table->boolean('manually_blocked')->default(false);
            $table->boolean('persistent_block')->default(false)->comment('Keep this block even during cleanups');

            $table->dateTime('blocked_until')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->json('response_json')->nullable()->comment('The json that is returned on a block');
            $table->string('response_html')->nullable()->comment('The view that is rendered on a block');

            $table->timestamps();
            $table->softDeletes();

            $table->index('blocked_ip');
            $table->index('blocked_browser_fingerprint');
            $table->index(['blocked_user_type', 'blocked_user_id']);
            $table->index('blocked_until');
        });
    }
};
