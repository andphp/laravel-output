<?php

/**
 * Created by PhpStorm.
 * User: DaXiong
 * Date: 2021/4/1
 * Time: 12:36 AM
 */

namespace AndPHP\OutPut;

use Illuminate\Support\ServiceProvider;

class OutPutServiceProvider extends ServiceProvider
{

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/Stubs/error.plain.stub' => app_path('Constant/Error.php'),
        ]);
    }
}