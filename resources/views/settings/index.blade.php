@extends('layouts.app')

@section('title', 'Pengaturan Aplikasi')
@section('page-title', 'Pengaturan Aplikasi')
@section('page-subtitle', 'Kustomisasi tampilan dan konfigurasi aplikasi')

@section('breadcrumb')
    <li class="breadcrumb-item active">Pengaturan</li>
@endsection

@section('content')
<div class="row g-4">
    <div class="col-lg-8">
        <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            {{-- General Settings --}}
            <div class="card card-custom mb-4">
                <div class="card-header-custom">
                    <i class="bi bi-gear me-2 text-primary"></i>
                    <h6 class="mb-0">Pengaturan Umum</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Aplikasi <span class="text-danger">*</span></label>
                            <input type="text" name="app_name" class="form-control"
                                   value="{{ old('app_name', $settings['general']->where('key', 'app_name')->first()?->value ?? 'SPMI') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tagline</label>
                            <input type="text" name="app_tagline" class="form-control"
                                   value="{{ old('app_tagline', $settings['general']->where('key', 'app_tagline')->first()?->value ?? '') }}">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Theme Settings --}}
            <div class="card card-custom mb-4">
                <div class="card-header-custom">
                    <i class="bi bi-palette me-2 text-primary"></i>
                    <h6 class="mb-0">Pengaturan Tema</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Warna Utama <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="color" id="colorPicker" class="form-control form-control-color"
                                       value="{{ old('theme_primary', $settings['theme']->where('key', 'theme_primary')->first()?->value ?? '#4e73df') }}"
                                       style="width: 60px;">
                                <input type="text" name="theme_primary" id="colorHex" class="form-control"
                                       value="{{ old('theme_primary', $settings['theme']->where('key', 'theme_primary')->first()?->value ?? '#4e73df') }}"
                                       pattern="^#[a-fA-F0-9]{6}$" required>
                            </div>
                            <div class="form-text">Klik kotak warna atau masukkan kode hex (contoh: #4e73df)</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tema Sidebar <span class="text-danger">*</span></label>
                            <select name="theme_sidebar" class="form-select" required>
                                <option value="dark" {{ ($settings['theme']->where('key', 'theme_sidebar')->first()?->value ?? 'dark') === 'dark' ? 'selected' : '' }}>
                                    Gelap (Dark)
                                </option>
                                <option value="light" {{ ($settings['theme']->where('key', 'theme_sidebar')->first()?->value ?? 'dark') === 'light' ? 'selected' : '' }}>
                                    Terang (Light)
                                </option>
                            </select>
                        </div>
                    </div>

                    {{-- Preset Colors --}}
                    <div class="mt-3">
                        <label class="form-label">Preset Warna:</label>
                        <div class="d-flex flex-wrap gap-2">
                            @php
                                $presets = [
                                    '#4e73df' => 'Primary Blue',
                                    '#1cc88a' => 'Success Green',
                                    '#36b9cc' => 'Info Cyan',
                                    '#f6c23e' => 'Warning Yellow',
                                    '#e74a3b' => 'Danger Red',
                                    '#6f42c1' => 'Purple',
                                    '#fd7e14' => 'Orange',
                                    '#20c997' => 'Teal',
                                    '#6c757d' => 'Secondary Gray',
                                    '#343a40' => 'Dark',
                                ];
                            @endphp
                            @foreach($presets as $color => $name)
                                <button type="button" class="btn btn-sm color-preset" 
                                        data-color="{{ $color }}"
                                        style="background-color: {{ $color }}; width: 32px; height: 32px;"
                                        title="{{ $name }}">
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- Institusi Settings --}}
            <div class="card card-custom mb-4">
                <div class="card-header-custom">
                    <i class="bi bi-building me-2 text-primary"></i>
                    <h6 class="mb-0">Data Institusi</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Nama Institusi</label>
                            <input type="text" name="nama_institusi" class="form-control"
                                   value="{{ old('nama_institusi', $settings['institusi']->where('key', 'nama_institusi')->first()?->value ?? '') }}">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Alamat Institusi</label>
                            <textarea name="alamat_institusi" class="form-control" rows="2">{{ old('alamat_institusi', $settings['institusi']->where('key', 'alamat_institusi')->first()?->value ?? '') }}</textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Kota</label>
                            <input type="text" name="kota_institusi" class="form-control"
                                   value="{{ old('kota_institusi', $settings['institusi']->where('key', 'kota_institusi')->first()?->value ?? '') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Logo Institusi</label>
                            @php $currentLogoInstitusi = $settings['institusi']->where('key', 'logo_institusi')->first()?->value; @endphp
                            @if($currentLogoInstitusi)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $currentLogoInstitusi) }}" alt="Logo Institusi" height="80">
                                </div>
                            @endif
                            <input type="file" name="logo_institusi" class="form-control" accept="image/png,jpg,jpeg">
                            <div class="form-text">PNG, JPG. Max 2MB. Disarankan ukuran 200x200 pixel</div>
                        </div>
                    </div>

                    {{-- Note: Data pimpinan (Kepala Institusi dan Ketua SPMI) diambil dari tabel users --}}
                    {{-- Berdasarkan role dan jabatan, bukan dari settings --}}
                </div>
            </div>

            {{-- Logo Settings --}}
            <div class="card card-custom mb-4">
                <div class="card-header-custom">
                    <i class="bi bi-image me-2 text-primary"></i>
                    <h6 class="mb-0">Logo & Favicon Aplikasi</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Logo Aplikasi</label>
                            @php $currentLogo = $settings['logo']->where('key', 'logo')->first()?->value; @endphp
                            @if($currentLogo)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $currentLogo) }}" alt="Logo" height="50">
                                </div>
                            @endif
                            <input type="file" name="logo" class="form-control" accept="image/png,jpg,jpeg,svg">
                            <div class="form-text">PNG, JPG, SVG. Max 2MB. Disarankan ukuran 200x50 pixel</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Favicon</label>
                            @php $currentFavicon = $settings['logo']->where('key', 'favicon')->first()?->value; @endphp
                            @if($currentFavicon)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $currentFavicon) }}" alt="Favicon" height="32">
                                </div>
                            @endif
                            <input type="file" name="favicon" class="form-control" accept="image/ico,png">
                            <div class="form-text">ICO, PNG. Max 512KB. Disarankan 32x32 pixel</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="d-flex gap-2 justify-content-between">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i>Simpan Pengaturan
                </button>
                <form action="{{ route('settings.reset') }}" method="POST" onsubmit="return confirm('Reset semua pengaturan ke default?')">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger">
                        <i class="bi bi-arrow-counterclockwise me-1"></i>Reset ke Default
                    </button>
                </form>
            </div>
        </form>
    </div>

    {{-- Preview --}}
    <div class="col-lg-4">
        <div class="card card-custom sticky-top" style="top: 20px;">
            <div class="card-header-custom">
                <i class="bi bi-eye me-2 text-primary"></i>
                <h6 class="mb-0">Preview</h6>
            </div>
            <div class="card-body">
                <div class="preview-sidebar p-3 rounded mb-3" id="previewSidebar">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="preview-logo" id="previewLogo">
                            <i class="bi bi-shield-check fs-4"></i>
                        </div>
                        <div>
                            <div class="fw-bold" id="previewName">SPMI</div>
                            <div class="small opacity-75" id="previewTagline">Penjaminan Mutu</div>
                        </div>
                    </div>
                    <div class="preview-menu-item active">
                        <i class="bi bi-speedometer2 me-2"></i>Dashboard
                    </div>
                    <div class="preview-menu-item">
                        <i class="bi bi-folder2-open me-2"></i>Dokumen
                    </div>
                    <div class="preview-menu-item">
                        <i class="bi bi-clipboard-check me-2"></i>Audit
                    </div>
                </div>

                <div class="preview-button p-3 rounded text-center" id="previewButton">
                    <button class="btn text-white" id="previewBtn">
                        <i class="bi bi-plus-lg me-1"></i>Tombol Primary
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.color-preset {
    border: 2px solid transparent;
    border-radius: 4px;
    cursor: pointer;
}
.color-preset:hover {
    border-color: #000;
    transform: scale(1.1);
}
.preview-sidebar {
    background: #1a1c2e;
    color: #fff;
}
.preview-sidebar.light {
    background: #f8f9fa;
    color: #333;
}
.preview-menu-item {
    padding: 8px 12px;
    border-radius: 4px;
    margin-bottom: 4px;
    font-size: 14px;
}
.preview-menu-item.active {
    background: rgba(255,255,255,0.1);
}
.preview-button {
    background: #f8f9fa;
}
</style>
@endpush

