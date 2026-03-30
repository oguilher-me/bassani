@extends('assembler.layout')

@section('title', 'Meu Ponto - Bassani')

@section('content')
{{-- Header --}}
<div class="app-header">
    <div class="d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ url('/assembler/home') }}" class="text-white">
                <i class="bx bx-arrow-back fs-4"></i>
            </a>
            <div>
                <div class="greeting">Meu Ponto</div>
                <div class="user-name small opacity-75">{{ date('d/m/Y') }}</div>
            </div>
        </div>
    </div>
</div>

{{-- Digital Clock --}}
<div class="text-center py-4" style="background: linear-gradient(135deg, var(--bassani-navy) 0%, #2a3a5c 100%); color: white;">
    <div id="digitalClock" class="display-1 fw-bold" style="font-family: 'Courier New', monospace; letter-spacing: 4px;">
        00:00:00
    </div>
    <div id="clockDate" class="small opacity-75 mt-1">
        {{ now()->translatedFormat('l, d \d\e F \d\e Y') }}
    </div>
</div>

{{-- Working Hours Summary --}}
<div class="stats-container" style="padding: 16px;">
    <div class="stat-card">
        <div class="stat-icon bg-label-success text-success">
            <i class="bx bx-time-five"></i>
        </div>
        <div class="stat-value" id="workedHours">{{ $workedHours['worked_formatted'] }}</div>
        <div class="stat-label">Horas Trabalhadas</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon bg-label-warning text-warning">
            <i class="bx bx-pause-circle"></i>
        </div>
        <div class="stat-value" id="pausedHours">{{ $workedHours['paused_formatted'] }}</div>
        <div class="stat-label">Em Pausa</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon bg-label-primary text-primary">
            <i class="bx bx-check-circle"></i>
        </div>
        <div class="stat-value" id="netHours">{{ $workedHours['net_formatted'] }}</div>
        <div class="stat-label">Líquido</div>
    </div>
</div>

{{-- Clock Action Button --}}
<div class="px-3 py-2">
    @php
        $currentType = $nextValidTypes[0] ?? 'start';
        $buttonConfig = [
            'start' => [
                'icon' => 'bx-play-circle',
                'label' => 'Iniciar Expediente',
                'color' => 'success',
                'gradient' => 'linear-gradient(135deg, #28a745 0%, #1e7e34 100%)'
            ],
            'pause' => [
                'icon' => 'bx-pause-circle',
                'label' => 'Pausar Expediente',
                'color' => 'warning',
                'gradient' => 'linear-gradient(135deg, #ffc107 0%, #e0a800 100%)'
            ],
            'resume' => [
                'icon' => 'bx-play-circle',
                'label' => 'Retornar',
                'color' => 'info',
                'gradient' => 'linear-gradient(135deg, #17a2b8 0%, #117a8b 100%)'
            ],
            'end' => [
                'icon' => 'bx-power-off',
                'label' => 'Encerrar Expediente',
                'color' => 'danger',
                'gradient' => 'linear-gradient(135deg, #DE0802 0%, #B3211A 100%)'
            ]
        ];
        $config = $buttonConfig[$currentType];
    @endphp

    <button type="button" id="clockActionBtn" class="btn w-100 py-3 fs-5 fw-bold d-flex align-items-center justify-content-center gap-2" 
            style="background: {{ $config['gradient'] }}; color: white; border: none; border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.2);"
            data-type="{{ $currentType }}">
        <i class="bx {{ $config['icon'] }} fs-3"></i>
        <span>{{ $config['label'] }}</span>
    </button>
    
    <div class="text-center mt-2">
        <small class="text-muted">
            <i class="bx bx-map-pin me-1"></i>
            <span id="locationStatus">Localização será capturada automaticamente</span>
        </small>
    </div>
</div>

