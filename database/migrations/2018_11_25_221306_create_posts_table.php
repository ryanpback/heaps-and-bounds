<?php

use App\Models\Post;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $model      = new Post;
        $connection = $model->getConnectionName();
        $tableName  = $model->getTableName();
        $hasTable   = Schema::connection($connection)->hasTable($tableName);

        if (!$hasTable) {
            Schema::connection($connection)->create($tableName, function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id')->unsigned()->index();
                $table->foreign('user_id')->references('id')->on('users');
                $table->string('title');
                $table->text('post_content');
                $table->string('status')->default('draft');
                $table->boolean('pinned')->default(false);
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
        $model = new Post;
        $connection = $model->getConnectionName();
        $tableName = $model->getTableName();

        Schema::connection($connection)->dropIfExists($tableName);
    }
}
