@extends('layouts.app')

@section('title', 'Scan QR Code')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6 col-md-8">
        <div class="card card-custom overflow-hidden">
            <div class="card-header-custom bg-primary text-white">
                <i class="bi bi-qr-code-scan me-2"></i>
                <h6 class="mb-0">Pemindaian QR Code Lapangan</h6>
            </div>
            <div class="card-body p-0 position-relative">
                {{-- Scanner Container --}}
                <div id="reader" style="width: 100%; min-height: 400px; background: #000;"></div>
                
                {{-- Overlay for status --}}
                <div id="scanner-status" class="p-3 text-center bg-light border-top">
                    <p class="mb-0 text-muted small" id="status-text">
                        <i class="bi bi-camera me-1"></i> Menginisialisasi kamera...
                    </p>
                </div>
            </div>
            <div class="card-footer bg-white border-top-0 p-4">
                <div class="text-center mb-3">
                    <h6 class="fw-bold mb-1">Cara Penggunaan:</h6>
                    <p class="text-muted small">Arahkan kamera ke QR Code yang tertempel pada dokumen atau aset SPMI.</p>
                </div>
                <div class="d-grid gap-2">
                    <button id="start-btn" class="btn btn-primary btn-lg rounded-pill shadow-sm">
                        <i class="bi bi-play-fill me-1"></i> Mulai Scanner
                    </button>
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-lg rounded-pill">
                        <i class="bi bi-house me-1"></i> Kembali ke Dashboard
                    </a>
                </div>
            </div>
        </div>

        {{-- Log/Result Container (Hidden by default) --}}
        <div id="scan-result" class="card card-custom mt-4 d-none">
            <div class="card-body p-3 d-flex align-items-center gap-3">
                <div class="result-icon text-success fs-1">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-0 text-success">Berhasil Dipindai!</h6>
                    <p class="text-muted small mb-2" id="result-url"></p>
                    <a href="#" id="result-link" class="btn btn-sm btn-primary">
                        Buka Dokumen <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
    const html5QrCode = new Html5Qrcode("reader");
    const statusText = document.getElementById('status-text');
    const startBtn = document.getElementById('start-btn');
    const scanResult = document.getElementById('scan-result');
    const resultUrl = document.getElementById('result-url');
    const resultLink = document.getElementById('result-link');

    const qrConfig = { fps: 10, qrbox: { width: 250, height: 250 } };

    const onScanSuccess = (decodedText, decodedResult) => {
        // Stop scanning after success
        html5QrCode.stop().then(() => {
            statusText.innerHTML = '<span class="text-success"><i class="bi bi-check-circle me-1"></i> Pemindaian Berhasil!</span>';
            startBtn.innerHTML = '<i class="bi bi-arrow-clockwise me-1"></i> Scan Ulang';
            startBtn.classList.replace('btn-secondary', 'btn-primary');
            
            // Show result
            scanResult.classList.remove('d-none');
            resultUrl.innerText = decodedText;
            resultLink.href = decodedText;
            
            // Audio feedback
            const audio = new Audio('https://assets.mixkit.co/active_storage/sfx/2218/2218-preview.mp3');
            audio.play();

            // Auto redirect if it's our site
            if (decodedText.includes(window.location.origin)) {
                setTimeout(() => {
                    window.location.href = decodedText;
                }, 1500);
            }
        }).catch((err) => {
            console.error(err);
        });
    };

    const startScanner = () => {
        statusText.innerHTML = '<span class="text-primary"><i class="bi bi-camera-video me-1"></i> Kamera aktif, silakan pindai...</span>';
        startBtn.innerHTML = '<i class="bi bi-stop-circle me-1"></i> Hentikan Scanner';
        startBtn.classList.replace('btn-primary', 'btn-secondary');
        scanResult.classList.add('d-none');

        html5QrCode.start({ facingMode: "environment" }, qrConfig, onScanSuccess)
            .catch((err) => {
                statusText.innerHTML = '<span class="text-danger"><i class="bi bi-exclamation-octagon me-1"></i> Gagal akses kamera: ' + err + '</span>';
                startBtn.innerHTML = '<i class="bi bi-play-fill me-1"></i> Mulai Scanner';
                startBtn.classList.replace('btn-secondary', 'btn-primary');
            });
    };

    const stopScanner = () => {
        html5QrCode.stop().then(() => {
            statusText.innerHTML = '<i class="bi bi-camera me-1"></i> Scanner dihentikan.';
            startBtn.innerHTML = '<i class="bi bi-play-fill me-1"></i> Mulai Scanner';
            startBtn.classList.replace('btn-secondary', 'btn-primary');
        });
    };

    startBtn.addEventListener('click', () => {
        if (html5QrCode.isScanning) {
            stopScanner();
        } else {
            startScanner();
        }
    });

    // Handle window resize or visibility change
    document.addEventListener("visibilitychange", () => {
        if (document.hidden && html5QrCode.isScanning) {
            stopScanner();
        }
    });
</script>

<style>
    #reader video {
        object-fit: cover !important;
    }
    #reader__scan_region {
        border: 2px solid rgba(255,255,255,0.3) !important;
        border-radius: 20px;
    }
    #reader__dashboard {
        display: none !important;
    }
    .qr-code-wrapper svg {
        display: block;
        margin: auto;
    }
</style>
@endpush
