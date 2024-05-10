<?php

use App\Models\Topic;
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
        Schema::create('topics', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique( );
            $table->text('description');
            $table->timestamps();
        });

        Schema::table('posts', function (Blueprint $table){
            $table->foreignIdFor(Topic::class)->nullable()->after('user_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table){
            $table->dropConstrainedForeignIdFor(Topic::class);
        });
        Schema::dropIfExists('topics');
    }
};
