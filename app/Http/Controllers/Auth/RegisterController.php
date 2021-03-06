<?php namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use XeConfig;
use XeDB;
use XeDynamicField;
use XeFrontend;
use XePresenter;
use XeTheme;
use Xpressengine\User\EmailBroker;
use Xpressengine\User\Models\User;
use Xpressengine\User\Repositories\RegisterTokenRepository;
use Xpressengine\User\UserHandler;

class RegisterController extends Controller
{
    use RedirectsUsers;

    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * @var UserHandler
     */
    protected $handler;

    /**
     * @var EmailBroker
     */
    protected $emailBroker;

    protected $redirectTo = '/';

    public function __construct()
    {
        $this->auth = app('auth');
        $this->handler = app('xe.user');
        $this->emailBroker = app('xe.auth.email');

        XeTheme::selectSiteTheme();
        XePresenter::setSkinTargetId('user/auth');

        $this->middleware('auth', ['only' => ['getRegisterAddInfo', 'postRegisterAddInfo']]);
        $this->middleware('guest', [
            'except' => ['getLogin', 'getLogout', 'getConfirm', 'getRegisterAddInfo', 'postRegisterAddInfo']
        ]);
    }

    /**
     * Show the application registration form.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function getRegister(Request $request)
    {
        // 회원 가입 허용 검사
        if (!$this->checkJoinable()) {
            return redirect()->back()->with(
                ['alert' => ['type' => 'danger', 'message' => xe_trans('xe::joinNotAllowed')]]
            );
        }

        $config = app('xe.config')->get('user.join');

        // 가입 인증을 사용하지 않을 경우, 곧바로 회원가입 폼 출력
        if (!$config->get('guard_forced', false) || $request->get('token')) {
            return $this->getRegisterForm($request);
        }

        return \XePresenter::make('register.index');

    }

    /**
     * Show the application registration form.
     *
     * @param Request $request
     *
     * @return Response
     */
    protected function getRegisterForm(Request $request)
    {
        $config = app('xe.config')->get('user.join');

        // 활성화된 가입폼 가져오기
        $parts = $this->handler->getRegisterParts();
        $activated = array_keys(array_intersect_key(array_flip($config->get('forms', [])), $parts));

        $parts = collect($parts)->filter(function ($part, $key) use ($activated) {
            return in_array($key, $activated) || $part::isImplicit();
        })->map(function ($part) use ($request) {
            return new $part($request);
        });

        $rules = $parts->map(function ($part) {
            return $part->rules();
        })->collapse();

        XeFrontend::rule('join', $rules);

        return \XePresenter::make('register.create', compact('config', 'parts', 'register_token'));
    }

    /**
     * 회원가입시 이메일 인증 요청 처리
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws Exception
     */
    public function postRegisterConfirm(Request $request, RegisterTokenRepository $tokenRepository)
    {
        $this->validate($request, ['email' => 'required|email']);

        $email = $request->get('email');

        try {
            $this->handler->validateEmail($email);
        } catch (\Exception $e) {
            throw new HttpException(400, xe_trans('xe::emailAlreadyExists'));
        }

        $mail = $this->handler->pendingEmails()->findByAddress($email);

        if ($mail === null) {
            \DB::beginTransaction();
            try {
                $mailData = ['address' => $email];
                $user = new User();
                $user->id = app('xe.keygen')->generate();
                $mail = $this->handler->createEmail($user, $mailData, false);
            } catch (\Exception $e) {
                \DB::rollBack();
                throw $e;
            }
            \DB::commit();
        }

        $token = $tokenRepository->create('email', ['email' => $email, 'user_id' => $mail->user_id]);
        $this->emailBroker->sendEmailForRegister($mail, $token);

        return redirect()->route('auth.register', ['token' => $token['id']])->with(
            ['alert' => ['type' => 'success', 'message' => xe_trans('xe::msgEmailSendComplete')]]
        );
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function postRegister(Request $request)
    {
        // validation
        if (!$this->checkJoinable()) {
            return redirect()->back()->with(
                ['alert' => ['type' => 'danger', 'message' => xe_trans('xe::joinNotAllowed')]]
            );
        }

        $config = app('xe.config')->get('user.join');

        // 활성화된 가입폼 가져오기
        $parts = $this->handler->getRegisterParts();
        $activated = array_keys(array_intersect_key(array_flip($config->get('forms', [])), $parts));

        $parts = collect($parts)->filter(function ($part, $key) use ($activated) {
            return in_array($key, $activated) || $part::isImplicit();
        })->map(function ($part) use ($request) {
            return new $part($request);
        });

        $userData = $parts->map(function ($part) {
            return $part->validate();
        })->collapse()->all();

        // set default join group
        $joinGroup = $config->get('joinGroup');
        if ($joinGroup !== null) {
            $userData['group_id'] = [$joinGroup];
        }

        XeDB::beginTransaction();
        try {
            $user = $this->handler->create($userData);
        } catch (\Exception $e) {
            XeDB::rollback();
            throw $e;
        }
        XeDB::commit();

        // login and redirect
        $this->auth->login($user);

        return redirect($this->redirectPath());
    }

    /**
     * checkJoinable
     *
     * @return boolean
     */
    protected function checkJoinable()
    {
        return XeConfig::getVal('user.join.joinable') === true;
    }

    public function getRegisterAddInfo()
    {
        $fields = $this->getAdditionalField();

        XeFrontend::rule('add-info', $fields->map(function ($field) {
            return $field->getRules();
        })->collapse());

        return XePresenter::make('register.add-info', compact('fields'));
    }

    public function postRegisterAddInfo(Request $request)
    {
        $fields = $this->getAdditionalField();
        $rules =$fields->map(function ($field) {
            return $field->getRules();
        })->collapse();
        
        $inputs = $this->validate($request, $rules->all());

        $this->handler->update(auth()->user(), $inputs);

        return redirect($this->redirectPath());
    }

    protected function getAdditionalField()
    {
        return collect(XeDynamicField::gets('user'))->filter(function ($field) {
            if (!$field->isEnabled()) {
                return false;
            }

            $rules = implode('|', $field->getRules());
            $rules = array_map('\Illuminate\Support\Str::snake', explode('|', $rules));

            return in_array('required', $rules);
        });
    }
}
