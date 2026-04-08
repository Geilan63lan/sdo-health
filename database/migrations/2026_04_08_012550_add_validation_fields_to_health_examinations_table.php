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
        Schema::table('health_examinations', function (Blueprint $table) {
            $table->boolean('validated')->default(false)->after('others_specify');
            $table->timestamp('validated_at')->nullable()->after('validated');
            $table->foreignId('validated_by')->nullable()->constrained('users')->after('validated_at');
            $table->timestamp('reverted_at')->nullable()->after('validated_by');
            $table->foreignId('reverted_by')->nullable()->constrained('users')->after('reverted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('health_examinations', function (Blueprint $table) {
            $table->dropForeign(['validated_by']);
            $table->dropForeign(['reverted_by']);
            $table->dropColumn(['validated', 'validated_at', 'validated_by', 'reverted_at', 'reverted_by']);
        });
    }
};
