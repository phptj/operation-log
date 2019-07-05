<?php
namespace TangJun\SysAuditLog\Entities;

use Illuminate\Database\Eloquent\Model;

class SysAuditLog extends Model {

    protected $table = "sys_audit_log";

    protected $fillable = [
        "action",
        "table_name",
        "key_column",
        "key_id",
        "orig_value",
        "new_value",
        "create_id",
        "ip",
        "browser",
        "system",
        "url",
        "content"
    ];
}