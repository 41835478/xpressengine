<?php
/**
 * This file is part of the Xpressengine package.
 *
 * @category    Interception
 * @package     Xpressengine\Interception
 * @author      XE Team (developers) <developers@xpressengine.com>
 * @copyright   2015 Copyright (C) NAVER <http://www.navercorp.com>
 * @license     http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL
 * @link        http://www.xpressengine.com
 */
namespace Xpressengine\Interception\Exceptions;

use Xpressengine\Interception\InterceptionException;

/**
 * 중복된 advisor 이름이 등록되었을 때 발생하는 예외
 *
 * @category    Interception
 * @package     Xpressengine\Interception
 * @author      XE Team (developers) <developers@xpressengine.com>
 * @license     http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL
 * @link        http://www.xpressengine.com
 */
class AdvisorNameAlreadyExistsException extends InterceptionException
{
    protected $message = 'Advisor[:name] already exists.';
}
