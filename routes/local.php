<?php

use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function (){

});

Route::middleware('api')->group(function (){
    Route::get('post-content', function (){
        return \App\Support\PostFixture::getFixture()->random();
    });
});
