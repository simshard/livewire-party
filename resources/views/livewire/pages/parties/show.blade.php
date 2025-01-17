<?php

use Livewire\Volt\Component;
use App\Models\ListeningParty;
use Livewire\Attributes\Validate;

new class extends Component {
    public ListeningParty $listeningParty;

    public function mount(ListeningParty $listeningParty)
    {
        $this->listeningParty = $listeningParty;
    }
 }
?>

<div>
     
   <h1 class="font-bold">{{$listeningParty->name}}   </h1> 
   <p> {{ $listeningParty->start_time}}  </p>
    <p>{{$listeningParty->episode}}</p>
</div>
