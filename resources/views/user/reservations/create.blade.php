@extends('layouts.app')

@section('title', 'Buat Reservasi Baru')

@section('content')
<style>
    .time-grid-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
        gap: 1rem;
    }
    .time-box {
        background-color: #ffffff;
        border: 2px solid #e2e8f0;
        border-radius: 0.5rem;
        padding: 0.75rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    .time-box:hover:not(.booked) {
        border-color: var(--primary);
        color: var(--primary);
        background-color: var(--primary-light);
    }
    .time-box.booked {
        background-color: #f1f5f9;
        color: #94a3b8;
        border-color: #e2e8f0;
        cursor: not-allowed;
    }
    .time-box .time-text {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
    }
    .time-box .status-text {
        font-size: 0.75rem;
    }
    
    .time-box.selected-start {
        background-color: #22c55e !important;
        border-color: #16a34a !important;
        color: white !important;
        box-shadow: 0 4px 6px -1px rgba(34, 197, 94, 0.4);
    }
    .time-box.selected-end {
        background-color: #eab308 !important;
        border-color: #ca8a04 !important;
        color: white !important;
        box-shadow: 0 4px 6px -1px rgba(234, 179, 8, 0.4);
    }
    .time-box.selected-between {
        background-color: #eff6ff !important;
        border-color: #bfdbfe !important;
        color: #1e3a8a !important;
    }
</style>
<div class="dashboard-layout">
    <!-- Sidebar -->
    <aside class="sidebar">
        <h3 class="text-xl mb-6 text-primary">Menu Pengguna</h3>
        <nav class="sidebar-menu">
            <a href="{{ route('user.dashboard') }}" class="sidebar-link"><i class="fa-solid fa-gauge"></i> Dashboard</a>
            <a href="{{ route('user.reservations.create') }}" class="sidebar-link active"><i class="fa-solid fa-calendar-plus"></i> Buat Reservasi</a>
            <a href="{{ route('user.reservations.index') }}" class="sidebar-link"><i class="fa-solid fa-clock-rotate-left"></i> Riwayat Booking</a>
            <a href="{{ route('user.profile.edit') }}" class="sidebar-link"><i class="fa-solid fa-user"></i> Profil Saya</a>
            <form method="POST" action="{{ route('logout') }}" style="margin-top: 1rem; border-top: 1px solid var(--border); padding-top: 1rem;">
                @csrf
                <button type="submit" class="sidebar-link text-danger w-full" style="text-align: left; background: none; border: none; cursor: pointer; display: flex; align-items: center; width: 100%; color: #ef4444; gap: 0.75rem;">
                    <i class="fa-solid fa-right-from-bracket"></i> Logout
                </button>
            </form>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="dashboard-content">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl">Buat Reservasi Baru</h1>
        </div>

        @if(session('success'))
            <div class="card mb-6 bg-primary-light" style="border-left: 4px solid var(--primary);">
                <p class="text-primary font-semibold">{{ session('success') }}</p>
            </div>
        @endif

        @if($errors->any())
            <div class="card mb-6" style="background-color: #fee2e2; border-left: 4px solid #991b1b;">
                <ul style="color: #991b1b; padding-left: 1rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card">
            <form action="{{ route('user.reservations.store') }}" method="POST">
                @csrf
                
                <div class="form-group mb-6">
                    <label for="court_id" class="form-label">Pilih Lapangan</label>
                    <select name="court_id" id="court_id" class="form-input" required>
                        <option value="" data-price="0">-- Pilih Lapangan --</option>
                        @foreach($courts as $court)
                            <option value="{{ $court->id }}" data-price="{{ $court->price_per_hour }}" {{ old('court_id') == $court->id ? 'selected' : '' }}>
                                {{ $court->name }} (Rp {{ number_format($court->price_per_hour, 0, ',', '.') }}/jam)
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group mb-6">
                    <label for="reservation_date" class="form-label">Tanggal Bermain</label>
                    <input type="date" name="reservation_date" id="reservation_date" class="form-input" required min="{{ date('Y-m-d', strtotime('+1 day')) }}" value="{{ old('reservation_date') }}" style="padding: 1rem; font-size: 1.25rem; height: auto; cursor: pointer; border: 2px solid var(--primary-light);">
                    <small class="text-muted mt-1 block">Silakan klik area di atas untuk memilih tanggal.</small>
                </div>

                <div class="mb-6">
                    <label class="form-label mb-3 block">Pilih Jam Bermain <small class="text-muted">(Pilih jam mulai, lalu pilih jam selesai)</small></label>
                    <input type="hidden" name="start_time" id="start_time" required>
                    <input type="hidden" name="end_time" id="end_time" required>
                    
                    <div id="time-grid-container" class="time-grid-container">
                        <div class="col-span-full text-center p-6 bg-gray-50 border border-dashed rounded-md text-muted" style="grid-column: 1 / -1;">
                            Pilih lapangan dan tanggal terlebih dahulu untuk melihat ketersediaan jam.
                        </div>
                    </div>
                    <small class="text-muted mt-2 block" id="time-loading" style="display: none;">Memuat ketersediaan...</small>
                    
                    <div id="selection-info" class="mt-4 p-3 bg-primary-light rounded-md text-primary flex justify-between items-center" style="display: none;">
                        <div>
                            <strong>Terpilih:</strong> <span id="selected-time-range"></span>
                        </div>
                        <button type="button" id="btn-reset-time" class="btn btn-outline" style="padding: 0.25rem 0.75rem; font-size: 0.875rem; background-color: white;">Reset Pilihan</button>
                    </div>
                </div>

                <div class="form-group mb-6">
                    <label for="coach_id" class="form-label">Pilih Pelatih (Opsional)</label>
                    <select name="coach_id" id="coach_id" class="form-input" disabled style="padding: 0.75rem; font-size: 1.1rem; height: auto;">
                        <option value="">-- Pilih Waktu Selesai Terlebih Dahulu --</option>
                    </select>
                    <small class="text-muted" id="coach-loading" style="display: none;">Memuat pelatih tersedia...</small>
                </div>

                <div class="mt-8 mb-4">
                    <div class="flex justify-between items-end border-b pb-2 mb-4">
                        <div>
                            <h3 class="text-xl font-semibold">Sewa Perlengkapan Tambahan (Opsional)</h3>
                            <p class="text-muted text-sm mt-1">Pilih raket atau bola jika Anda tidak membawanya.</p>
                        </div>
                        <div style="width: 250px;">
                            <input type="text" id="search-equipment" class="form-input text-sm" placeholder="Cari perlengkapan..." style="padding: 0.5rem; height: auto;">
                        </div>
                    </div>
                    
                    <div id="equipment-container" class="grid grid-cols-1 md:grid-cols-2 gap-4 pb-4" style="max-height: 400px; overflow-y: auto; padding-right: 10px;">
                        @foreach($inventories as $inv)
                        <div class="equipment-item" data-name="{{ strtolower($inv->name) }}" style="border: 1px solid #cbd5e1; border-radius: 0.75rem; padding: 1.25rem; background-color: #f8fafc; box-shadow: 0 1px 3px rgba(0,0,0,0.05); transition: all 0.2s ease;" onmouseover="this.style.borderColor='#2563eb'; this.style.backgroundColor='#ffffff';" onmouseout="this.style.borderColor='#cbd5e1'; this.style.backgroundColor='#f8fafc';">
                            <div class="flex justify-between items-center mb-2">
                                <div>
                                    <h4 class="font-semibold text-gray-800">{{ $inv->name }}</h4>
                                    <p class="text-sm font-bold" style="color: #2563eb;">Rp {{ number_format($inv->price, 0, ',', '.') }}</p>
                                    <p class="text-xs mt-1" style="color: #64748b;">Sisa stok: <span class="font-semibold">{{ $inv->stock }}</span></p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button type="button" class="btn-minus-eq w-8 h-8 rounded-full flex items-center justify-center transition-colors" style="background-color: #e2e8f0; color: #475569;" onmouseover="this.style.backgroundColor='#cbd5e1'" onmouseout="this.style.backgroundColor='#e2e8f0'" data-id="{{ $inv->id }}"><i class="fa-solid fa-minus text-xs"></i></button>
                                    <input type="number" id="eq-qty-{{ $inv->id }}" name="inventories[{{ $inv->id }}][quantity]" min="0" max="{{ $inv->stock }}" value="{{ old('inventories.'.$inv->id.'.quantity', 0) }}" class="w-12 text-center border-none bg-transparent font-bold focus:ring-0" style="padding: 0;" readonly data-name="{{ $inv->name }}" data-price="{{ $inv->price }}">
                                    <button type="button" class="btn-plus-eq w-8 h-8 rounded-full flex items-center justify-center transition-colors" style="background-color: var(--primary); color: white;" onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'" data-id="{{ $inv->id }}" data-max="{{ $inv->stock }}"><i class="fa-solid fa-plus text-xs"></i></button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Selected Equipment Table -->
                    <div id="selected-equipment-section" class="mt-8 pt-6 border-t border-gray-200" style="display: none;">
                        <h4 class="font-semibold mb-4 text-gray-800 text-lg">Barang yang akan disewa:</h4>
                        <div class="border rounded-lg overflow-hidden shadow-sm" style="border-color: var(--border);">
                            <table class="w-full text-left border-collapse bg-white">
                                <thead style="background-color: #f8fafc;">
                                    <tr>
                                        <th class="py-3 px-4 font-semibold text-sm border-b text-gray-700" style="border-color: var(--border);">Nama Barang</th>
                                        <th class="py-3 px-4 font-semibold text-sm border-b text-center text-gray-700" style="border-color: var(--border);">Jumlah</th>
                                        <th class="py-3 px-4 font-semibold text-sm border-b text-right text-gray-700" style="border-color: var(--border);">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody id="selected-equipment-body">
                                    <!-- dynamic content -->
                                </tbody>
                                <tfoot class="bg-gray-50 border-t font-semibold" style="border-color: var(--border);">
                                    <tr>
                                        <td colspan="2" class="py-4 px-4 text-right text-gray-700 uppercase text-sm tracking-wide">Total Sewa Perlengkapan:</td>
                                        <td class="py-4 px-4 text-right text-primary text-xl" id="selected-equipment-total">Rp 0</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="mt-8 mb-4">
                    <h3 class="text-xl font-semibold border-b pb-2">Kode Promo (Opsional)</h3>
                    <p class="text-muted text-sm mt-1 mb-4">Masukkan kode promo jika Anda memilikinya.</p>
                    <div class="form-group" style="max-width: 400px;">
                        <input type="text" name="promo_code" id="promo_code" class="form-input" placeholder="Contoh: DISKON10" value="{{ old('promo_code') }}" style="text-transform: uppercase;">
                    </div>
                </div>

                <!-- Ringkasan Pembayaran -->
                <div id="booking-summary" class="card mt-8 mb-8" style="background-color: #f8fafc; border: 1px solid var(--border); display: none;">
                    <h3 class="text-xl font-semibold mb-4 border-b pb-3 text-gray-800"><i class="fa-solid fa-receipt mr-2 text-primary"></i>Ringkasan Pembayaran</h3>
                    
                    <div class="flex justify-between mb-3 text-gray-700">
                        <span>Sewa Lapangan (<span id="summary-hours">0</span> Jam)</span>
                        <span class="font-medium" id="summary-court-price">Rp 0</span>
                    </div>
                    
                    <div class="flex justify-between mb-3 text-gray-700" id="summary-coach-row" style="display: none;">
                        <span>Jasa Pelatih</span>
                        <span class="font-medium" id="summary-coach-price">Rp 0</span>
                    </div>
                    
                    <div class="flex justify-between mb-3 text-gray-700" id="summary-equipment-row" style="display: none;">
                        <span>Sewa Perlengkapan</span>
                        <span class="font-medium" id="summary-equipment-price">Rp 0</span>
                    </div>
                    
                    <div class="flex justify-between mt-4 pt-4 border-t border-gray-300">
                        <span class="text-lg font-bold text-gray-800">Total Pembayaran</span>
                        <span class="text-2xl font-bold text-primary" id="summary-total-price">Rp 0</span>
                    </div>
                </div>

                <div class="mt-8 flex justify-end gap-4">
                    <a href="{{ route('user.dashboard') }}" class="btn btn-outline">Batal</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-check mr-2"></i> Konfirmasi Reservasi
                    </button>
                </div>
            </form>
        </div>
    </main>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const courtSelect = document.getElementById('court_id');
        const dateInput = document.getElementById('reservation_date');
        const startTimeInput = document.getElementById('start_time');
        const endTimeInput = document.getElementById('end_time');
        const coachSelect = document.getElementById('coach_id');
        const loadingIndicator = document.getElementById('time-loading');
        const coachLoading = document.getElementById('coach-loading');
        const gridContainer = document.getElementById('time-grid-container');
        const selectionInfo = document.getElementById('selection-info');
        const selectedTimeRange = document.getElementById('selected-time-range');
        
        let bookedSlots = [];
        let selectedStart = null;
        let selectedEnd = null; // Represents the slot index that is the end of the range
        
        let oldStartTime = "{{ old('start_time') }}";
        let oldEndTime = "{{ old('end_time') }}";
        let oldCoachId = "{{ old('coach_id') }}";
        
        const baseHours = [
            '08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', 
            '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00', 
            '22:00', '23:00'
        ];

        function fetchBookedSlots() {
            const courtId = courtSelect.value;
            const dateStr = dateInput.value;

            if (!courtId || !dateStr) {
                gridContainer.innerHTML = '<div class="col-span-full text-center p-6 bg-gray-50 border border-dashed rounded-md text-muted" style="grid-column: 1 / -1;">Pilih lapangan dan tanggal terlebih dahulu untuk melihat ketersediaan jam.</div>';
                resetSelection();
                return;
            }

            loadingIndicator.style.display = 'block';
            gridContainer.innerHTML = '';
            resetSelection();
            coachSelect.innerHTML = '<option value="">-- Pilih Waktu Selesai Terlebih Dahulu --</option>';
            coachSelect.disabled = true;

            fetch(`/user/api/booked-slots?court_id=${courtId}&date=${dateStr}`)
                .then(res => res.json())
                .then(data => {
                    bookedSlots = data;
                    renderTimeGrid();
                    
                    if (oldStartTime) {
                        const startIndex = baseHours.indexOf(oldStartTime.substring(0, 5));
                        if (startIndex !== -1) {
                            selectedStart = startIndex;
                            if (oldEndTime) {
                                const endStr = oldEndTime.substring(0, 5);
                                if (endStr === '23:59' || endStr === '00:00') {
                                    selectedEnd = baseHours.length - 1;
                                } else {
                                    const endIndex = baseHours.indexOf(endStr);
                                    if (endIndex !== -1) {
                                        selectedEnd = endIndex - 1;
                                    }
                                }
                            }
                            updateGridVisuals();
                            updateHiddenInputs();
                        }
                        oldStartTime = null;
                        oldEndTime = null;
                    }
                })
                .catch(err => console.error(err))
                .finally(() => {
                    loadingIndicator.style.display = 'none';
                });
        }

        function isSlotBooked(timeSlot) {
            return bookedSlots.some(slot => {
                return timeSlot >= slot.start && timeSlot < slot.end;
            });
        }

        function renderTimeGrid() {
            gridContainer.innerHTML = '';
            
            baseHours.forEach((hour, index) => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.dataset.index = index;
                btn.dataset.time = hour;
                
                if (isSlotBooked(hour)) {
                    btn.disabled = true;
                    btn.className = 'time-box booked';
                    btn.innerHTML = `<span class="time-text">${hour}</span><span class="status-text">Booked</span>`;
                } else {
                    btn.className = 'time-box';
                    btn.innerHTML = `<span class="time-text">${hour}</span><span class="status-text opacity-70">Tersedia</span>`;
                    btn.addEventListener('click', () => handleSlotClick(index));
                }
                
                gridContainer.appendChild(btn);
            });
        }

        function handleSlotClick(index) {
            // Logic:
            // 1. If nothing selected, select as start
            // 2. If start selected but no end, and clicked index > start, try to select as end (validate range)
            // 3. If clicked index < start, make it the new start
            // 4. If both start and end selected, reset and make clicked index the new start
            
            if (selectedStart === null || (selectedStart !== null && selectedEnd !== null)) {
                // Set new start
                selectedStart = index;
                selectedEnd = null;
            } else if (index === selectedStart) {
                // Deselect if clicking the same start
                selectedStart = null;
                selectedEnd = null;
            } else if (index > selectedStart) {
                // Maksimal 5 jam = (index - selectedStart) <= 4
                if (index - selectedStart >= 5) {
                    alert('Maksimal booking adalah 5 jam.');
                    return;
                }

                // Try to set end. Validate that there are no booked slots in between
                let valid = true;
                for (let i = selectedStart; i <= index; i++) {
                    if (isSlotBooked(baseHours[i])) {
                        valid = false;
                        break;
                    }
                }
                
                if (valid) {
                    selectedEnd = index;
                } else {
                    alert('Terdapat jam yang sudah di-booking pada rentang waktu tersebut.');
                    selectedStart = index; // Reset start to the clicked one
                }
            } else {
                // Clicked earlier than start
                selectedStart = index;
            }
            
            updateGridVisuals();
            updateHiddenInputs();
        }

        function updateGridVisuals() {
            const buttons = gridContainer.querySelectorAll('.time-box');
            
            buttons.forEach((btn, index) => {
                if (btn.disabled) return; // Skip booked slots
                
                // Reset styling
                btn.className = 'time-box';
                
                if (selectedStart !== null && selectedEnd === null) {
                    if (index === selectedStart) btn.classList.add('selected-start');
                } else if (selectedStart !== null && selectedEnd !== null) {
                    if (index === selectedStart) btn.classList.add('selected-start');
                    else if (index === selectedEnd) btn.classList.add('selected-end');
                    else if (index > selectedStart && index < selectedEnd) btn.classList.add('selected-between');
                }
            });
        }

        function updateHiddenInputs() {
            if (selectedStart !== null) {
                const startTime = baseHours[selectedStart];
                let endTime = '';
                
                if (selectedEnd !== null) {
                    // End time is the end of the selected end slot
                    const nextIndex = selectedEnd + 1;
                    endTime = (nextIndex < baseHours.length) ? baseHours[nextIndex] : '23:59';
                } else {
                    // Only 1 hour selected
                    const nextIndex = selectedStart + 1;
                    endTime = (nextIndex < baseHours.length) ? baseHours[nextIndex] : '23:59';
                }
                
                startTimeInput.value = startTime;
                endTimeInput.value = endTime;
                
                const endDisplay = endTime === '23:59' ? '00:00' : endTime;
                selectionInfo.style.display = 'block';
                selectedTimeRange.textContent = `${startTime} - ${endDisplay}`;
                
                calculateGrandTotal();
                
                // Once both start and end are figured out (even just 1 hour), fetch coaches
                fetchAvailableCoaches();
            } else {
                resetSelection();
            }
        }
        
        function resetSelection() {
            selectedStart = null;
            selectedEnd = null;
            startTimeInput.value = '';
            endTimeInput.value = '';
            selectionInfo.style.display = 'none';
            selectedTimeRange.textContent = '';
            coachSelect.innerHTML = '<option value="" data-price="0">-- Pilih Waktu Selesai Terlebih Dahulu --</option>';
            coachSelect.disabled = true;
            
            if (gridContainer.children.length > 1) {
                updateGridVisuals();
            }
            calculateGrandTotal();
        }

        function fetchAvailableCoaches() {
            const dateStr = dateInput.value;
            const startStr = startTimeInput.value;
            const endStr = endTimeInput.value;

            if (!dateStr || !startStr || !endStr) return;

            coachLoading.style.display = 'block';
            coachSelect.disabled = true;

            fetch(`/user/api/available-coaches?date=${dateStr}&start_time=${startStr}&end_time=${endStr}`)
                .then(res => res.json())
                .then(data => {
                    coachSelect.innerHTML = '<option value="" data-price="0">-- Tanpa Pelatih --</option>';
                    data.forEach(coach => {
                        const opt = document.createElement('option');
                        opt.value = coach.id;
                        opt.dataset.price = coach.price_per_hour;
                        opt.textContent = `${coach.name} (Sisa Slot: ${coach.remaining_slots} | Rp ${new Intl.NumberFormat('id-ID').format(coach.price_per_hour)}/jam)`;
                        coachSelect.appendChild(opt);
                    });
                    coachSelect.disabled = false;
                    
                    if (oldCoachId) {
                        coachSelect.value = oldCoachId;
                        oldCoachId = null;
                    }
                    
                    calculateGrandTotal();
                })
                .catch(err => console.error(err))
                .finally(() => {
                    coachLoading.style.display = 'none';
                });
        }

        courtSelect.addEventListener('change', fetchBookedSlots);
        dateInput.addEventListener('change', fetchBookedSlots);
        
        // Reset Time Selection
        document.getElementById('btn-reset-time').addEventListener('click', resetSelection);

        // Equipment Live Search
        const searchEquipment = document.getElementById('search-equipment');
        const equipmentItems = document.querySelectorAll('.equipment-item');
        
        searchEquipment.addEventListener('input', function(e) {
            const keyword = e.target.value.toLowerCase();
            equipmentItems.forEach(item => {
                const name = item.getAttribute('data-name');
                if (name.includes(keyword)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });

        // Equipment Plus Minus Logic
        const btnPlus = document.querySelectorAll('.btn-plus-eq');
        const btnMinus = document.querySelectorAll('.btn-minus-eq');
        const selectedEqSection = document.getElementById('selected-equipment-section');
        const selectedEqBody = document.getElementById('selected-equipment-body');
        const selectedEqTotal = document.getElementById('selected-equipment-total');

        function updateSelectedEquipment() {
            selectedEqBody.innerHTML = '';
            let total = 0;
            let hasSelected = false;

            document.querySelectorAll('input[id^="eq-qty-"]').forEach(input => {
                const qty = parseInt(input.value);
                if (qty > 0) {
                    hasSelected = true;
                    const name = input.getAttribute('data-name');
                    const price = parseFloat(input.getAttribute('data-price'));
                    const subtotal = qty * price;
                    total += subtotal;

                    const itemId = input.id.replace('eq-qty-', '');
                    const tr = document.createElement('tr');
                    tr.className = 'border-b last:border-b-0 hover:bg-gray-50 transition-colors';
                    tr.style.borderColor = 'var(--border)';
                    tr.innerHTML = `
                        <td class="py-3 px-4 text-sm font-medium">
                            ${name} <br>
                            <span class="text-xs text-muted font-normal">@ Rp ${new Intl.NumberFormat('id-ID').format(price)}</span>
                        </td>
                        <td class="py-3 px-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button type="button" class="w-6 h-6 rounded-full flex items-center justify-center bg-gray-200 text-gray-600 hover:bg-gray-300" onclick="document.querySelector('.btn-minus-eq[data-id=\\'${itemId}\\']').click()"><i class="fa-solid fa-minus text-[10px]" style="font-size: 0.6rem;"></i></button>
                                <span class="font-semibold w-4 text-center">${qty}</span>
                                <button type="button" class="w-6 h-6 rounded-full flex items-center justify-center bg-primary text-white hover:opacity-90" onclick="document.querySelector('.btn-plus-eq[data-id=\\'${itemId}\\']').click()"><i class="fa-solid fa-plus text-[10px]" style="font-size: 0.6rem;"></i></button>
                            </div>
                        </td>
                        <td class="py-3 px-4 text-right font-semibold">Rp ${new Intl.NumberFormat('id-ID').format(subtotal)}</td>
                    `;
                    selectedEqBody.appendChild(tr);
                }
            });

            selectedEqTotal.textContent = `Rp ${new Intl.NumberFormat('id-ID').format(total)}`;
            selectedEqSection.style.display = hasSelected ? 'block' : 'none';
            
            globalEquipmentTotal = total;
            calculateGrandTotal();
        }

        let globalEquipmentTotal = 0;

        function calculateGrandTotal() {
            const summaryBox = document.getElementById('booking-summary');
            const summaryHours = document.getElementById('summary-hours');
            const summaryCourtPrice = document.getElementById('summary-court-price');
            const summaryCoachRow = document.getElementById('summary-coach-row');
            const summaryCoachPrice = document.getElementById('summary-coach-price');
            const summaryEqRow = document.getElementById('summary-equipment-row');
            const summaryEqPrice = document.getElementById('summary-equipment-price');
            const summaryTotalPrice = document.getElementById('summary-total-price');

            if (!courtSelect.value || selectedStart === null) {
                summaryBox.style.display = 'none';
                return;
            }
            summaryBox.style.display = 'block';

            // Calculate hours
            let hours = 1;
            if (selectedEnd !== null) {
                hours = selectedEnd - selectedStart + 1;
            }
            summaryHours.textContent = hours;

            // Court price
            let courtPricePerHour = 0;
            if (courtSelect.selectedIndex >= 0) {
                courtPricePerHour = parseFloat(courtSelect.options[courtSelect.selectedIndex].getAttribute('data-price')) || 0;
            }
            const totalCourtPrice = courtPricePerHour * hours;
            summaryCourtPrice.textContent = `Rp ${new Intl.NumberFormat('id-ID').format(totalCourtPrice)}`;

            // Coach price
            let totalCoachPrice = 0;
            if (coachSelect.value && coachSelect.selectedIndex >= 0) {
                const coachPricePerHour = parseFloat(coachSelect.options[coachSelect.selectedIndex].getAttribute('data-price')) || 0;
                totalCoachPrice = coachPricePerHour * hours;
                if (totalCoachPrice > 0) {
                    summaryCoachRow.style.display = 'flex';
                    summaryCoachPrice.textContent = `Rp ${new Intl.NumberFormat('id-ID').format(totalCoachPrice)}`;
                } else {
                    summaryCoachRow.style.display = 'none';
                }
            } else {
                summaryCoachRow.style.display = 'none';
            }

            // Equipment price
            if (globalEquipmentTotal > 0) {
                summaryEqRow.style.display = 'flex';
                summaryEqPrice.textContent = `Rp ${new Intl.NumberFormat('id-ID').format(globalEquipmentTotal)}`;
            } else {
                summaryEqRow.style.display = 'none';
            }

            // Grand Total
            const grandTotal = totalCourtPrice + totalCoachPrice + globalEquipmentTotal;
            summaryTotalPrice.textContent = `Rp ${new Intl.NumberFormat('id-ID').format(grandTotal)}`;
        }

        courtSelect.addEventListener('change', calculateGrandTotal);
        coachSelect.addEventListener('change', calculateGrandTotal);

        btnPlus.forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const max = parseInt(this.getAttribute('data-max'));
                const input = document.getElementById(`eq-qty-${id}`);
                let val = parseInt(input.value);
                if (val < max) {
                    input.value = val + 1;
                    updateSelectedEquipment();
                } else {
                    alert('Stok tidak mencukupi');
                }
            });
        });

        btnMinus.forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const input = document.getElementById(`eq-qty-${id}`);
                let val = parseInt(input.value);
                if (val > 0) {
                    input.value = val - 1;
                    updateSelectedEquipment();
                }
            });
        });
        
        // Form submission validation
        document.querySelector('form').addEventListener('submit', function(e) {
            if (!startTimeInput.value || !endTimeInput.value) {
                e.preventDefault();
                alert('Silakan pilih jam bermain terlebih dahulu.');
            }
        });
        
        // Restore state if old values exist
        updateSelectedEquipment();
        if (courtSelect.value && dateInput.value) {
            fetchBookedSlots();
        }
    });
</script>
@endsection

