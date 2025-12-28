<?php
/**
 * Created by PhpStorm.
 * User: Dipok
 * Date: 12.05.16
 * Time: 07:22
 */

namespace Dipokhalder\EnvEditor;

use Illuminate\Support\ServiceProvider;

class EnvEditorServiceProvider extends ServiceProvider
{
    /**
     * Provider boot
     *
     * @return null
     */
    public function boot()
    {
        $this->publishes(
            [
                __DIR__ . '/../config/enveditor.php' => config_path('enveditor.php'),
            ],
            'config'
        );
    }

    /**
     * Provider register
     *
     * @return null
     */
    public function register()
    {
        $this->app->bind(
            'dipokhalder-enveditor',
            function () {
                return new EnvEditor();
            }
        );

        $this->mergeConfigFrom(__DIR__ . '/../config/enveditor.php', 'enveditor');
    }
}
