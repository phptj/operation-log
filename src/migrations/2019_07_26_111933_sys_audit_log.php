<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SysAuditLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sys_audit_log', function (Blueprint $table) {
            $table->increments('id');
            $table->string("action",20)->comment("动作：create、update、delete");
            $table->string("table_name",80)->comment("表名");
            $table->string("key_column",100)->comment("字段名称");
            $table->integer("key_id")->comment("主键id");
            $table->string("orig_value",1000)->comment("字段之前的值");
            $table->string("new_value",1000)->comment("字段修改之后的值");
            $table->integer("create_id")->comment("用户id");
            $table->string("create_batch_mark","50")->comment("操作标识");
            $table->string("create_time",150)->comment("发生时间");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sys_audit_log');
    }
}
