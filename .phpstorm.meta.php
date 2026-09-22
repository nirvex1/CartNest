<?php
/**
 * PhpStorm Meta File for Laravel Eloquent
 * This file helps the IDE understand Eloquent's dynamic query builder methods
 */

namespace PHPSTORM_META {
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Database\Eloquent\Model;
    
    // Tell PhpStorm that these methods exist on Builder and Model
    expectedReturnValues(
        \Illuminate\Database\Eloquent\Builder::where(),
        \Illuminate\Database\Eloquent\Builder::class
    );
    
    expectedReturnValues(
        \Illuminate\Database\Eloquent\Builder::query(),
        \Illuminate\Database\Eloquent\Builder::class
    );
    
    expectedReturnValues(
        \Illuminate\Database\Eloquent\Builder::with(),
        \Illuminate\Database\Eloquent\Builder::class
    );
    
    expectedReturnValues(
        \Illuminate\Database\Eloquent\Builder::orderBy(),
        \Illuminate\Database\Eloquent\Builder::class
    );

    // Override properties that are treated as function calls
    override(
        \Illuminate\Database\Eloquent\Builder::where(),
        map([
            'email' => \Illuminate\Database\Eloquent\Builder::class,
            'phone' => \Illuminate\Database\Eloquent\Builder::class,
            'payment_status' => \Illuminate\Database\Eloquent\Builder::class,
            'created_at' => \Illuminate\Database\Eloquent\Builder::class,
            'reset_token' => \Illuminate\Database\Eloquent\Builder::class,
            'reset_token_expires_at' => \Illuminate\Database\Eloquent\Builder::class,
            'reset_code' => \Illuminate\Database\Eloquent\Builder::class,
            'reset_code_expires_at' => \Illuminate\Database\Eloquent\Builder::class,
        ])
    );
}
