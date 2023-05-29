<?php
// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tripwire_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('xid')->unique(); // customizable ?
            $table->string('ip')->nullable(); // ??
            $table->string('level')->default('medium');
            $table->string('middleware')->nullable(); // ??
            $table->integer('user_id')->nullable();
            $table->string('user_type')->nullable();
            $table->string('url')->nullable();
            $table->string('referrer')->nullable();
            $table->text('request')->nullable();
            $table->text('user_agent')->nullable();
            $table->text('robot_crawler')->nullable();
            $table->text('browser_fingerprint')->nullable();
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
