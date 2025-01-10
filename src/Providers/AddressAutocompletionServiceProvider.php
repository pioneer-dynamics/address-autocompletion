<?php

namespace PioneerDynamics\AddressAutocomplete\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;
use PioneerDynamics\AddressAutocomplete\Contracts\AddressAutoCompletionProvider;

class AddressAutocompletionServiceProvider extends ServiceProvider implements DeferrableProvider
{
    const CONFIG_FILE = __DIR__.'/../..//config/address-autocompletion.php';

    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(self::CONFIG_FILE, 'share');

        $this->app->singleton(AddressAutoCompletionProvider::class, function ($app) {

            $config = $app->config['address-autocompletion'];

            $driver_name = $this->getDefaultDriver($config);

            $driver_config = $this->getDriverConfig($config, $driver_name);
            
            $provider_class = $driver_config['class'];

            return new $provider_class( $driver_config['config']);
        });
    }

    private function definePublishableComponents()
    {
        $this->publishes([
            self::CONFIG_FILE => config_path('address-autocompletion.php')
        ], 'address-autocomplete');
    }

    private function getDriverConfig($config, $driver_name): array
    {
        return $config['providers'][$driver_name];
    }

    private function getDefaultDriver($config): string
    {
        return $config['default'];
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->definePublishableComponents();
    }

    public function provides(): array
    {
        return [
            AddressAutoCompletionProvider::class,
        ];
    }
}
