<?php


it('should transform the response to AuthResponse', function () {
    $response = [
        'access_token' => 'access_token',
        'refresh_token' => 'refresh_token',
        'expires_in' => 3600, 
        'token_type' => 'bearer',
        'expires_at' => '2023-10-01 12:00:00',
        'user' => [
            'id' => '123',
            'email' => ''
        ]
    ];
    
    $authResponse = new \Bytecraftnz\Larabase\Responses\AuthResponse($response);
    expect($authResponse->user_access->access_token)->toBe('access_token');
    expect($authResponse->user->id)->toBe('123');

});