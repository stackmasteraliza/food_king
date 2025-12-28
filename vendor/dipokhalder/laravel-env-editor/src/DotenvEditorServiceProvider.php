<?php
/**
 * Created by PhpStorm.
 * User: Dipok
 * Date: 12.05.16
 * Time: 07:22
 */

namespace Dipokhalder\DotenvEditor;

use Illuminate\Support\ServiceProvider;

class DotenvEditorServiceProvider extends ServiceProvider
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
                __DIR__ . '/../config/dotenveditor.php' => config_path('dotenveditor.php'),
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
            'dipokhalder-dotenveditor',
            function () {
                return new DotenvEditor();
            }
        );

        $this->mergeConfigFrom(__DIR__ . '/../config/dotenveditor.php', 'dotenveditor');
    }
}
