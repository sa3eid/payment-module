<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Response;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        
        Response::macro('success', function($data=null, $message){
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data
          ]);
        });

      Response::macro('error', function($errors){
        return response()->json([
          'status' => false,
          'errors' => $errors
        ]);
      });
    }
}
