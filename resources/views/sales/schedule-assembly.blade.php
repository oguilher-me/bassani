@extends('layouts/contentNavbarLayout')

@section('title', 'Agendar Montagem')

@section('content')
    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">Vendas / Detalhes /</span> Agendar Montagem
    </h4>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Agendamento de Montagem para Venda #{{ $sale->erp_code }}</h5>
        </div>
        <div class="card-body">
            <form id="assemblyScheduleForm" action="{{ route('assembly-schedules.store') }}" method="POST">
                @csrf
                <input type="hidden" name="sale_id" value="{{ $sale->id }}">
                <div class="mb-3">
                    <label for="scheduled_date" class="form-label">Data da Montagem</label>
                    <input type="date" class="form-control" id="scheduled_date" name="scheduled_date" required>
                </div>
                <div class="mb-3">
                    <label for="start_time" class="form-label">Hora de Início da Montagem</label>
                    <input type="time" class="form-control" id="start_time" name="start_time" required>
                </div>
                <div class="mb-3">
                    <label for="estimated_duration" class="form-label">Duração Estimada (horas)</label>
                    <input type="number" class="form-control" id="estimated_duration" name="estimated_duration" min="0.5" step="0.5" required>
                </div>
                <div class="mb-3">
                    <label for="notes" class="form-label">Observações</label>
                    <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="card-header">Montadores Disponíveis</div>
                            <div class="card-body" id="available-assemblers">
                                <!-- Assembler cards will be loaded here -->
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="card-header">Montadores Selecionados</div>
                            <div class="card-body">
                                <div class="row" id="selected-assemblers">
                                    <!-- Selected assembler cards with commission input will be moved here -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="assembler_commissions" class="mb-3" style="display: none;">
                </div>
                <button type="submit" class="btn btn-primary">Salvar Agendamento</button>
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

            // Initialize SortableJS for selected assemblers
            new Sortable(selectedAssemblersDiv[0], {
                group: 'assemblers',
                animation: 150,
                onAdd: function (evt) {
                    const item = $(evt.item);
                    const assemblerId = item.data('id');
                    const assemblerName = item.data('name');
                    addCommissionInput(assemblerId, assemblerName);
                },
                onRemove: function (evt) {
                    const item = $(evt.item);
                    const assemblerId = item.data('id');
                    removeCommissionInput(assemblerId);
                }
            });

            // Function to add commission input
            function addCommissionInput(id, name) {
                const commissionInputHtml = `
                    <div class="mb-3" id="commission-input-${id}">
                        <label for="commission-${id}" class="form-label">Comissão para ${name}</label>
                        <input type="number" class="form-control" id="commission-${id}" name="commissions[${id}]" step="0.01" value="0.00">
                    </div>
                `;
                assemblerCommissionsDiv.append(commissionInputHtml);
                assemblerCommissionsDiv.show();
            }

            // Function to remove commission input
            function removeCommissionInput(id) {
                $(`#commission-input-${id}`).remove();
                if (selectedAssemblersDiv.children().length === 0) {
                    assemblerCommissionsDiv.hide();
                }
            }

            const routeUrl = '{{ route('assemblers.data') }}';
           

            $.ajax({
                url: "{{ route('assemblers.available') }}",
                method: 'GET',
                success: function(response) {
                   
                    availableAssemblersDiv.empty();
                    const rowDiv = $('<div class="row"></div>');
                    response.results.forEach(function(assembler) {
                        const assemblerCard = `
                            <div class="col-md-4 mb-3 draggable-item assembler-card-container " data-id="${assembler.id}" data-name="${assembler.text}">
                                <div class="card d-flex flex-column text-center ">
                                     <div class="card-body p-2 d-flex flex-column align-items-center assembler-card-container " style="flex-grow: 1;">
                                         ${assembler.photo ? `<img src="/storage/${assembler.photo}" alt="${assembler.text}" class="assembler-card-image">` : ''}
                                         <p class="card-text" style="overflow: hidden; white-space: nowrap; text-overflow: ellipsis; margin-top: auto; padding-bottom: 10px;">${assembler.text}</p>
                                     </div>
                                 </div>
                            </div>
                        `;
                        rowDiv.append(assemblerCard);
                    });
                    availableAssemblersDiv.append(rowDiv);

                    // Initialize SortableJS for available assemblers after they are loaded
                    new Sortable(rowDiv[0], {
                        group: 'assemblers',
                        animation: 150,
                        draggable: '.draggable-item'
                    });
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error Response:", xhr.responseText); // Adicionado para depuração
                    console.error("Error fetching assemblers:", error);
                }
            });

            $('#assemblyScheduleForm').on('submit', function(e) {
                e.preventDefault(); // Prevent default form submission

                const form = $(this);
                const formData = new FormData(form[0]);

                const scheduledDate = $('#scheduled_date').val();
                const startTime = $('#start_time').val();
                const estimatedDuration = parseFloat($('#estimated_duration').val());

                if (!scheduledDate || !startTime || isNaN(estimatedDuration)) {
                    Swal.fire('Erro!', 'Por favor, preencha a data, hora de início e duração estimada.', 'error');
                    return;
                }

                // Calculate end time
                const startDateTime = new Date(`${scheduledDate}T${startTime}:00`);
                const endDateTime = new Date(startDateTime.getTime() + (estimatedDuration * 60 * 60 * 1000));

                const endHours = String(endDateTime.getHours()).padStart(2, '0');
                const endMinutes = String(endDateTime.getMinutes()).padStart(2, '0');
                const endTime = `${endHours}:${endMinutes}`;

                formData.append('start_time', startTime);
                formData.append('end_time', endTime);

                // Add selected assembler IDs to formData
                const selectedAssemblerIds = [];
                selectedAssemblersDiv.children('.draggable-item').each(function() {
                    selectedAssemblerIds.push($(this).data('id'));
                });
                selectedAssemblerIds.forEach(function(id) {
                    formData.append('assemblers[]', id);
                });

                // Add commissions to formData
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
                            Swal.fire('Sucesso!', response.message || 'Agendamento salvo com sucesso!', 'success');
                            window.location.href = response.redirect;
                        } 
                    },
                    error: function(xhr) {
                        console.error("Form Submission Error:", xhr.responseText);
                        const errors = xhr.responseJSON.errors;
                        let errorMessage = 'Erro ao salvar agendamento:\n';
                        for (const key in errors) {
                            if (errors.hasOwnProperty(key)) {
                                errorMessage += `- ${errors[key][0]}\n`;
                            }
                        }
                        Swal.fire('Erro!', errorMessage, 'error');
                    }
                });
            });
        });
    </script>
@endsection