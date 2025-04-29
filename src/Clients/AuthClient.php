<?php
namespace Bytecraftnz\Larabase\Clients;

use Bytecraftnz\Larabase\Supabase;
use Bytecraftnz\Larabase\Models\AuthError;
use Bytecraftnz\Larabase\Responses\AuthResponse;
use Bytecraftnz\Larabase\Responses\UserResponse;

final class AuthClient extends Supabase implements \Bytecraftnz\Larabase\Contracts\AuthClient
{

    public function __construct(
        protected string $url,
        protected string $key,
        protected \GuzzleHttp\Client $httpClient
    )
    {

        parent::__construct($url, $key,$httpClient );
    }

    /**
     * Sign in (authenticate by email and password)
     *
     * @param  string  $email  The user email
     * @param  string  $password  The user password
     */
    public function signInWithEmailAndPassword(string $email, string $password): AuthResponse|AuthError
    {
        $options = [
            'body' => [
                'email' => $email,
                'password' => $password,
            ],
        ];


        return $this->buildAuthResponse(
            $this->doPostRequest('token?grant_type=password', $options)
        );

    }

    /**
     * Sign in (authenticate by refresh token)
     *
     * @param  string  $refreshToken  The refresh token
     */
    public function signInWithRefreshToken(string $refreshToken): AuthResponse|AuthError
    {
        $options = [
            'body' => [
                'refresh_token' => $refreshToken,
            ],
        ];

        return $this->buildAuthResponse(
            $this->doPostRequest('token?grant_type=refresh_token', $options)
        );

    }

    /**
     * Sign in (authenticate by SMS OTP)
     *
     * @param  string  $phone  The user phone number
     * @return AuthResponse | AuthError
     */
    public function signInWithSMSOTP(string $phone): ?AuthError
    {
        throw new \Exception('Not implemented');
        $fields = [
            'phone' => $phone,
        ];

        $response = $this->doPostRequest('otp', $fields);

        return isset($response['error']) ? new AuthError($response['error']) : null;          
    }

    /**
     * Sign in (authenticate by magic link sended to user email)
     *
     * @param  string  $email  The user email
     */
    public function signInMagicLink(string $email): AuthResponse|AuthError
    {
        throw new \Exception('Not implemented');
        $fields = [
            'email' => $email,
        ];

        return $this->buildAuthResponse(
            $this->doPostRequest('magiclink', $fields)
        );        
    }

    /**
     * Sign up (create user by email and password)
     *
     * @param  string  $email  The user email
     * @param  string  $password  The user password
     * @param  array  $data  Additional data to be sent stored as user metadata
     */
    public function signUpWithEmailAndPassword(string $email, string $password, array $data): AuthResponse|AuthError
    {
        // Implement sign-up logic here
        $fields = [
            'email' => $email,
            'password' => $password,
        ];
        if (is_array($data) && count($data) > 0) {
            $fields['data'] = ['data' => $data];
        }

        return $this->buildAuthResponse(
            $this->doPostRequest('signup', ['body' => $fields])
        );
    }

    /**
     * Sign Up (create user by phone and password)
     *
     * @param  string  $phone  The user phone number
     * @param  string  $password  The user password
     * @param  array  $data  Additional data to be sent stored as user metadata
     */
    public function signUpWithPhoneAndPassword(string $phone, string $password, array $data = []): AuthResponse|AuthError
    {
        $fields = [
            'body' => [
                'phone' => $phone,
                'password' => $password,
            ],
        ];
        if (is_array($data) && count($data) > 0) {
            $fields['data'] = ['data' => $data];
        }

        return $this->buildAuthResponse(
            $this->doPostRequest('signup', $fields)
        );
    }

    /**
     * Verify OTP - Phone (One Time Password)
     *
     * @param  string  $otp  The OTP code
     * @param  string  $token  The token received in the OTP process
     */
    public function verifyOtpViaPhone(string $otp, string $token): AuthResponse
    {
        return $this->verifyOtp('phone', $otp, $token);
    }

