<?php

namespace App\Providers;

use App\Http\Clients\DicionarioAberto\DicionarioAbertoClientInterface;
use App\Http\Clients\DicionarioAberto\LiveDicionarioAbertoClient;
use App\Http\Clients\DicionarioAberto\MockDicionarioAbertoClient;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /** Register any application services. */
    public function register()
    {
        $this->app->singleton(\Faker\Generator::class, function () {
            return \Faker\Factory::create('pt_BR');
        });
    }

    /** Bootstrap any application services. */
    public function boot(): void
    {
        $this->app->bind(DicionarioAbertoClientInterface::class, function (Application $app): DicionarioAbertoClientInterface {
            if ($app->environment(['local', 'testing']) && config('services.dicionario_aberto.force_live_client') === false) {
                return new MockDicionarioAbertoClient($app->make(\Faker\Generator::class));
            }

            return new LiveDicionarioAbertoClient(
                new GuzzleClient([
                    'base_uri' => config('services.dicionario_aberto.base_uri'),
                ]),
            );
        });
    }
}
