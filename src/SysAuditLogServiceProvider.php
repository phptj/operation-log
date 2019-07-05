<?php

namespace TangJun\SysAuditLog;

use Illuminate\Support\ServiceProvider;
use SysAuditLog;

class SysAuditLogServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Publish configuration files

        $this->publishes([
            __DIR__.'/migrations' => database_path('migrations'),
        ], 'migrations');


        $this->publishes([
            __DIR__.'/config/sysauditlog.php' => config_path('sysauditlog.php'),
        ], 'config');

        $model = config("sysauditlog.entities");
		if($model){
			foreach($model as $k => $v) {

			$v::saved(function($data){
                SysAuditLog::createActionLog($data,'update');
			});
			
			$v::deleted(function($data){
                SysAuditLog::createActionLog($data,'delete');

			});
			
			}
		}
        

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton("SysAuditLog",function($app){
            return new \TangJun\SysAuditLog\Repositories\SysAuditLogRepository();
        });
    }
}
