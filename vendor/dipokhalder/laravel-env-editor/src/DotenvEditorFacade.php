<?php
/**
 * Created by PhpStorm.
 * User: Dipok
 * Date: 12.05.16
 * Time: 07:20
 */

namespace Dipokhalder\DotenvEditor;

use Illuminate\Support\Facades\Facade;

class DotenvEditorFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'dipokhalder-dotenveditor';
    }
}
