<?php
/**
 * This file is not available exception
 *
 * @category    Media
 * @package     Xpressengine\Media
 * @author      XE Team (developers) <developers@xpressengine.com>
 * @copyright   2015 Copyright (C) NAVER <http://www.navercorp.com>
 * @license     http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL
 * @link        http://www.xpressengine.com
 */
namespace Xpressengine\Media\Exceptions;

use Xpressengine\Media\MediaException;

/**
 * 이용가능하지 않은 경우
 *
 * @category    Media
 * @package     Xpressengine\Media
 * @author      XE Team (developers) <developers@xpressengine.com>
 * @copyright   2015 Copyright (C) NAVER <http://www.navercorp.com>
 * @license     http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL
 * @link        http://www.xpressengine.com
 */
class NotAvailableException extends MediaException
{
    protected $message = '사용할 수 없는 파일입니다.';
}
