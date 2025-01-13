<?php

use Livewire\Volt\Volt;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home');

Volt::route('/parties/{listeningParty}', 'pages.listening-parties.show');

require __DIR__.'/auth.php';
