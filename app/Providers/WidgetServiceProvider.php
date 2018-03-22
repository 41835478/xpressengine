<?php
/**
 * @author      XE Developers <developers@xpressengine.com>
 * @copyright   2015 Copyright (C) NAVER Corp. <http://www.navercorp.com>
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.html LGPL-2.1
 * @link        https://xpressengine.io
 */

namespace App\Providers;

use App\Skins\Widget\ContentInfoSkin;
use App\Skins\Widget\DownloadRankSkin;
use App\Skins\Widget\HtmlWidgetSkin;
use App\Skins\Widget\StorageSpaceSkin;
use App\Skins\Widget\SystemInfoSkin;
use App\UIObjects\Widget\WidgetGenerator;
use App\UIObjects\Widget\WidgetBox as WidgetBoxUIObject;
use App\Widgets\ContentInfo;
use App\Widgets\DownloadRank;
use App\Widgets\HtmlWidget;
use App\Widgets\StorageSpace;
use App\Widgets\SystemInfo;
use Illuminate\Support\ServiceProvider;
use Xpressengine\Widget\Models\WidgetBox;
use Xpressengine\Widget\Presenters\AbstractPresenter;
use Xpressengine\Widget\Presenters\BootstrapPresenter;
use Xpressengine\Widget\Presenters\XEUIPresenter;
use Xpressengine\Widget\WidgetBoxHandler;
use Xpressengine\Widget\WidgetBoxRepository;
use Xpressengine\Widget\WidgetHandler;
use Xpressengine\Widget\WidgetParser;

/**
 * Class WidgetServiceProvider
 *
 * @category    Widget
 * @package     App\Providers
 */
class WidgetServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(WidgetHandler::class, function ($app) {
            $proxyClass = $app['xe.interception']->proxy(WidgetHandler::class, 'XeWidget');
            $widgetHandler = new $proxyClass(
                $app['xe.pluginRegister'],
                $app['auth']->guard(),
                $app['view'],
                $app['config']->get('app.debug') === true
            );

            return $widgetHandler;
        });
        $this->app->alias(WidgetHandler::class, 'xe.widget');


        $this->app->singleton(WidgetParser::class, function ($app) {
            $handler = $app['xe.widget'];
            return new WidgetParser($handler);
        });
        $this->app->alias(WidgetParser::class, 'xe.widget.parser');

        $this->app->singleton(WidgetBoxHandler::class, function ($app) {
            $proxyClass = $app['xe.interception']->proxy(WidgetBoxHandler::class, 'XeWidgetBox');
            $widgetHandler = new $proxyClass(
                new WidgetBoxRepository,
                $app['xe.permission']
            );
            return $widgetHandler;
        });
        $this->app->alias(WidgetBoxHandler::class, 'xe.widgetbox');
    }

    /**
     * boot
     *
     * @return void
     */
    public function boot()
    {
        $this->app->resolving('xe.widgetbox', function () {
            AbstractPresenter::setWidgetCodeGenerator(function ($widgetId, array $inputs) {
                return $this->app['xe.widget']->generateCode($widgetId, $inputs);
            });

            WidgetBoxHandler::setContainer($this->app['xe.register']);
            WidgetBoxHandler::addPresenter(BootstrapPresenter::class);
            WidgetBoxHandler::addPresenter(XEUIPresenter::class);
        });
    }
}
