<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Jejak perubahan nilai. Diisi otomatis oleh trigger pada tabel grades. */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grade_logs', function (Blueprint $table) {
            $table->id('log_id');

            // Tanpa foreign key: log harus tetap ada walau nilainya sudah dihapus
            $table->unsignedBigInteger('grade_id');
            $table->string('students_id', 10);
            $table->string('cst_id', 20);
            $table->string('grade_type', 10);

            $table->decimal('old_score', 6, 2)->nullable();
            $table->decimal('new_score', 6, 2)->nullable();
            $table->enum('action', ['insert', 'update', 'delete']);

            $table->string('db_user', 100);                 // akun MySQL yang mengubah
            $table->timestamp('changed_at')->useCurrent();

            $table->index('grade_id');
            $table->index('students_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grade_logs');
    }
};