{{-- Today's History --}}
<div class="section-title">
    <i class="bx bx-history me-1 text-danger"></i> Registros de Hoje
</div>

<div id="clockHistory">
    @if($todayClocks->isEmpty())
        <div class="empty-state">
            <i class="bx bx-time-five"></i>
            <p>Nenhum registro hoje</p>
        </div>
    @else
        @foreach($todayClocks as $clock)
        <div class="expense-card">
            <div class="expense-icon bg-label-{{ $clock->type_color }} text-{{ $clock->type_color }}">
                <i class="bx {{ $clock->type === 'start' ? 'bx-play-circle' : ($clock->type === 'pause' ? 'bx-pause-circle' : ($clock->type === 'resume' ? 'bx-play-circle' : ($clock->type === 'end' ? 'bx-power-off' : 'bx-time'))) }}"></i>
            </div>
            <div class="expense-details">
                <div class="expense-category">{{ $clock->type_label }}</div>
                <div class="expense-date">
                    <i class="bx bx-time-five me-1"></i>
                    {{ $clock->clock_in_at->format('H:i:s') }}
                </div>
            </div>
            <div class="text-end">
                <span class="badge bg-label-{{ $clock->type_color }} rounded-pill px-2 py-1">
                    {{ $clock->type_label }}
                </span>
            </div>
        </div>
        @endforeach
    @endif
</div>

{{-- GPS Info --}}
<div class="px-3 py-3">
    <div class="form-card" style="margin: 0; font-size: 0.8rem;">
        <div class="d-flex align-items-center text-muted">
            <i class="bx bx-gps me-2"></i>
            <span id="gpsInfo">Aguardando GPS...</span>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Digital Clock
function updateClock() {
    const now = new Date();
    const timeString = now.toLocaleTimeString('pt-BR', { hour12: false });
    document.getElementById('digitalClock').textContent = timeString;
}
setInterval(updateClock, 1000);
updateClock();

// GPS Location
let currentLat = null;
let currentLng = null;

function updateGPSStatus() {
    const gpsInfo = document.getElementById('gpsInfo');
    const locationStatus = document.getElementById('locationStatus');
    
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (position) => {
                currentLat = position.coords.latitude;
                currentLng = position.coords.longitude;
                gpsInfo.innerHTML = `<i class="bx bx-check-circle text-success me-1"></i>Lat: ${currentLat.toFixed(6)}, Lng: ${currentLng.toFixed(6)}`;
                locationStatus.textContent = 'Localização capturada';
            },
            (error) => {
                gpsInfo.innerHTML = `<i class="bx bx-error-circle text-warning me-1"></i>GPS indisponível`;
                locationStatus.textContent = 'GPS indisponível';
            },
            { enableHighAccuracy: true, timeout: 10000 }
        );
    } else {
        gpsInfo.innerHTML = `<i class="bx bx-error-circle text-danger me-1"></i>GPS não suportado`;
    }
}

// Get GPS on load
updateGPSStatus();
// Refresh GPS every 30 seconds
setInterval(updateGPSStatus, 30000);

// Clock Action
document.getElementById('clockActionBtn').addEventListener('click', function() {
    const btn = this;
    const type = btn.dataset.type;
    
    // Haptic feedback
    if (navigator.vibrate) navigator.vibrate(100);
    
    // Disable button
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Registrando...';
    
    // Prepare data
    const data = {
        type: type,
        latitude: currentLat,
        longitude: currentLng,
        _token: '{{ csrf_token() }}'
    };
    
    // Send request
    fetch('{{ route("meu-ponto.store") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Success feedback
            if (navigator.vibrate) navigator.vibrate([100, 50, 100]);
            
            // Show success toast
            showToast(data.message, 'success');
            
            // Refresh page to update UI
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            showToast(data.message || 'Erro ao registrar ponto', 'error');
            resetButton(btn, type);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Erro de conexão. Tente novamente.', 'error');
        resetButton(btn, type);
    });
});

function resetButton(btn, type) {
    const buttonConfig = {
        'start': { icon: 'bx-play-circle', label: 'Iniciar Expediente' },
        'pause': { icon: 'bx-pause-circle', label: 'Pausar Expediente' },
        'resume': { icon: 'bx-play-circle', label: 'Retornar' },
        'end': { icon: 'bx-power-off', label: 'Encerrar Expediente' }
    };
    const config = buttonConfig[type];
    
    btn.disabled = false;
    btn.innerHTML = `<i class="bx ${config.icon} fs-3"></i><span>${config.label}</span>`;
}

function showToast(message, type) {
    const container = document.getElementById('toastContainer');
    const icons = {
        success: 'bx-check-circle',
        error: 'bx-x-circle',
        warning: 'bx-error-circle'
    };
    
    const toast = document.createElement('div');
    toast.className = `custom-toast ${type}`;
    toast.innerHTML = `
        <i class="bx ${icons[type]}" style="color: ${type === 'success' ? '#28a745' : type === 'error' ? '#dc3545' : '#ffc107'}"></i>
        <span style="font-size: 0.9rem;">${message}</span>
    `;
    
    container.appendChild(toast);
    
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(-20px)';
        toast.style.transition = 'all 0.3s ease';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}
</script>
@endsection
