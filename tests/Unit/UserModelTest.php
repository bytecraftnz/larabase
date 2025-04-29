<?php

it('should create a new user model', function () {

    $user = new \Bytecraftnz\Larabase\Models\User([
        'id' => '123',
        'email' => 'valid@valid.com',
    ]);
    
    expect($user->id)->toBe('123');

});

it('should cast user meta data to UserMetadata', function () {

    $user = new \Bytecraftnz\Larabase\Models\User([
        'user_metadata' => [
            'email' => 'valid@valid.com',
            'email_verified' => false,
            'phone_verified' => false,
            'sub' => '1',
    
        ],
    ]);

    expect($user->user_metadata)->toBeInstanceOf(\Bytecraftnz\Larabase\Models\UserMetadata::class);
    expect($user->user_metadata->email)->toBe('valid@valid.com');

});


it('should cast confirmation_sent_at to date', function () {

    $user = new \Bytecraftnz\Larabase\Models\User([
        'confirmation_sent_at' => '2023-10-01 12:00:00',
    ]);
    
    expect($user->confirmation_sent_at->format('d/m/Y'))->toBe('01/10/2023');

});
