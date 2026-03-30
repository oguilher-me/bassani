@extends('layouts/contentNavbarLayout')

@section('title', __('Agendar Montagem'))

@section('content')
{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">{{ __('Agendar Montagem') }}</h4>
        <p class="text-muted mb-0">{{ __('Vendas / Detalhes / Agendar Montagem') }}</p>
    </div>
    <a href="{{ route('sales.show', $sale->id) }}" class="btn btn-sm btn-outline-secondary">
        <i class="bx bx-arrow-back me-1"></i> {{ __('Voltar') }}
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent py-3">
        <h6 class="mb-0 fw-semibold">
            <i class="bx bx-calendar text-danger me-2"></i>{{ __('Agendamento de Montagem para Venda') }} <span class="text-danger">#{{ $sale->erp_code }}</span>
        </h6>
    </div>
    <div class="card-body">
        <form id="assemblyScheduleForm" action="{{ route('assembly-schedules.store') }}" method="POST">
            @csrf
            <input type="hidden" name="sale_id" value="{{ $sale->id }}">
            
            <div class="row">
                <div class="mb-3 col-md-4">
                    <label for="scheduled_date" class="form-label">{{ __('Data da Montagem') }} <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="scheduled_date" name="scheduled_date" required>
                </div>
                <div class="mb-3 col-md-4">
                    <label for="start_time" class="form-label">{{ __('Hora de Início') }} <span class="text-danger">*</span></label>
                    <input type="time" class="form-control" id="start_time" name="start_time" required>
                </div>
                <div class="mb-3 col-md-4">
                    <label for="estimated_duration" class="form-label">{{ __('Duração Estimada (horas)') }} <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" id="estimated_duration" name="estimated_duration" min="0.5" step="0.5" required>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="notes" class="form-label">{{ __('Observações') }}</label>
                <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-header bg-transparent py-3">
                            <h6 class="mb-0 fw-semibold">
                                <i class="bx bx-user-plus text-primary me-2"></i>{{ __('Montadores Disponíveis') }}
                            </h6>
                        </div>
                        <div class="card-body" id="available-assemblers">
                            <div class="text-center py-3">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">{{ __('Carregando...') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-header bg-transparent py-3">
                            <h6 class="mb-0 fw-semibold">
                                <i class="bx bx-user-check text-success me-2"></i>{{ __('Montadores Selecionados') }}
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row" id="selected-assemblers">
                                <div class="col-12 text-center py-3">
                                    <p class="text-muted mb-0 small">{{ __('Arraste os montadores aqui') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div id="assembler_commissions" class="mb-3" style="display: none;">
            </div>
            
            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('sales.show', $sale->id) }}" class="btn btn-sm btn-outline-secondary">{{ __('Cancelar') }}</a>
                <button type="submit" class="btn btn-sm btn-primary">
                    <i class="bx bx-save me-1"></i> {{ __('Salvar Agendamento') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

 
@section('page-script')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>
    <script>
        $(document).ready(function() {
            const availableAssemblersDiv = $('#available-assemblers');
            const selectedAssemblersDiv = $('#selected-assemblers');
            const assemblerCommissionsDiv = $('#assembler_commissions');

            new Sortable(selectedAssemblersDiv[0], {
                group: 'assemblers',
                animation: 150,
                onAdd: function (evt) {
                    const item = $(evt.item);
                    const assemblerId = item.data('id');
                    const assemblerName = item.data('name');
                    addCommissionInput(assemblerId, assemblerName);
                    
                    if (selectedAssemblersDiv.children('.draggable-item').length > 0) {
                        selectedAssemblersDiv.find('.text-center').remove();
                    }
                },
                onRemove: function (evt) {
                    const item = $(evt.item);
                    const assemblerId = item.data('id');
                    removeCommissionInput(assemblerId);
                    
                    if (selectedAssemblersDiv.children('.draggable-item').length === 0) {
                        selectedAssemblersDiv.append('<div class="col-12 text-center py-3"><p class="text-muted mb-0 small">Arraste os montadores aqui</p></div>');
                    }
                }
            });

            function addCommissionInput(id, name) {
                const commissionInputHtml = `
                    <div class="mb-3 p-3 border rounded bg-light" id="commission-input-${id}">
                        <label for="commission-${id}" class="form-label fw-semibold small">{{ __('Comissão para') }} ${name}</label>
                        <div class="input-group">
                            <span class="input-group-text">R$</span>
                            <input type="number" class="form-control" id="commission-${id}" name="commissions[${id}]" step="0.01" value="0.00">
                        </div>
                    </div>
                `;
                assemblerCommissionsDiv.append(commissionInputHtml);
                assemblerCommissionsDiv.show();
            }

            function removeCommissionInput(id) {
                $(`#commission-input-${id}`).remove();
                if (selectedAssemblersDiv.children('.draggable-item').length === 0) {
                    assemblerCommissionsDiv.hide();
                }
            }

            $.ajax({
                url: "{{ route('assemblers.available') }}",
                method: 'GET',
                success: function(response) {
                    availableAssemblersDiv.empty();
                    const rowDiv = $('<div class="row g-2"></div>');
                    response.results.forEach(function(assembler) {
                        const assemblerCard = `
                            <div class="col-md-6 col-lg-4 draggable-item assembler-card-container" data-id="${assembler.id}" data-name="${assembler.text}">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body p-2 d-flex align-items-center">
                                        ${assembler.photo ? `<img src="/storage/${assembler.photo}" alt="${assembler.text}" class="rounded-circle me-2" style="width: 40px; height: 40px; object-fit: cover;">` : '<div class="avatar rounded-circle bg-label-secondary d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;"><i class="bx bx-user"></i></div>'}
                                        <p class="card-text mb-0 small fw-semibold" style="overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">${assembler.text}</p>
                                    </div>
                                </div>
                            </div>
                        `;
                        rowDiv.append(assemblerCard);
                    });
                    availableAssemblersDiv.append(rowDiv);

                    new Sortable(rowDiv[0], {
                        group: 'assemblers',
                        animation: 150,
                        draggable: '.draggable-item'
                    });
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error Response:", xhr.responseText);
                    console.error("Error fetching assemblers:", error);
                    availableAssemblersDiv.html('<p class="text-danger small">Erro ao carregar montadores.</p>');
                }
            });

            $('#assemblyScheduleForm').on('submit', function(e) {
                e.preventDefault();

                const form = $(this);
                const formData = new FormData(form[0]);

                const scheduledDate = $('#scheduled_date').val();
                const startTime = $('#start_time').val();
                const estimatedDuration = parseFloat($('#estimated_duration').val());

                if (!scheduledDate || !startTime || isNaN(estimatedDuration)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro!',
                        text: 'Por favor, preencha a data, hora de início e duração estimada.',
                        confirmButtonColor: '#DE0802'
                    });
                    return;
                }

                const startDateTime = new Date(`${scheduledDate}T${startTime}:00`);
                const endDateTime = new Date(startDateTime.getTime() + (estimatedDuration * 60 * 60 * 1000));

                const endHours = String(endDateTime.getHours()).padStart(2, '0');
                const endMinutes = String(endDateTime.getMinutes()).padStart(2, '0');
                const endTime = `${endHours}:${endMinutes}`;

                formData.append('start_time', startTime);
                formData.append('end_time', endTime);

                const selectedAssemblerIds = [];
                selectedAssemblersDiv.children('.draggable-item').each(function() {
                    selectedAssemblerIds.push($(this).data('id'));
                });
                selectedAssemblerIds.forEach(function(id) {
                    formData.append('assemblers[]', id);
                });

                assemblerCommissionsDiv.find('input[name^="commissions"]').each(function() {
                    formData.append($(this).attr('name'), $(this).val());
                });

                $.ajax({
                    url: form.attr('action'),
                    method: form.attr('method'),
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        console.log("Form Submission Success:", response);
                        if (response.redirect) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Sucesso!',
                                text: response.message || 'Agendamento salvo com sucesso!',
                                confirmButtonColor: '#DE0802'
                            }).then(() => {
                                window.location.href = response.redirect;
                            });
                        } 
                    },
                    error: function(xhr) {
                        console.error("Form Submission Error:", xhr.responseText);
                        const errors = xhr.responseJSON?.errors;
                        let errorMessage = 'Erro ao salvar agendamento:';
                        if (errors) {
                            for (const key in errors) {
                                if (errors.hasOwnProperty(key)) {
                                    errorMessage += `<br>- ${errors[key][0]}`;
                                }
                            }
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Erro!',
                            html: errorMessage,
                            confirmButtonColor: '#DE0802'
                        });
                    }
                });
            });
        });
    </script>
@endsection
