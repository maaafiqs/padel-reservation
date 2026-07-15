@extends('layouts.admin')

@section('title', 'Backup & Restore')

@section('content')
<style>
    .backup-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 2rem;
    }
    .format-card {
        border: 2px solid var(--border);
        border-radius: var(--radius-lg);
        padding: 1.25rem;
        cursor: pointer;
        transition: var(--transition);
        display: flex;
        align-items: center;
        gap: 1rem;
        position: relative;
        background: var(--surface);
        box-shadow: var(--shadow-sm);
    }
    .format-card:hover {
        border-color: var(--primary);
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }
    .format-card input[type="radio"] {
        position: absolute;
        top: 1.25rem;
        right: 1.25rem;
        accent-color: var(--primary);
        width: 1.1rem;
        height: 1.1rem;
    }
    .format-card.active {
        border-color: var(--primary);
        background-color: var(--primary-light);
    }
    .format-icon {
        width: 48px;
        height: 48px;
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    .warning-box {
        background-color: #fff5f5;
        border: 1px solid #feb2b2;
        border-radius: var(--radius-md);
        padding: 1rem;
        color: #c53030;
        display: flex;
        gap: 0.75rem;
        font-size: 0.875rem;
        line-height: 1.4;
    }
    .custom-file-upload {
        border: 2px dashed var(--border);
        border-radius: var(--radius-lg);
        padding: 2rem;
        text-align: center;
        cursor: pointer;
        transition: var(--transition);
        background: var(--background);
        display: block;
        width: 100%;
        margin-top: 0.5rem;
    }
    .custom-file-upload:hover {
        border-color: var(--primary);
        background: var(--primary-light);
    }
    .file-input-hidden {
        display: none;
    }
    .slide-down {
        transition: all 0.3s ease-out;
        opacity: 0;
        max-height: 0;
        overflow: hidden;
    }
    .slide-down.show {
        opacity: 1;
        max-height: 150px;
        margin-top: 1rem;
    }
    @media (max-width: 768px) {
        .backup-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-3xl">Backup & Pemulihan Data</h1>
        <p class="text-muted text-sm mt-1">Ekspor basis data Anda untuk pengarsipan, atau pulihkan data dari file cadangan sebelumnya.</p>
    </div>
</div>

@if(session('error'))
    <div class="badge badge-danger mb-6" style="padding: 1rem; width: 100%; display: flex; font-size: 1rem; border-radius: var(--radius-md); background-color: #ffe5e5; color: #c53030; border: 1px solid #feb2b2;">
        <i class="fa-solid fa-exclamation-circle" style="margin-right: 0.5rem;"></i> {{ session('error') }}
    </div>
@endif

@if(session('success'))
    <div class="badge badge-success mb-6" style="padding: 1rem; width: 100%; display: flex; font-size: 1rem; border-radius: var(--radius-md); background-color: #e6fffa; color: #0d9488; border: 1px solid #99f6e4;">
        <i class="fa-solid fa-circle-check" style="margin-right: 0.5rem;"></i> {{ session('success') }}
    </div>
@endif

<div class="backup-grid">
    <!-- Card 1: Export -->
    <div class="card" style="display: flex; flex-direction: column; gap: 1.5rem;">
        <div>
            <h2 class="text-xl font-semibold mb-1" style="display: flex; align-items: center; gap: 0.5rem;">
                <i class="fa-solid fa-cloud-arrow-down text-primary"></i> Ekspor / Backup Data
            </h2>
            <p class="text-muted text-sm">Pilih salah satu format ekspor data di bawah ini.</p>
        </div>

        <form action="{{ route('admin.backup.export') }}" method="POST" style="display: flex; flex-direction: column; gap: 1.5rem;">
            @csrf
            
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                <!-- DB option -->
                <label class="format-card active" for="format-db">
                    <input type="radio" id="format-db" name="format" value="db" checked>
                    <div class="format-icon" style="background-color: rgba(37, 99, 235, 0.1); color: var(--primary);">
                        <i class="fa-solid fa-database"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-sm">Database SQLite (.sqlite)</h4>
                        <p class="text-muted text-xs mt-1">Unduh salinan langsung file database SQLite aktif. Sangat mudah dipulihkan.</p>
                    </div>
                </label>

                <!-- SQL option -->
                <label class="format-card" for="format-sql">
                    <input type="radio" id="format-sql" name="format" value="sql">
                    <div class="format-icon" style="background-color: rgba(16, 185, 129, 0.1); color: #10b981;">
                        <i class="fa-solid fa-file-code"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-sm">Skrip Dump SQL (.sql)</h4>
                        <p class="text-muted text-xs mt-1">Ekspor struktur tabel dan data menjadi skrip query SQL standar.</p>
                    </div>
                </label>

                <!-- CSV option -->
                <label class="format-card" for="format-csv">
                    <input type="radio" id="format-csv" name="format" value="csv">
                    <div class="format-icon" style="background-color: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                        <i class="fa-solid fa-file-csv"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-sm">Tabel CSV Tunggal (.csv)</h4>
                        <p class="text-muted text-xs mt-1">Ekspor data salah satu tabel pilihan secara individu menjadi file CSV.</p>
                    </div>
                </label>
            </div>

            <!-- Table selection for CSV export (hidden by default) -->
            <div id="export-table-container" class="slide-down" style="display: flex; flex-direction: column; gap: 0.5rem; padding-left: 4.25rem;">
                <label class="text-sm font-semibold">Pilih Tabel untuk Diunduh (CSV)</label>
                <select name="table" class="btn btn-outline" style="width: 100%; text-align: left; padding: 0.75rem; background: var(--surface); color: var(--text-main); font-weight: normal; border: 1.5px solid var(--border);">
                    @foreach($tableNames as $tName)
                        <option value="{{ $tName }}">{{ ucfirst($tName) }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; display: flex; align-items: center; justify-content: center; gap: 0.5rem; padding: 1rem; margin-top: auto;">
                <i class="fa-solid fa-download"></i> Unduh File Backup
            </button>
        </form>
    </div>

    <!-- Card 2: Import -->
    <div class="card" style="display: flex; flex-direction: column; gap: 1.5rem;">
        <div>
            <h2 class="text-xl font-semibold mb-1" style="display: flex; align-items: center; gap: 0.5rem;">
                <i class="fa-solid fa-cloud-arrow-up text-danger" style="color: #e53e3e;"></i> Impor / Pemulihan Data
            </h2>
            <p class="text-muted text-sm">Unggah file backup sebelumnya untuk memulihkan keadaan database.</p>
        </div>

        <form id="restore-form" action="{{ route('admin.backup.restore') }}" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 1.25rem;">
            @csrf
            
            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                <label class="text-sm font-semibold">1. Pilih Format File Unggahan</label>
                <select name="format" id="restore-format" class="btn btn-outline" style="width: 100%; text-align: left; padding: 0.75rem; background: var(--surface); color: var(--text-main); font-weight: normal; border: 1.5px solid var(--border);">
                    <option value="db">Database SQLite (.sqlite / .db)</option>
                    <option value="sql">Skrip Dump SQL (.sql)</option>
                    <option value="csv">Tabel CSV Tunggal (.csv)</option>
                </select>
            </div>

            <!-- Table selection for CSV restore (hidden by default) -->
            <div id="restore-table-container" class="slide-down" style="display: flex; flex-direction: column; gap: 0.5rem;">
                <label class="text-sm font-semibold">Pilih Tabel Tujuan Pemulihan (CSV)</label>
                <select name="table" class="btn btn-outline" style="width: 100%; text-align: left; padding: 0.75rem; background: var(--surface); color: var(--text-main); font-weight: normal; border: 1.5px solid var(--border);">
                    @foreach($tableNames as $tName)
                        <option value="{{ $tName }}">{{ ucfirst($tName) }}</option>
                    @endforeach
                </select>
            </div>

            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                <label class="text-sm font-semibold">2. Pilih File Backup</label>
                <label class="custom-file-upload" id="file-drop-zone">
                    <input type="file" name="file" id="backup-file" class="file-input-hidden" required>
                    <i class="fa-solid fa-file-import text-2xl text-muted" id="upload-icon" style="font-size: 2rem; margin-bottom: 0.75rem;"></i>
                    <p class="font-medium text-sm" id="upload-text">Klik atau seret file backup ke sini</p>
                    <p class="text-muted text-xs mt-1" id="file-requirements">Ekstensi file harus sesuai dengan format pilihan</p>
                </label>
            </div>

            <!-- Danger Alert Box -->
            <div class="warning-box">
                <div style="font-size: 1.25rem; margin-top: 0.1rem;">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <div>
                    <strong style="display: block; margin-bottom: 0.25rem; font-weight: 700;">PERINGATAN BAHAYA</strong>
                    Aksi pemulihan (restore) akan menghapus data yang ada sekarang secara permanen dan menggantinya dengan data dari file backup. Pastikan data saat ini tidak lagi diperlukan atau telah disimpan.
                </div>
            </div>

            <!-- Overwrite Acknowledgment Checkbox -->
            <label style="display: flex; gap: 0.75rem; align-items: flex-start; cursor: pointer; padding: 0.25rem 0;">
                <input type="checkbox" name="confirm_overwrite" id="confirm-overwrite" required style="margin-top: 0.25rem; accent-color: #e53e3e; width: 1.1rem; height: 1.1rem;">
                <span class="text-sm text-muted" style="line-height: 1.4;">Saya menyetujui dan memahami sepenuhnya bahwa aksi ini akan menimpa basis data saat ini dengan file backup yang diunggah.</span>
            </label>

            <button type="submit" class="btn" id="btn-restore" style="width: 100%; display: flex; align-items: center; justify-content: center; gap: 0.5rem; padding: 1rem; background-color: #e53e3e; color: white;">
                <i class="fa-solid fa-clock-rotate-left"></i> Mulai Proses Pemulihan
            </button>
        </form>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Toggle active styling for radio buttons
        const formatCards = document.querySelectorAll('.format-card');
        const exportTableContainer = document.getElementById('export-table-container');

        formatCards.forEach(card => {
            card.addEventListener('click', function() {
                formatCards.forEach(c => c.classList.remove('active'));
                this.classList.add('active');
                
                // Get the input radio value inside
                const val = this.querySelector('input[type="radio"]').value;
                if (val === 'csv') {
                    exportTableContainer.classList.add('show');
                } else {
                    exportTableContainer.classList.remove('show');
                }
            });
        });

        // Handle file upload display
        const fileInput = document.getElementById('backup-file');
        const uploadText = document.getElementById('upload-text');
        const uploadIcon = document.getElementById('upload-icon');
        const fileZone = document.getElementById('file-drop-zone');

        fileInput.addEventListener('change', function(e) {
            if (e.target.files.length > 0) {
                const name = e.target.files[0].name;
                uploadText.textContent = name;
                uploadText.style.color = 'var(--primary)';
                uploadIcon.className = 'fa-solid fa-circle-check text-2xl text-success';
                uploadIcon.style.color = '#10b981';
                fileZone.style.borderColor = 'var(--primary)';
            }
        });

        // Dynamic change file requirements based on format dropdown
        const restoreFormat = document.getElementById('restore-format');
        const fileReqs = document.getElementById('file-requirements');
        const restoreTableContainer = document.getElementById('restore-table-container');
        
        const updateRequirementsText = () => {
            const format = restoreFormat.value;
            if (format === 'db') {
                fileReqs.textContent = 'Ekstensi file harus berupa .sqlite atau .db';
                restoreTableContainer.classList.remove('show');
            } else if (format === 'sql') {
                fileReqs.textContent = 'Ekstensi file harus berupa .sql';
                restoreTableContainer.classList.remove('show');
            } else if (format === 'csv') {
                fileReqs.textContent = 'Ekstensi file harus berupa .csv';
                restoreTableContainer.classList.add('show');
            }
        };

        restoreFormat.addEventListener('change', updateRequirementsText);
        updateRequirementsText(); // Initial setup

        // Double confirmation mechanism
        const restoreForm = document.getElementById('restore-form');
        restoreForm.addEventListener('submit', function(e) {
            const isChecked = document.getElementById('confirm-overwrite').checked;
            if (!isChecked) {
                alert('Anda harus mencentang kotak persetujuan sebelum melakukan pemulihan!');
                e.preventDefault();
                return;
            }

            const confirmation = confirm('TINDAKAN SANGAT BERBAHAYA!\nApakah Anda benar-benar yakin ingin memulihkan database? Seluruh data yang ada saat ini akan DIHAPUS permanen dan ditimpa!');
            if (!confirmation) {
                e.preventDefault();
                return;
            }

            // Show loading animation on the button
            const btnRestore = document.getElementById('btn-restore');
            btnRestore.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Memproses Pemulihan Data...';
            btnRestore.disabled = true;
            btnRestore.style.opacity = '0.7';
            btnRestore.style.cursor = 'not-allowed';
        });
    });
</script>
@endsection
