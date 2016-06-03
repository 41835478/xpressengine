<?php
/**
 * Site.php
 *
 * @category    Command
 * @package     App\Console\Commands
 * @author      XE Team (developers) <developers@xpressengine.com>
 * @copyright   2015 Copyright (C) NAVER <http://www.navercorp.com>
 * @license     http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL
 * @link        http://www.xpressengine.com
 */

namespace App\Console\Commands;

use Illuminate\Console\Command;
use InvalidArgumentException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Xpressengine\Site\Exceptions\NotFoundSiteException;
use Xpressengine\Site\SiteHandler;
use Xpressengine\Site\Site as SiteModel;

/**
 * Class Site
 *
 * @category    Command
 * @package     App\Console\Commands
 * @author      XE Team (developers) <developers@xpressengine.com>
 * @copyright   2015 Copyright (C) NAVER <http://www.navercorp.com>
 * @license     http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL
 * @link        http://www.xpressengine.com
 */
class Site extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'site:host:update';
    /**
     * @var SiteHandler
     */
    protected $handler;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '사이트 Host 정보가 변경되었습니다 ';

    /**
     * @param SiteHandler $handler site handler
     */
    public function __construct(SiteHandler $handler)
    {
        $this->handler = $handler;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $siteKey = $this->option('siteKey');
        $host = $this->option('host');

        $handler = $this->handler;

        try {
            $host = $this->validateHost($host);

            /** @var SiteModel $site */
            $site = SiteModel::find($siteKey);
            $site->host = $host;

            $handler->put($site);

            $this->comment($this->description);

        } catch (NotFoundSiteException $e) {
            $this->comment("{$siteKey} 에 해당하는 사이트 정보를 찾을 수 없습니다");
        } catch (InvalidArgumentException $e) {
            $this->comment("{$host} 형식이 잘못 입력되었습니다. 입력예) example.com ");
        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['action', InputArgument::OPTIONAL, '"list" 등록된 사이트 목록을 확인합니다. "update" 사이트 호스트를 변경합니다.', 'list'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['siteKey', null, InputOption::VALUE_REQUIRED, '사이트 키를 지정합니다', 'default'],
            ['host', null, InputOption::VALUE_REQUIRED, '변경할 호스트'],
        ];
    }

    /**
     * validateHost
     *
     * @param string $host host
     *
     * @return string
     */
    private function validateHost($host)
    {
        if (preg_match("/^([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/i", $host) //valid chars check
            && preg_match("/^.{1,253}$/", $host) //overall length check
            && preg_match("/^[^\.]{1,63}(\.[^\.]{1,63})*$/", $host) //length of each label
        ) {
            return $host;
        } else {
            throw new InvalidArgumentException;
        }
    }
}
