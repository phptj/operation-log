<?php
namespace TangJun\SysAuditLog\Entities;

use Illuminate\Database\Eloquent\Model;

class SysAuditLog extends Model {

    protected $table = "sys_audit";
    public $timestamps = false;
    protected $fillable = [
        'id',
        'action',
        'type',
        'table_name',
        'key_column',
        'key_id',
        'orig_value',
        'new_value',
        'create_id',
        'create_batch_mark',
        'create_time'
    ];
}