@push('scripts')
<script>
// Color picker sync
const colorPicker = document.getElementById('colorPicker');
const colorHex = document.getElementById('colorHex');

colorPicker.addEventListener('input', function() {
    colorHex.value = this.value;
    updatePreview(this.value);
});

colorHex.addEventListener('input', function() {
    if (/^#[a-fA-F0-9]{6}$/.test(this.value)) {
        colorPicker.value = this.value;
        updatePreview(this.value);
    }
});

// Preset colors
document.querySelectorAll('.color-preset').forEach(btn => {
    btn.addEventListener('click', function() {
        const color = this.dataset.color;
        colorPicker.value = color;
        colorHex.value = color;
        updatePreview(color);
    });
});

// Sidebar theme
document.querySelector('[name="theme_sidebar"]').addEventListener('change', function() {
    const sidebar = document.getElementById('previewSidebar');
    if (this.value === 'light') {
        sidebar.classList.add('light');
    } else {
        sidebar.classList.remove('light');
    }
});

// App name preview
document.querySelector('[name="app_name"]').addEventListener('input', function() {
    document.getElementById('previewName').textContent = this.value || 'SPMI';
});

document.querySelector('[name="app_tagline"]').addEventListener('input', function() {
    document.getElementById('previewTagline').textContent = this.value || 'Penjaminan Mutu';
});

function updatePreview(color) {
    document.getElementById('previewBtn').style.backgroundColor = color;
    document.getElementById('previewLogo').style.color = color;
}

// Initial preview
updatePreview(colorHex.value);
</script>
@endpush
@endsection