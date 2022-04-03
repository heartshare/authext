<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPhoneToAdminUsersTable extends Migration
{
  public function getConnection()
  {
    return config('database.connection') ?: config('database.default');
  }

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table(config('admin.database.users_table'), function (Blueprint $table) {
      $table->string('phone', 60)->after('username')->unique()->default('10000000000')->nullable(false);
      $table->string('username', 120)->nullable()->change();
      $table->string('password', 80)->nullable()->change();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table(config('admin.database.users_table'), function (Blueprint $table) {
      $table->dropColumn('phone');
      $table->string('username', 120)->nullable(false)->change();
      $table->string('password', 80)->nullable(false)->change();
    });
  }
}
