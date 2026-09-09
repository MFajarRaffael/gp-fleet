<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('dokumens', function (Blueprint $table) {
            $table->timestamp('notif_h30_sent_at')->nullable()->after('file');
            $table->timestamp('notif_h15_sent_at')->nullable()->after('notif_h30_sent_at');
            $table->timestamp('notif_h5_sent_at')->nullable()->after('notif_h15_sent_at');
            $table->timestamp('notif_h4_sent_at')->nullable()->after('notif_h5_sent_at');
            $table->timestamp('notif_h3_sent_at')->nullable()->after('notif_h4_sent_at');
            $table->timestamp('notif_h2_sent_at')->nullable()->after('notif_h3_sent_at');
            $table->timestamp('notif_h1_sent_at')->nullable()->after('notif_h2_sent_at');
            $table->timestamp('notif_h0_sent_at')->nullable()->after('notif_h1_sent_at');
        });
    }

    public function down(): void
    {
        Schema::table('dokumens', function (Blueprint $table) {
            $table->dropColumn([
                'notif_h30_sent_at',
                'notif_h15_sent_at',
                'notif_h5_sent_at',
                'notif_h4_sent_at',
                'notif_h3_sent_at',
                'notif_h2_sent_at',
                'notif_h1_sent_at',
                'notif_h0_sent_at',
            ]);
        });
    }
};