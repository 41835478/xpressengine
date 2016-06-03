<?php
/**
 * This file is register this package for laravel
 *
 * @category    Captcha
 * @package     Xpressengine\Captcha
 * @author      XE Team (developers) <developers@xpressengine.com>
 * @copyright   2015 Copyright (C) NAVER <http://www.navercorp.com>
 * @license     http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL
 * @link        http://www.xpressengine.com
 */
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Xpressengine\Captcha\CaptchaManager;
use Xpressengine\UIObjects\Captcha\CaptchaUIObject;

/**
 * laravel 에서 사용하기위해 등록처리를 하는 class
 *
 * @category    Captcha
 * @package     Xpressengine\Captcha
 * @author      XE Team (developers) <developers@xpressengine.com>
 * @copyright   2015 Copyright (C) NAVER <http://www.navercorp.com>
 * @license     http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL
 * @link        http://www.xpressengine.com
 */
class CaptchaServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        CaptchaUIObject::setManager($this->app['xe.captcha']);



        $this->app['xe.pluginRegister']->add(CaptchaUIObject::class);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('xe.captcha', function ($app) {
            $proxyClass = $app['xe.interception']->proxy(CaptchaManager::class, 'Captcha');
            $captchaManager = new $proxyClass($app);
            return $captchaManager;
        }, true);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['xe.captcha'];
    }
}
