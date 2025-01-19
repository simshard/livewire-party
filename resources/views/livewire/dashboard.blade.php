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
           'listeningParties' => ListeningParty::where('is_active',true)->whereNotNull('end_time')->with('episode.podcast')->orderBy('start_time', 'asc')-> get(),
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
<div class="bg-white rounded-lg shadow  mb-5">
    <h3 class="mt-2 mb-6 ml-4 text-lg underline underline-offset-8">Listening Parties</h3>
@if ($listeningParties->isEmpty())
    <div>No Listening Parties</div>
@else
 

    @foreach ($listeningParties as $listeningParty)
    <a href="{{ route('parties.show', $listeningParty) }}" class="block">
        <div
        class="flex items-center justify-between p-4 border-b border-indigo-400 hover:bg-indigo-50 transition duration-150 ease-in-out">
        <div  wire:key={{$listeningParty->id }} class="flex items-center space-x-4">
    
        <div class="flex-shrink-0">
            <img src="{{ $listeningParty->episode->podcast->artwork_url }}"
                    class="w-20 h-20 rounded-lg" alt="Podcast Artwork" />
        </div>

        <div class="flex-1 min-w-0">
            <div class="text-sm font-medium text-gray-900 truncate">
            {{ $listeningParty->name }}
            </div>
            <div class="text-sm max-w-sm text-green-700 truncate">
                {{ $listeningParty->episode->title }}
            </div>
            <div class="text-xs text-indigo-400 truncate">
                {{ $listeningParty->episode->podcast->title }}
            </div>

            <div class="text-xs text-gray-500 mt-1"
            <div class="text-xs text-gray-500 mt-1"
            x-data="{
                startTime: '{{ $listeningParty->start_time->toIso8601String() }}',
                countdownText: '',
                updateCountdown() {
                    const start = new Date(this.startTime).getTime();
                    const now = new Date().getTime();
                    const distance = start - now;

                    if (distance < 0) {
                        this.countdownText = 'Started';
                    } else {
                        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                        this.countdownText = `${days}d ${hours}h ${minutes}m `;
                    }
                }
            }"
                x-init="updateCountdown();
                setInterval(() => updateCountdown(), 60000);"
            >
                Starts in: <span x-text="countdownText"></span>
            </div>
    </div> 
  </div>
  <x-button flat class="ml-4">Join</x-button>
</div>
</a>
    @endforeach
@endif
</div>
</div>

</div>
