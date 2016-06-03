<?php
/**
 *  This file is part of the Xpressengine package.
 *
 * @category    Plugin
 * @package     Xpressengine\Skins\Plugin
 * @author      XE Team (developers) <developers@xpressengine.com>
 * @copyright   2015 Copyright (C) NAVER <http://www.navercorp.com>
 * @license     http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL
 * @link        http://www.xpressengine.com
 */
namespace Xpressengine\Skins\Plugin;

use Xpressengine\Skin\BladeSkin;

/**
 * @category    Plugin
 * @package     Xpressengine\Skins\Plugin
 * @author      XE Team (developers) <developers@xpressengine.com>
 * @license     http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL
 * @link        http://www.xpressengine.com
 */
class PluginSettingsSkin extends BladeSkin
{

    protected static $id = 'plugins/settingsSkin/xpressengine@default';

    protected static $componentInfo = [
        'name' => '기본 플러그인 설정스킨',
        'description' => 'Xpressengine의 기본 플러그인 설정페이지 스킨입니다'
    ];

    protected $path = 'plugin.skins.default';

    /**
     * listView
     *
     * @return \Illuminate\View\View
     */
    protected function index()
    {
        $this->loadDefault();
        return $this->renderBlade();
    }

    /**
     * listView
     *
     * @return \Illuminate\View\View
     */
    protected function show()
    {
        $this->loadDefault();

        app('xe.frontend')->js([
           'assets/vendor/swiper2/idangerous.swiper.js',
           'assets/core/plugin/js/plugin.js'
        ])->appendTo('head')->load();
        app('xe.frontend')->css('assets/vendor/swiper2/idangerous.swiper.css')
            ->before('assets/core/settings/css/admin.css')->load();

        return $this->renderBlade();
    }

    protected function loadDefault()
    {
        app('xe.frontend')->html('plugins.loadTooltip')->content("<script>
            $(function () {
              $('[data-toggle=tooltip]').tooltip()
            })
        </script>")->load();



        array_set($this->data, 'color', [
            'theme' => 'success',
            'skin' => 'info',
            'settingsSkin' => 'warning',
            'settingsTheme' => 'warning',
            'widget' => 'danger',
            'module' => 'danger',
            'uiobject' => 'primary',
            'FieldType' => 'default',
            'FieldSkin' => 'default',
        ]);
    }

}
