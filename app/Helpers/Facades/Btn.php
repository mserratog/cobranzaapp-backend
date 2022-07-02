<?
namespace App\Helpers\Facades;

use Illuminate\Support\Facades\Facade;

class Btn extends Facade{

    protected static function getFacadeAccessor()
    {
        return 'btn';
    }
}
