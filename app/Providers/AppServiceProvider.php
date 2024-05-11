<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Empresas;
use Illuminate\Support\Facades\Blade;
use GuzzleHttp\Client;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $baseUrl = env('API_CROL_ENDPOINT'); // Obtén el endpoint de la API desde el archivo .env

        $this->app->singleton(Client::class, function ($app) use ($baseUrl) {
            return new Client(['base_uri' => $baseUrl]);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blade::directive('money', function ($amount) {
            $cleanAmount = str_replace(',', '', $amount);
            if (is_numeric($cleanAmount)) {
                $formattedAmount = number_format($cleanAmount, 2);
                return "<?php echo '$' . $formattedAmount; ?>";
            } else {
                return "<?php echo $amount; ?>";
            }
        });
    }
}
