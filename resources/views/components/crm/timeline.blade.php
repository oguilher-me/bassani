@props(['interactions'])

<ul class="timeline">
    @forelse($interactions as $interaction)
        <li class="timeline-item timeline-item-transparent">
            <span class="timeline-point timeline-point-{{ 
                $interaction->type == 'call' ? 'primary' : 
                ($interaction->type == 'email' ? 'warning' : 
                ($interaction->type == 'meeting' ? 'info' : 'success')) 
            }}"></span>
            <div class="timeline-event">
                <div class="timeline-header mb-1">
                    <h6 class="mb-0 text-capitalize">{{ $interaction->type }}</h6>
                    <small class="text-muted">{{ $interaction->interaction_date->format('M d, Y h:i A') }}</small>
                </div>
                <div class="d-flex justify-content-between flex-wrap mb-2">
                    <div class="d-flex align-items-center">
                        <span class="text-muted small">Registrado por: {{ $interaction->user->name }}</span>
                    </div>
                    <div class="d-flex align-items-center">
                         <form action="{{ route('interactions.destroy', $interaction->id) }}" method="POST" onsubmit="return confirm('Excluir esta atividade?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-icon text-muted"><i class="bx bx-trash"></i></button>
                        </form>
                    </div>
                </div>
                <p class="mb-2">
                    {{ $interaction->summary }}
                </p>
            </div>
        </li>
    @empty
        <li class="timeline-item timeline-item-transparent">
            <span class="timeline-point timeline-point-secondary"></span>
            <div class="timeline-event">
                <div class="timeline-header mb-1">
                    <h6 class="mb-0">Nenhuma interação registrada</h6>
                </div>
                <p class="mb-2">Comece a interagir com este lead!</p>
            </div>
        </li>
    @endforelse
</ul>
