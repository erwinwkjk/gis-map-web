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
    if (!Schema::hasColumn('maps', 'marker_color')) {
        Schema::table('maps', function (Blueprint $table) {
            $table->string('marker_color')->nullable();
        });
    }
}

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('maps', function (Blueprint $table) {
            $table->dropColumn('marker_color');
        });
    }
    
};
