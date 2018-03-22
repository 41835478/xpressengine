<?php
/**
 * Service provider
 *
 * PHP version 5
 *
 * @category  Menu
 * @package   Xpressengine\Menu
 * @author    XE Developers <developers@xpressengine.com>
 * @copyright 2015 Copyright (C) NAVER Corp. <http://www.navercorp.com>
 * @license   http://www.gnu.org/licenses/old-licenses/lgpl-2.1.html LGPL-2.1
 * @link      https://xpressengine.io
 */

namespace App\Providers;

use App\UIObjects\Menu\MenuSelect;
use Illuminate\Support\HtmlString;
use Illuminate\Support\ServiceProvider;
use Xpressengine\Menu\EventListener;
use Xpressengine\Menu\MenuHandler;
use Xpressengine\Menu\MenuItemPolicy;
use Xpressengine\Menu\Models\Menu;
use Xpressengine\Menu\Models\MenuItem;
use Xpressengine\Menu\ModuleHandler;
use Xpressengine\Menu\Repositories\IdentifierGenerator;
use Xpressengine\Menu\Repositories\MenuItemRepository;
use Xpressengine\Menu\Repositories\MenuRepository;
use App\UIObjects\Menu\MenuType;
use Xpressengine\Menu\MenuType\DirectLink;
use App\UIObjects\Menu\TypeSelect;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;

/**
 * Menu Service Provider
 *
 * @category Menu
 * @package  Xpressengine\Menu
 */
class MenuServiceProvider extends ServiceProvider
{
    protected $policies = [
        MenuItem::class => MenuItemPolicy::class
    ];

    /**
     * Service Provider Boot
     *
     * @return void
     */
    public function boot(GateContract $gate)
    {
        // @todo route 처리과정에서 xe.menu 를 통하지 않고 메뉴를 사용하는지 점검
//        $this->app->resolving('xe.menu', function () {
            MenuItemRepository::setMenuModelProvider(function () {
                return MenuRepository::getModel();
            });
//        });

        $this->app['events']->subscribe(EventListener::class);

        foreach ($this->policies as $class => $policy) {
            $gate->policy($class, $policy);
        }

        // 메뉴아이템의 링크를 편하게 제공하기 위한 resolver 등록
        MenuItem::setLinkResolver(function(MenuItem $item){
            $title = xe_trans($item->getAttributeValue('title'));
            if($image = $item->getRelationValue('basicImage')) {
                $image = $item->isSelected() ? $item->getSelectedImage() : $image;
                $hoverImage = $item->getHoverImage();
                return new HtmlString(sprintf(
                    '<img src="%s" class="__xe_menu_image" data-hover="%s" alt="%s"/>',
                    $image,
                    $hoverImage,
                    $title
                ));
            }
            return $title;
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        /**
         * @deprecated since beta.17
         * ModuleHandler 는 MenuHandler 를 통해 사용가능 하도록 변경
         */
        $this->app->singleton(ModuleHandler::class, function ($app) {
            $register = $app['xe.pluginRegister'];
            $proxyClass = $app['xe.interception']->proxy(ModuleHandler::class, 'XeModule');
            return new $proxyClass($register);
        });
        $this->app->alias(ModuleHandler::class, 'xe.module');
        
        $this->app->singleton(MenuHandler::class, function ($app) {
            $generator = new IdentifierGenerator($app['xe.keygen']);

            return new MenuHandler(
                new MenuRepository($generator),
                new MenuItemRepository($generator, $app['events']),
                $app['xe.config'],
                $app['xe.module'],
                $app['xe.router']
            );
        });
        $this->app->alias(MenuHandler::class, 'xe.menu');
    }
}
