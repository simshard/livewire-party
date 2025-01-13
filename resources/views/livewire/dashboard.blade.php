<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Validate;
use App\Models\ListeningParty;
use App\Models\Episode;

new class extends Component {

     #[Validate('required|string|max:255')]
    public string $name = '';
    #[Validate('required')]
    public $startTime;
    #[Validate('required|url')]
    public string $mediaUrl = '';
    public string $thing= '';
    public function createListeningParty()
    {
        //dd($this->name,$this->startTime,$this->mediaUrl); 
        $this->validate;
        $episode = Episode::create([
            'media_url' => $this->mediaUrl
        ]);

        $listeningParty = ListeningParty::create([
            'episode_id' => $episode->id,
            'name' => $this->name,
            'start_time' => $this->startTime,
        ]);

        return redirect()->route('listening-parties.show', $listeningParty);

    }
   //
    public function with()
    {
        return [
           'listeningParties' => ListeningParty::all(),
        ];
    }

}; ?>

<div class="flex items-center justify-center min-h-screen bg-indigo-50">
    <div class="max-w-lg w-ful px-4">
        <x-card shadow="lg" rounded="lg">
        <form wire:submit='createListeningParty'class="space-y-6">
            <x-input wire:model='name' placeholder="Listening Party Name"/>
            <x-datetime-picker wire:model='startTime' placeholder='Start Time'/> 
            <x-input wire:model='mediaUrl' placeholder='Podcast Episode URL' description='Entering the RSS Feed URL will grab the latest episode'></x-input>
            <x-button type="submit">Create Listening Party</x-button>
        </form>
    </x-card>
    </div>
</div>
