<?php

use App\Models\Question;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $model      = new Question;
        $connection = $model->getConnectionName();
        $tableName  = $model->getTableName();
        $hasTable   = Schema::connection($connection)->hasTable($tableName);

        if (!$hasTable) {
            Schema::connection($connection)->create($tableName, function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id')->unsigned()->index();
                $table->foreign('user_id')->references('id')->on('users');
                $table->string('title');
                $table->text('question_content');
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
        $model      = new Question;
        $connection = $model->getConnectionName();
        $tableName  = $model->getTableName();

        Schema::connection($connection)->dropIfExists($tableName);
    }
}
