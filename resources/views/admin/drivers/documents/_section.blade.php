{{-- =========================================================
     Documentos Digitalizados — Partial
     Variáveis esperadas: $driver (Driver), $documents (paginator|collection)
     ========================================================= --}}

@php
    $today = \Carbon\Carbon::today();
@endphp

<style>
    .doc-dropzone {
        border: 2px dashed rgba(var(--bs-primary-rgb), .45);
        border-radius: 12px;
        padding: 28px 20px;
        text-align: center;
        transition: background .2s, border-color .2s;
        cursor: pointer;
        position: relative;
    }
    .doc-dropzone:hover,
    .doc-dropzone.drag-over {
        background: rgba(var(--bs-primary-rgb), .06);
        border-color: rgba(var(--bs-primary-rgb), .8);
    }
    .doc-dropzone input[type="file"] {
        position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%;
    }
    .doc-dropzone .dz-icon { font-size: 2.4rem; color: var(--bs-primary); }
    .doc-dropzone .dz-label { font-size: .95rem; color: var(--bs-secondary-color); margin-top: 6px; }
    .doc-dropzone .dz-filename { font-weight: 600; color: var(--bs-body-color); font-size: .9rem; margin-top: 8px; }

    .doc-badge-expired  { background: #ea5455 !important; color: #fff !important; }
    .doc-badge-expiring { background: #ff9f43 !important; color: #fff !important; }
    .doc-badge-ok       { background: #28c76f !important; color: #fff !important; }
    .doc-badge-none     { background: var(--bs-secondary-bg) !important; color: var(--bs-secondary-color) !important; }

    .doc-row-expired  { background: rgba(234, 84, 85, .07) !important; }
    .doc-row-expiring { background: rgba(255, 159, 67, .07) !important; }

    #imageModal .modal-body img { max-height: 80vh; object-fit: contain; }
</style>
<div class="row mb-6 gy-6">
    <div class="col-xl">
<div class="card mb-4" id="documentos-section">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0">
            <i class="bx bx-folder-open me-2 text-primary"></i>
            Documentos Digitalizados
        </h6>
        <span class="badge bg-label-primary">{{ $driver->documents()->count() }} doc(s)</span>
    </div>
    <div class="card-body">

        {{-- ─── Flash Messages ──────────────────────────────────── --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                <i class="bx bx-check-circle me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                <i class="bx bx-error-circle me-1"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- ─── Upload Form ──────────────────────────────────────── --}}
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
                        <div class="dz-label">Arraste um arquivo aqui ou <strong>clique para selecionar</strong></div>
                        <div class="dz-label" style="font-size:.8rem">PDF, JPG ou PNG · máx. 5 MB</div>
                        <div class="dz-filename d-none" id="dz-filename-display"></div>
                    </div>
                    @error('file')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Description --}}
                <div class="col-md-4">
                    <label for="doc-description" class="form-label">Descrição <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="doc-description"
                           name="description" value="{{ old('description') }}"
                           placeholder="Ex: CNH verso" required>
                    @error('description')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Category --}}
                <div class="col-md-3">
                    <label for="doc-category" class="form-label">Categoria <span class="text-danger">*</span></label>
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
                    <label for="doc-expires-at" class="form-label">Validade</label>
                    <input type="date" class="form-control" id="doc-expires-at"
                           name="expires_at" value="{{ old('expires_at') }}">
                    @error('expires_at')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Submit --}}
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100" id="doc-submit-btn">
                        <i class="bx bx-upload me-1"></i> Enviar
                    </button>
                </div>
            </div>
        </form>

        {{-- ─── Documents Table ─────────────────────────────────── --}}
        @if($driver->documents()->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width:40px"></th>
                        <th>Descrição</th>
                        <th>Categoria</th>
                        <th>Validade</th>
                        <th>Enviado em</th>
                        <th style="width:120px">Ações</th>
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
                            <td class="text-center">
                                @if($doc->file_type === 'pdf')
                                    <i class="bx bxs-file-pdf fs-4 text-danger"></i>
                                @else
                                    <i class="bx bxs-image fs-4 text-info"></i>
                                @endif
                            </td>

                            {{-- Description --}}
                            <td>
                                <div class="fw-semibold">{{ $doc->description }}</div>
                            </td>

                            {{-- Category --}}
                            <td>
                                <span class="badge bg-label-secondary">{{ $doc->category }}</span>
                            </td>

                            {{-- Expiry badge --}}
                            <td>
                                @if(!$doc->expires_at)
                                    <span class="badge doc-badge-none">Sem validade</span>
                                @elseif($isExpired)
                                    <span class="badge doc-badge-expired">
                                        <i class="bx bx-x-circle me-1"></i>
                                        Vencido {{ $doc->expires_at->format('d/m/Y') }}
                                    </span>
                                @elseif($isExpiringSoon)
                                    <span class="badge doc-badge-expiring">
                                        <i class="bx bx-error me-1"></i>
                                        Vence {{ $doc->expires_at->format('d/m/Y') }}
                                    </span>
                                @else
                                    <span class="badge doc-badge-ok">
                                        {{ $doc->expires_at->format('d/m/Y') }}
                                    </span>
                                @endif
                            </td>

                            {{-- Upload date --}}
                            <td class="text-muted small">{{ $doc->created_at->format('d/m/Y H:i') }}</td>

                            {{-- Actions --}}
                            <td>
                                @if($doc->file_type === 'image')
                                    <button type="button"
                                            class="btn btn-icon btn-sm btn-outline-info me-1"
                                            title="Visualizar"
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
                                       title="Abrir PDF">
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
                                            title="Excluir"
                                            onclick="return confirm('Tem certeza que deseja excluir este documento?')">
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
            <div class="mt-2">{{ $documents->links() }}</div>
        @endif

        @else
            <div class="text-center py-4 text-muted">
                <i class="bx bx-folder-open fs-1 d-block mb-2 opacity-50"></i>
                Nenhum documento enviado ainda.
            </div>
        @endif

    </div>{{-- /card-body --}}
</div>
</div>
</div>

{{-- ─── Image Lightbox Modal ────────────────────────────────── --}}
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="imageLightboxCaption">Visualizar Imagem</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-2">
                <img id="imageLightboxImg" src="" alt="" class="img-fluid rounded">
            </div>
            <div class="modal-footer justify-content-between">
                <a id="imageLightboxDownload" href="#" target="_blank"
                   class="btn btn-outline-primary btn-sm">
                    <i class="bx bx-download me-1"></i> Abrir em nova aba
                </a>
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Fechar</button>
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
            const caption = btn.dataset.caption || 'Visualizar Imagem';
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
