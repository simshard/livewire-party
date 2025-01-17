<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Validate;
use App\Models\ListeningParty;
use App\Models\Episode;
use App\Jobs\ProcessPodcastUrl;

new class extends Component {

     #[Validate('required|string|max:255')]
    public string $name = '';
    #[Validate('required')]
    public $startTime;
    #[Validate('required|url')]
    public string $mediaUrl = '';
   //public string $thing= '';

    public function createListeningParty()
    {
       $this->validate();
        $episode = Episode::create([
            'media_url' => $this->mediaUrl
        ]);

        $listeningParty = ListeningParty::create([
            'episode_id' => $episode->id,
            'name' => $this->name,
            'start_time' => $this->startTime,
        ]);
       // dd($this->name,$this->startTime,$this->mediaUrl);
        ProcessPodcastUrl::dispatch($this->mediaUrl,$listeningParty,$episode);

        
     //  
          return redirect()->route('parties.show', $listeningParty);

    }
  
    public function with()
    {
        return [
           'listeningParties' => ListeningParty::where('is_active',true)->with('episode.podcast')->orderBy('start_time', 'asc')-> get(),
        ];
        // dd($this->listeningParties);
    }

}; ?>


<div class="  min-h-screen bg-indigo-50 pt-8">
    {{-- ToP Listening party create- form --}}
    <div class="flex flex-col items-center justify-center px-4 ">
       <div class="w-full max-w-lg">
        <x-card shadow="lg" rounded="lg">
        <h2 class="text-2xl font-bold text-center mb-6">
            Create Listening Party
        </h2>
        <form wire:submit='createListeningParty'class="space-y-6">
            <x-input wire:model='name' placeholder="Listening Party Name"/>
            <x-datetime-picker wire:model='startTime' placeholder='Start Time'  :min="now()->subDays(1)" /> 
            <x-input wire:model='mediaUrl' placeholder='Podcast Episode URL' description='Entering the RSS Feed URL will grab the latest episode'></x-input>
            <x-button class="w-full" type="submit">Create Listening Party</x-button>
        </form>
       </x-card>
      </div>
    </div>
    {{-- bottom - Listening Parties --}}
<div class="max-w-lg mx-auto">
@if ($listeningParties->isEmpty())
    <div>No Listening Parties</div>
@else
 
<div class="bg-white rounded-lg shadow overflow-hidden">
    @foreach ($listeningParties as $listeningParty)
    <a href="{{ route('parties.show', $listeningParty) }}" class="block">
        <div
        class="flex items-center justify-between p-4 border-b border-gray-200 hover:bg-gray-50 transition duration-150 ease-in-out">
        <div  wire:key={{$listeningParty->id }} class="flex items-center space-x-4">
    
         <x-avatar src="{{$listeningParty->episode->podcast->artwork_url }}" size ="xl" rounded="full"/>  {{-- --}}
          <p>{{$listeningParty->name}}</p>

        <p>   {{$listeningParty->episode->title}}</p>
        <p>{{$listeningParty->start_time}}</p>
        <p>{{$listeningParty->episode->podcast->artwork_url }}</p>
        <p>{{$listeningParty->episode->podcast->title }}</p>
    </div>
</div>
</a>
    @endforeach
@endif
</div>
</div>

</div>
