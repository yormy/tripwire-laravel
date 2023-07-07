<?php

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        $tablename = config('tripwire.database_tables.tripwire_logs');
        Schema::create($tablename, function (Blueprint $table) {
            $table->increments('id');
            $table->string('xid')->unique(); // customizable ?
            $table->boolean('ignore')->default(false);

            $table->string('event_code');
            $table->integer('event_score');
            $table->string('event_violation')->nullalbe();
            $table->string('event_comment')->nullable();

            $table->string('ip')->nullable();   // need place for encrypted values
            $table->json('ips')->nullable();    // need place for encrypted values
            $table->string('level')->default('medium');
            $table->integer('user_id')->nullable();
            $table->string('user_type')->nullable();

            $table->string('url')->nullable();
            $table->string('method')->nullable();
            $table->string('referer')->nullable();
            $table->text('header', 500)->nullable();

            $table->text('request', 500)->nullable();
            $table->text('trigger_data', 500)->nullable();
            $table->string('trigger_rule')->nullable();
            $table->text('user_agent')->nullable();

            $table->text('robot_crawler')->nullable();

            $table->string('browser_fingerprint')->nullable();
            $table->string('request_fingerprint')->nullable();
            $table->unsignedBigInteger('tripwire_block_id')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('ip');
            $table->index('browser_fingerprint');
            $table->index(['user_type', 'user_id']);
        });
    }
};
