<?php


describe('AuthClientTest', function () {
    $testUserEmail = 'test@test.com';
    $testUserPassword = 'testpassword';
    $testUserData = [
        'name' => 'Test User',
    ];
    $authClient = new \Bytecraftnz\Larabase\Clients\AuthClient(
        env('SUPABASE_URL'),
        env('SUPABASE_KEY'),
        new \GuzzleHttp\Client
    );


    it('should be true', function () {
        expect(true)->toBeTrue();
    }); 


    it('should return Unable to validate email address: invalid format when reset password from email called with invalid email', function() use ($authClient) {
        $response = $authClient->resetPasswordForEmail('@email.com', []);
        expect($response)->toBeInstanceOf(\Bytecraftnz\Larabase\Models\AuthError::class);
        expect($response->status)->toBe(400);
        expect($response->message)->toBe('Unable to validate email address: invalid format');
    });


    it('should return token has expired when Opt Via email', function() use($authClient){
        $response = $authClient->verifyOtpViaEmail(
            'email@email.com',
            'testpassword'
        );

        expect($response)->toBeInstanceOf(\Bytecraftnz\Larabase\Responses\AuthResponse::class);
        expect($response->hasError())->toBeTrue();
        expect($response->error->status)->toBe(403);
        expect($response->error->message)->toBe('Token has expired or is invalid');
    

    })->skip();

    it('should return token has expired when Opt via Phone', function() use($authClient){
        $response = $authClient->verifyOtpViaPhone(
            '1234567890',
            'testpassword'
        );

        expect($response)->toBeInstanceOf(\Bytecraftnz\Larabase\Responses\AuthResponse::class);
        expect($response->hasError())->toBeTrue();
        expect($response->error->status)->toBe(403);
        expect($response->error->message)->toBe('Token has expired or is invalid');
    

    })->skip();


    it('should create a user with phone and password', function() use($authClient){
        $response = $authClient->signUpWithPhoneAndPassword(
            '1234567890',
            'testpassword'
        );
        expect($response)->toBeInstanceOf(\Bytecraftnz\Larabase\Responses\AuthResponse::class);
        expect($response->user->phone)->toBe('1234567890');
        expect($response->user->isPhoneVerified())->toBeTrue();
        expect($response->user_access->access_token)->not()->toBeNull();

    })->skip();

    it('should have error 422 user already exists', function() use($authClient){
        $response = $authClient->signUpWithPhoneAndPassword(
            '1234567890',
            'testpassword'
        );
        
        expect($response)->toBeInstanceOf(\Bytecraftnz\Larabase\Responses\AuthResponse::class);
        expect($response->hasError())->toBeTrue();
        expect($response->error->status)->toBe(422);
        expect($response->error->message)->toBe('User already registered');
    })->skip();



    it('should create a new user with email and password', 
        function () use ($authClient, $testUserEmail, $testUserPassword, $testUserData) {
            $response = $authClient->signUpWithEmailAndPassword(
                $testUserEmail,
                $testUserPassword,
                $testUserData
            );
            expect($response)->toBeInstanceOf(\Bytecraftnz\Larabase\Responses\AuthResponse::class);
            expect($response->user->isEmailVerified())->toBeTrue();
            expect($response->user->user_metadata->email)->toBe($testUserEmail);
            expect($response->user->user_metadata->name)->toBe($testUserData['name']);

            expect($response->user_access->access_token)->not()->toBeNull();            
    })->skip();


    it('should return null when user signs out', function() use ($authClient, $testUserEmail, $testUserPassword) {
        $response = $authClient->signInWithEmailAndPassword(
            $testUserEmail,
            $testUserPassword
        );
        expect($response)->toBeInstanceOf(\Bytecraftnz\Larabase\Responses\AuthResponse::class);
        expect($response->user_access->access_token)->not()->toBeNull();

        $signOutResponse = $authClient->signOut($response->user_access->access_token);
        expect($signOutResponse)->toBeNull();
    })->skip();



    it('should return error 422 when user already exists', 
        function () use ($authClient, $testUserEmail, $testUserPassword, $testUserData) {
            $response = $authClient->signUpWithEmailAndPassword(
                $testUserEmail,
                $testUserPassword,
                $testUserData
            );
            expect($response)->toBeInstanceOf(\Bytecraftnz\Larabase\Responses\AuthResponse::class);
            expect($response->hasError())->toBeTrue();
            expect($response->error->status)->toBe(422);
            expect($response->error->message)->toBe('User already registered');
            
    })->skip();


    it('should have an error in the response when no email or password passed in with status of 400', function () use ($authClient) {
        $response = $authClient->signInWithEmailAndPassword(
            '',
            ''
        );
        expect($response)->toBeInstanceOf(\Bytecraftnz\Larabase\Responses\AuthResponse::class);
        expect($response->hasError())->toBeTrue();
        expect($response->error->status)->toBe(400);
    })->skip();

    it('should have an error in the response when invalid email and password with message "Invalid login credentials" ', function () use ($authClient) {
        $response = $authClient->signInWithEmailAndPassword(
            'invalid@email.com',
            'invalidpassword'
        );
        expect($response)->toBeInstanceOf(\Bytecraftnz\Larabase\Responses\AuthResponse::class);
        expect($response->hasError())->toBeTrue();
        expect($response->error->message)->toBe('Invalid login credentials');        
    })->skip();

    it('should return 200 with valid email and password', function () use ($authClient, $testUserEmail, $testUserPassword) {
        $response = $authClient->signInWithEmailAndPassword(
            $testUserEmail,
            $testUserPassword
        );

        expect($response)->toBeInstanceOf(\Bytecraftnz\Larabase\Responses\AuthResponse::class);
        expect($response->user->email)->toBe($testUserEmail);
        expect($response->user_access->access_token)->not()->toBeNull();
    })->skip();


});