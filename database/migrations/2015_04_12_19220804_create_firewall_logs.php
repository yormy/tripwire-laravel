<?php
// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Yormy\TripwireLaravel\Models\TripwireLog;

return new class extends Migration
{

    public function up()
    {
        Schema::create((new TripwireLog())->getTable(), function (Blueprint $table) {
            $table->increments('id');
            $table->string('xid')->unique(); // customizable ?
            $table->string('event_code');
            $table->integer('event_score');
            $table->string('event_violation')->nullalbe();
            $table->string('event_comment')->nullable();
            $table->string('ip')->nullable(); // ??
            $table->json('ips')->nullable();
            $table->string('level')->default('medium');
            $table->string('middleware')->nullable(); // ??
            $table->integer('user_id')->nullable();
            $table->string('user_type')->nullable();
            $table->string('url')->nullable();
            $table->string('method')->nullable();
            $table->string('referer')->nullable();
            $table->json('header')->nullable();
            $table->json('request')->nullable();
            $table->text('user_agent')->nullable();
            $table->text('robot_crawler')->nullable();
            $table->text('browser_fingerprint')->nullable();
            $table->text('request_fingerprint')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('ip');
        });
    }

    public function down()
    {
        Schema::drop('firewall_logs');
    }
};
