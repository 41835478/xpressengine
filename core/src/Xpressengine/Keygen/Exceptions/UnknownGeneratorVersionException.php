<?php
/**
 * This file is exception for unknown generator call.
 *
 * @category    Keygen
 * @package     Xpressengine\Keygen
 * @author      XE Team (developers) <developers@xpressengine.com>
 * @copyright   2015 Copyright (C) NAVER <http://www.navercorp.com>
 * @license     http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL
 * @link        http://www.xpressengine.com
 */
namespace Xpressengine\Keygen\Exceptions;

use Xpressengine\Keygen\KeygenException;

/**
 * 잘못된 생성자 호출시 발생되는 예외
 *
 * @category    Keygen
 * @package     Xpressengine\Keygen
 * @author      XE Team (developers) <developers@xpressengine.com>
 * @copyright   2015 Copyright (C) NAVER <http://www.navercorp.com>
 * @license     http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL
 * @link        http://www.xpressengine.com
 */
class UnknownGeneratorVersionException extends KeygenException
{
    protected $message = 'Unknown version [#:version]';
}
