<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('restorations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("user_id");
            $table->biginteger("lending_id");
            $table->dateTime("date_time");
            $table->integer("total_good_stuff");
            $table->integer("total_defec_stuff");
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restorations');
    }
};
