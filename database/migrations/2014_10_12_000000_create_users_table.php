<?php

use App\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $model = new User;
        $connection = $model->getConnectionName();
        $tableName = $model->getTableName();
        $hasTable = Schema::connection($connection)->hasTable($tableName);

        if (!$hasTable) {
            Schema::connection($connection)->create($tableName, function (Blueprint $table) {
                $table->increments('id');
                $table->string('email')->unique();
                $table->string('username')->unique()->nullable();
                $table->string('first_name')->nullable();
                $table->string('last_name')->nullable();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->rememberToken();
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $model = new User;
        $connection = $model->getConnectionName();
        $tableName = $model->getTableName();

        Schema::connection($connection)->dropIfExists($tableName);
    }
}
