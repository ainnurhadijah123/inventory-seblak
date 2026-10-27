<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
    Schema::table('purchases', function (Blueprint $table) {
        $table->integer('total_cost')->default(0); // Sesuai dengan controller Anda
    });
    }

public function down()
    {
    Schema::table('purchases', function (Blueprint $table) {
        $table->dropColumn('total_cost');
    });
    }
};
