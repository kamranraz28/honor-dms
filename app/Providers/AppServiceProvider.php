<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::directive('moneybdt', function ($amount) {
            return "<?php echo  number_format($amount, 2).'Tk'; ?>";
});
}

/**
* Register any application services.
*
* @return void
*/
public function register()
{
//
}
}
