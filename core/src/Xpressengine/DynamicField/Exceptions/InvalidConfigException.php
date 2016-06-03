<?php
/**
 * AlreadyExistException
 *
 * @category    DynamicField
 * @package     Xpressengine\DynamidField
 * @author      XE Team (developers) <developers@xpressengine.com>
 * @copyright   2015 Copyright (C) NAVER <http://www.navercorp.com>
 * @license     http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL
 * @link        http://www.xpressengine.com
 */
namespace Xpressengine\DynamicField\Exceptions;

use Xpressengine\DynamicField\DynamicFieldException;

/**
 * Invalid config exception
 *
 * @category    DynamicField
 * @package     Xpressengine\DynamicField
 * @author      XE Team (developers) <developers@xpressengine.com>
 * @copyright   2015 Copyright (C) NAVER <http://www.navercorp.com>
 * @license     http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL
 * @link        http://www.xpressengine.com
 */
class InvalidConfigException extends DynamicFieldException
{
    protected $message = 'Invalid dynamic field config.';
}
