{{-- =========================================================
     Documentos Digitalizados — Partial
     Variáveis esperadas: $driver (Driver), $documents (paginator|collection)
     ========================================================= --}}

@php
    $today = \Carbon\Carbon::today();
@endphp

<style>
    .doc-dropzone {
        border: 2px dashed rgba(222, 8, 2, 0.45);
        border-radius: 12px;
        padding: 28px 20px;
        text-align: center;
        transition: background .2s, border-color .2s;
        cursor: pointer;
        position: relative;
        background: #fafafa;
    }
    .doc-dropzone:hover,
    .doc-dropzone.drag-over {
        background: rgba(222, 8, 2, 0.06);
        border-color: rgba(222, 8, 2, 0.8);
    }
    .doc-dropzone input[type="file"] {
        position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%;
    }
    .doc-dropzone .dz-icon { font-size: 2.4rem; color: #DE0802; }
    .doc-dropzone .dz-label { font-size: .95rem; color: #666; margin-top: 6px; }
    .doc-dropzone .dz-filename { font-weight: 600; color: #1F2A44; font-size: .9rem; margin-top: 8px; }

    .doc-badge-expired  { background: #e74a3b !important; color: #fff !important; }
    .doc-badge-expiring { background: #f6c23e !important; color: #fff !important; }
    .doc-badge-ok       { background: #1cc88a !important; color: #fff !important; }
    .doc-badge-none     { background: #D2D4DA !important; color: #1F2A44 !important; }

    .doc-row-expired  { background: rgba(231, 74, 59, .07) !important; }
    .doc-row-expiring { background: rgba(246, 194, 62, .07) !important; }

    #imageModal .modal-body img { max-height: 80vh; object-fit: contain; }
</style>

<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm mb-4" id="documentos-section">
            <div class="card-header bg-transparent border-0 py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-semibold">
                    <i class="bx bx-folder-open me-2 text-danger"></i>{{ __('Documentos Digitalizados') }}
                </h6>
                <span class="badge bg-label-primary">{{ $driver->documents()->count() }} {{ __('doc(s)') }}</span>
            </div>
            <div class="card-body">

                {{-- Flash Messages --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show mb-3 border-0 bg-success bg-opacity-10" role="alert">
                        <i class="bx bx-check-circle me-1 text-success"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mb-3 border-0 bg-danger bg-opacity-10" role="alert">
                        <i class="bx bx-error-circle me-1 text-danger"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- Upload Form --}}
                <form action="{{ route('drivers.documents.store', $driver->id) }}"
                      method="POST" enctype="multipart/form-data" id="doc-upload-form">
                    @csrf
                    <div class="row g-3 align-items-end mb-4">
                        {{-- Dropzone --}}
                        <div class="col-12">
                            <div class="doc-dropzone" id="doc-dropzone">
                                <input type="file" name="file" id="doc-file-input"
                                       accept=".pdf,.jpg,.jpeg,.png" required>
                                <div class="dz-icon"><i class="bx bx-cloud-upload"></i></div>
                                <div class="dz-label">{{ __('Arraste um arquivo aqui ou') }} <strong>{{ __('clique para selecionar') }}</strong></div>
                                <div class="dz-label" style="font-size:.8rem">{{ __('PDF, JPG ou PNG') }} · {{ __('máx. 5 MB') }}</div>
                                <div class="dz-filename d-none" id="dz-filename-display"></div>
                            </div>
                            @error('file')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div class="col-md-4">
                            <label for="doc-description" class="form-label">{{ __('Descrição') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="doc-description"
                                   name="description" value="{{ old('description') }}"
                                   placeholder="{{ __('Ex: CNH verso') }}" required>
                            @error('description')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Category --}}
                        <div class="col-md-3">
                            <label for="doc-category" class="form-label">{{ __('Categoria') }} <span class="text-danger">*</span></label>
                            <select class="form-select" id="doc-category" name="category" required>
                                @foreach(['CNH','CRLV','Contrato','Outros'] as $cat)
                                    <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            </select>
                            @error('category')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Expiry --}}
                        <div class="col-md-3">
                            <label for="doc-expires-at" class="form-label">{{ __('Validade') }}</label>
                            <input type="date" class="form-control" id="doc-expires-at"
                                   name="expires_at" value="{{ old('expires_at') }}">
                            @error('expires_at')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Submit --}}
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100" id="doc-submit-btn">
                                <i class="bx bx-upload me-1"></i> {{ __('Enviar') }}
                            </button>
                        </div>
                    </div>
                </form>

                {{-- Documents Table --}}
                @if($driver->documents()->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="py-3 px-4" style="width:40px"></th>
                                    <th class="py-3">{{ __('Descrição') }}</th>
                                    <th class="py-3">{{ __('Categoria') }}</th>
                                    <th class="py-3">{{ __('Validade') }}</th>
                                    <th class="py-3">{{ __('Enviado em') }}</th>
                                    <th class="py-3 text-center">{{ __('Ações') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($documents as $doc)
                                    @php
                                        $isExpired      = $doc->expires_at && $doc->expires_at->isPast();
                                        $isExpiringSoon = $doc->expires_at && !$isExpired
                                                         && $doc->expires_at->diffInDays($today) <= 30;
                                        $rowClass = $isExpired ? 'doc-row-expired' : ($isExpiringSoon ? 'doc-row-expiring' : '');
                                    @endphp
                                    <tr class="{{ $rowClass }}">
                                        {{-- File type icon --}}
                                        <td class="py-3 px-4 text-center">
                                            @if($doc->file_type === 'pdf')
                                                <i class="bx bxs-file-pdf fs-4 text-danger"></i>
                                            @else
                                                <i class="bx bxs-image fs-4 text-info"></i>
                                            @endif
                                        </td>

                                        {{-- Description --}}
                                        <td class="py-3">
                                            <span class="fw-semibold">{{ $doc->description }}</span>
                                        </td>

                                        {{-- Category --}}
                                        <td class="py-3">
                                            <span class="badge bg-label-secondary">{{ $doc->category }}</span>
                                        </td>

                                        {{-- Expiry badge --}}
                                        <td class="py-3">
                                            @if(!$doc->expires_at)
                                                <span class="badge doc-badge-none">{{ __('Sem validade') }}</span>
                                            @elseif($isExpired)
                                                <span class="badge doc-badge-expired">
                                                    <i class="bx bx-x-circle me-1"></i>
                                                    {{ __('Vencido') }} {{ $doc->expires_at->format('d/m/Y') }}
                                                </span>
                                            @elseif($isExpiringSoon)
                                                <span class="badge doc-badge-expiring">
                                                    <i class="bx bx-error me-1"></i>
                                                    {{ __('Vence') }} {{ $doc->expires_at->format('d/m/Y') }}
                                                </span>
                                            @else
                                                <span class="badge doc-badge-ok">
                                                    {{ $doc->expires_at->format('d/m/Y') }}
                                                </span>
                                            @endif
                                        </td>

                                        {{-- Upload date --}}
                                        <td class="py-3 text-muted small">{{ $doc->created_at->format('d/m/Y H:i') }}</td>

                                        {{-- Actions --}}
                                        <td class="py-3 text-center">
                                            @if($doc->file_type === 'image')
                                                <button type="button"
                                                        class="btn btn-icon btn-sm btn-outline-info me-1"
                                                        title="{{ __('Visualizar') }}"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#imageModal"
                                                        data-src="{{ asset('storage/' . $doc->file_path) }}"
                                                        data-caption="{{ $doc->description }}">
                                                    <i class="bx bx-show"></i>
                                                </button>
                                            @else
                                                <a href="{{ asset('storage/' . $doc->file_path) }}"
                                                   target="_blank"
                                                   class="btn btn-icon btn-sm btn-outline-info me-1"
                                                   title="{{ __('Abrir PDF') }}">
                                                    <i class="bx bx-show"></i>
                                                </a>
                                            @endif

                                            {{-- Delete --}}
                                            <form action="{{ route('drivers.documents.destroy', [$driver->id, $doc->id]) }}"
                                                  method="POST" class="d-inline doc-delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="btn btn-icon btn-sm btn-outline-danger"
                                                        title="{{ __('Excluir') }}"
                                                        onclick="return confirm('{{ __('Tem certeza que deseja excluir este documento?') }}')">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if(method_exists($documents, 'links'))
                        <div class="mt-3">{{ $documents->links() }}</div>
                    @endif

                @else
                    <div class="text-center py-5">
                        <i class="bx bx-folder-open fs-1 text-muted opacity-50 d-block mb-2"></i>
                        <p class="text-muted mb-0">{{ __('Nenhum documento enviado ainda') }}</p>
                    </div>
                @endif

            </div>{{-- /card-body --}}
        </div>
    </div>
</div>

{{-- ─── Image Lightbox Modal ────────────────────────────────── --}}
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h6 class="modal-title" id="imageLightboxCaption">{{ __('Visualizar Imagem') }}</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-2">
                <img id="imageLightboxImg" src="" alt="" class="img-fluid rounded">
            </div>
            <div class="modal-footer justify-content-between">
                <a id="imageLightboxDownload" href="#" target="_blank"
                   class="btn btn-outline-primary btn-sm">
                    <i class="bx bx-download me-1"></i> {{ __('Abrir em nova aba') }}
                </a>
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">{{ __('Fechar') }}</button>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    // ── Dropzone drag-and-drop ───
    const dropzone = document.getElementById('doc-dropzone');
    const fileInput = document.getElementById('doc-file-input');
    const filenameDisplay = document.getElementById('dz-filename-display');

    if (dropzone && fileInput) {
        ['dragenter','dragover'].forEach(ev => {
            dropzone.addEventListener(ev, e => { e.preventDefault(); dropzone.classList.add('drag-over'); });
        });
        ['dragleave','drop'].forEach(ev => {
            dropzone.addEventListener(ev, e => { e.preventDefault(); dropzone.classList.remove('drag-over'); });
        });
        dropzone.addEventListener('drop', e => {
            if (e.dataTransfer.files.length) {
                fileInput.files = e.dataTransfer.files;
                showFilename(e.dataTransfer.files[0].name);
            }
        });
        fileInput.addEventListener('change', () => {
            if (fileInput.files.length) showFilename(fileInput.files[0].name);
        });
        function showFilename(name) {
            filenameDisplay.textContent = '📎 ' + name;
            filenameDisplay.classList.remove('d-none');
        }
    }

    // ── Image Lightbox ───
    const imageModal = document.getElementById('imageModal');
    if (imageModal) {
        imageModal.addEventListener('show.bs.modal', function (event) {
            const btn = event.relatedTarget;
            const src = btn.dataset.src;
            const caption = btn.dataset.caption || '{{ __("Visualizar Imagem") }}';
            document.getElementById('imageLightboxImg').src = src;
            document.getElementById('imageLightboxImg').alt = caption;
            document.getElementById('imageLightboxCaption').textContent = caption;
            document.getElementById('imageLightboxDownload').href = src;
        });
        imageModal.addEventListener('hidden.bs.modal', function () {
            document.getElementById('imageLightboxImg').src = '';
        });
    }
})();
</script>
