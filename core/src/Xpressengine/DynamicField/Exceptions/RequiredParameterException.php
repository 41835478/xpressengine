<?php
/**
 * Exceptions
 *
 * PHP version 5
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
 * Required parameter exception
 *
 * @category    DynamicField
 * @package     Xpressengine\DynamicField
 * @author      XE Team (developers) <developers@xpressengine.com>
 * @copyright   2015 Copyright (C) NAVER <http://www.navercorp.com>
 * @license     http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL
 * @link        http://www.xpressengine.com
 */
class RequiredParameterException extends DynamicFieldException
{
    protected $message = '":name" parameter required.';
}