    /**
     * Verify OTP - Email (One Time Password)
     *
     * @param  string  $otp  The OTP code
     * @param  string  $token  The token received in the OTP process
     */
    public function verifyOtpViaEmail(string $otp, string $token): AuthResponse|AuthError
    {
        return $this->verifyOtp('email', $otp, $token);
    }

    /**
     * Logout
     *
     * @param  string  $bearerUserToken  The bearer token (from in sign in process)
     */
    public function signOut(string $bearerToken): ?AuthError
    {

        $options = [
            'headers' => $this->getHeadersWithBearer($bearerToken),
        ];

        $response = $this->doPostRequest('logout', $options, null);

        return isset($response['error']) ? new AuthError($response['error']) : null;        
    }

    /**
     * Recover the user password (by a link sended to user email)
     *
     * @param  string  $email  The user email
     */
    public function resetPasswordForEmail(string $email, array $options): ?AuthError
    {
        $fields = [
            'body' => [
                'email' => $email,
            ],
            'redirect_to' => $options['redirectTo'] ?? null,
        ];

        $response = $this->doPostRequest('recover', $fields, null);

        return isset($response['error']) ? new AuthError($response['error']) : null;
    }

    /**
     * Update the user data
     *
     * @param  string  $bearerUserToken  The bearer user token (generated in sign in process)
     * @param  array  $data  Optional. The user meta data
     */
    public function updateUser(string $bearerToken, array $data = []): UserResponse|AuthError
    {
        if (isset($data['data'])) {
            $data['data'] = [
                'data' => $data['data'],
            ];
        }

        $options = [
            'headers' => $this->getHeadersWithBearer($bearerToken),
            'body' => $data,
        ];

        return $this->buildUserResponse(
            $this->doPutRequest('user', $options)
        );

    }

    /**
     * Update the user Password
     *
     * @param  string  $bearerUserToken  The bearer user token (generated in sign in process)
     * @param  string  $password  Optional. The user password
     */
    public function updateUserPassword(string $bearerToken, string $password): UserResponse|AuthError
    {
        return $this->updateUser($bearerToken, ['password' => $password]);
    }

    /**
     * Update the user Email
     *
     * @param  string  $bearerUserToken  The bearer user token (generated in sign in process)
     * @param  string  $email  Optional. The user email
     */
    public function updateUserEmail(string $bearerToken, string $email): UserResponse|AuthError
    {
        return $this->updateUser($bearerToken, ['email' => $email]);
    }

    /**
     * Get the user data
     *
     * @param  string  $bearerUserToken  The bearer user token (generated in sign in process)
     */
    public function getUser(string $bearerToken): UserResponse|AuthError
    {
        $options = [
            'headers' => $this->getHeadersWithBearer($bearerToken),
        ];

        return $this->buildUserResponse(
                $this->doGetRequest('user', $options)
            );

    }

    /**
     * Check if the user is authenticated
     *
     * @param  string  $bearerUserToken  The bearer user token (generated in sign in process)
     */
    public function isAuthenticated(string $bearerUserToken): bool
    {
        $user = $this->getUser($bearerUserToken);

        return true; // ($user->getUser())->getAud() == 'authenticated';
    }

    /**
     * Verify OTP (One Time Password)
     *
     * @param  string  $type  The type of OTP (email or phone)
     * @param  string  $otp  The OTP code
     * @param  string  $token  The token received in the OTP process
     * @return array|object|null
     */
    private function verifyOtp(string $type, string $otp, string $token): AuthResponse
    {
        $fields = [
            'body' => [
                'type' => $type,
                'token' => $token,
                $type => $otp,
            ],
        ];

        return $this->buildAuthResponse(
            $this->doPostRequest('verify', $fields)
        );
    }


    private function buildAuthResponse(array $response): mixed
    {
        return new AuthResponse($response);
    }

    private function buildUserResponse(array $response): mixed
    {
        return new UserResponse($response);
    }
    
}
