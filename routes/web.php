<?php

use Livewire\Volt\Volt;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home');

Volt::route('/parties/{listeningParty}', 'pages.parties.show')->name('parties.show');

require __DIR__.'/auth.php';
