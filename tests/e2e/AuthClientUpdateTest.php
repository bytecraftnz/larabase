<?php





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
    
    
    // should update the name of the user 
    it('should update the name of the user', function() use($authClient, $testUserEmail, $testUserPassword, $testUserData) {

        $response = $authClient->signUpWithEmailAndPassword(
            $testUserEmail,
            $testUserPassword,
            $testUserData
        );


        $response = $authClient->updateUser(
            $response->user_access->access_token,
            [                
                'data' => [
                    'name' => 'Updated User'
                ],
            ]
        );
        
        expect($response)->toBeInstanceOf(\Bytecraftnz\Larabase\Responses\UserResponse::class);
        expect($response->user->user_metadata->data['name'])->toBe('Updated User');

    })->skip();

    it('should update email address for a user', function() use($authClient, $testUserEmail, $testUserPassword, $testUserData) {

        $response = $authClient->signUpWithEmailAndPassword(
            $testUserEmail,
            $testUserPassword,
            $testUserData
        );


        $response = $authClient->updateUserEmail(
            $response->user_access->access_token,
            'mynewemail@somewhere.com'
        );
        
        expect($response)->toBeInstanceOf(\Bytecraftnz\Larabase\Responses\UserResponse::class);
        expect($response->user->new_email)->toBe('mynewemail@somewhere.com');

    })->skip();


    it('should update password for a user', function() use($authClient, $testUserEmail, $testUserPassword, $testUserData) {

        $response = $authClient->signUpWithEmailAndPassword(
            $testUserEmail,
            $testUserPassword,
            $testUserData
        );


        $response = $authClient->updateUserPassword(
            $response->user_access->access_token,
            'mynewpassword'
        );
        

        $response = $authClient->signInWithEmailAndPassword(
            $testUserEmail,
            'mynewpassword'
        );
        expect($response)->toBeInstanceOf(\Bytecraftnz\Larabase\Responses\AuthResponse::class);
        expect($response->user_access->access_token)->not()->toBeNull();
    })->skip();


    

