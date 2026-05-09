<?php
namespace App\Livewire;

use App\Models\Campaign;
use Livewire\Component;
use Livewire\Attributes\Poll;

class CampaignProgress extends Component
{
    public Campaign $campaign;
    public float $raised;
    public float $goal;
    public float $percentage;
    public int $donorCount;

    public function mount(Campaign $campaign): void
    {
        $this->campaign   = $campaign;
        $this->refresh();
    }

    // Auto-refresh every 15 seconds without page reload
    #[Poll(15000)]
    public function refresh(): void
    {
        $this->campaign->refresh();
        $this->raised     = $this->campaign->raised_amount;
        $this->goal       = $this->campaign->goal_amount;
        $this->percentage = $this->campaign->progress_percentage;
        $this->donorCount = $this->campaign->donations()->where('status', 'completed')->count();
    }

    public function render()
    {
        return view('livewire.campaign-progress');
    }
}