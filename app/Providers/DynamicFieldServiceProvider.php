<?php
/**
 * Service provider
 *
 * PHP version 5
 *
 * @category    DyanmicField
 * @package     Xpressengine\DyanmicField
 * @author      XE Developers <developers@xpressengine.com>
 * @copyright   2015 Copyright (C) NAVER Corp. <http://www.navercorp.com>
 * @license     LGPL-2.1
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.html
 * @link        https://xpressengine.io
 */

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App;
use XeRegister;
use Xpressengine\DynamicField\ConfigHandler;
use Xpressengine\DynamicField\DatabaseProxy;
use Xpressengine\DynamicField\DynamicFieldHandler;
use Xpressengine\DynamicField\RegisterHandler;
use Xpressengine\DynamicField\RevisionManager;
use Xpressengine\FieldTypes\Category;
use Xpressengine\FieldTypes\Number;
use Xpressengine\FieldTypes\Text;
use Xpressengine\FieldTypes\Boolean;
use Xpressengine\FieldTypes\Address;

/**
 * laravel service provider
 *
 * @category    DynamicField
 * @package     Xpressengine\DynamicField
 */
class DynamicFieldServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        app('xe.db.proxy')->register(new DatabaseProxy(App::make('xe.dynamicField')));
        $this->registerFieldType();
    }

    /**
     * register field type
     *
     * @return void
     */
    private function registerFieldType()
    {
        $registerHandler = app('xe.dynamicField')->getRegisterHandler();

        $registerHandler->add(Category::class);
        $registerHandler->add(Number::class);
        $registerHandler->add(Text::class);
        $registerHandler->add(Boolean::class);
        $registerHandler->add(Address::class);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('xe.dynamicField', function ($app) {

            /** @var \Xpressengine\Database\VirtualConnectionInterface $connection */
            $connection = $app['xe.db']->connection();
            $proxyClass = $app['xe.interception']->proxy(DynamicFieldHandler::class, 'DynamicField');
            return new $proxyClass(
                $connection,
                new ConfigHandler($connection, $app['xe.config']),
                new RegisterHandler($this->app['xe.pluginRegister'])
            );
        });

        $this->app->singleton('xe.dynamicField.revision', function ($app) {
            return new RevisionManager($app['xe.dynamicField']);
        });
    }
}
