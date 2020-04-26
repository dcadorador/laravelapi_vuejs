<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

use App\Transformers\TokenTransformer;
use App\Transformers\UserTransformer;

class AuthController extends ApiBaseController
{
    public function token(Request $request)
    {

        $authHeader = $request->header('Authorization');

        // Get for Auth Basic
        if (strtolower(substr($authHeader, 0, 5)) !== 'basic') {
            throw new UnauthorizedHttpException('Invalid authorization header, should be type basic');
        }

        // Get credentials
        $credentials = base64_decode(trim(substr($authHeader, 5)));
        list($username, $password) = explode(':', $credentials, 2);

        $credentials = [
            'email' => $username,
            'password' => $password
        ];

        if (! $token = auth()->attempt($credentials)) {
            throw new UnauthorizedHttpException('Unauthorized login');
        }
        
        $tokenDetails = new \stdClass;

        $tokenDetails->token  = $token;
        $tokenDetails->type   = 'bearer';
        $tokenDetails->expiry = auth()->factory()->getTTL();

        $this->transformer = new TokenTransformer;

        session(['authtoken' => $token]);
        return $this->success($tokenDetails);
    }

    public function invalidateToken(Request $request)
    {
        auth()->logout();
        $request->session()->forget('authtoken');

        return $this->response->noContent();
    }

    public function checkAuth()
    {
        $this->transformer = new UserTransformer;
        $user = \Auth::user();
        return $this->success($user);
    }
}
