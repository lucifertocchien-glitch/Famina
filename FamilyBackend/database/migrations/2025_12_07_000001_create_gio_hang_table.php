<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        if (!Schema::hasTable('gio_hang')) {
            Schema::create('gio_hang', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('MaKH', 50)->index();
                $table->string('MaSP', 50)->index();
                $table->integer('SoLuong')->default(1);
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('gio_hang');
    }
};
