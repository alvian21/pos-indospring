<?php

namespace App\Providers;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

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
        Blade::directive('rupiah', function ($expression) {
            return "Rp. <?php echo number_format($expression, 2, ',', '.'); ?>";
        });

        Blade::directive('customSearch', function ($keyword,$arrayToSearch) {
            foreach($arrayToSearch as $key => $arrayItem){
                if( stristr( $arrayItem, $keyword ) ){
                    return true;
                }
            }
        });
    }
}
