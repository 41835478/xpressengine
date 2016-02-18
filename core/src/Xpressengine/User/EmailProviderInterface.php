<?php
/**
 *  This file is part of the Xpressengine package.
 *
 * PHP version 5
 *
 * @category    Member
 * @package     Xpressengine\Member
 * @author      XE Team (developers) <developers@xpressengine.com>
 * @copyright   2015 Copyright (C) NAVER <http://www.navercorp.com>
 * @license     http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL
 * @link        http://www.xpressengine.com
 */
namespace Xpressengine\User;

/**
 * 이 인터페이스는 회원의 등록 대기중인 이메일의 정보를 저장하는 저장소가 구현해야 하는 인터페이스이다.
 *
 * @category    Member
 * @package     Xpressengine\Member
 * @author      XE Team (developers) <developers@xpressengine.com>
 * @copyright   2015 Copyright (C) NAVER <http://www.navercorp.com>
 * @license     http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL
 * @link        http://www.xpressengine.com
 */
interface EmailProviderInterface
{

    /**
     * 이메일 주소로 등록대기 이메일 정보를 조회한다.
     *
     * @param string        $address 조회할 이메일 주소
     * @param string[]|null $with    entity와 함께 반환할 relation 정보
     *
     * @return EmailInterface
     */
    public function findByAddress($address, $with = null);

    /**
     * 주어진 회원이 소유한 이메일 목록을 조회한다.
     *
     * @param string        $memberId member id
     * @param string[]|null $with     entity와 함께 반환할 relation 정보
     *
     * @return EmailInterface[]
     */
    public function findByMember($memberId, $with = null);

    /**
     * 주어진 회원이 소유한 이메일을 삭제한다.
     *
     * @param string $userIds 삭제할 이메일을 소유한 회원의 id
     *
     * @return integer
     */
    public function deleteByMemberIds($userIds);

    /**
     * 주어진 회원이 소유한 이메일의 인증 코드를 반환한다.
     *
     * @param string        $memberId member id
     * @param string        $code     mail confirmation code
     * @param string[]|null $with     entity와 함께 반환할 relation 정보
     *
     * @return EmailInterface
     */
    public function findByConfirmationCode($memberId, $code, $with = null);
}
