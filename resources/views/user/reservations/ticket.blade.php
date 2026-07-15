<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Ticket Reservasi #{{ str_pad($reservation->id, 4, '0', STR_PAD_LEFT) }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #f8fafc;
            color: #0f172a;
            margin: 0;
            padding: 2rem;
            display: flex;
            justify-content: center;
        }
        .ticket {
            background-color: #ffffff;
            width: 100%;
            max-width: 600px;
            border-radius: 1rem;
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1);
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }
        .ticket-header {
            background-color: #2563eb;
            color: white;
            padding: 2rem;
            text-align: center;
        }
        .ticket-header h1 {
            margin: 0 0 0.5rem 0;
            font-size: 2rem;
            font-weight: 700;
        }
        .ticket-header p {
            margin: 0;
            opacity: 0.9;
        }
        .ticket-body {
            padding: 2rem;
        }
        .ticket-section {
            margin-bottom: 1.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px dashed #cbd5e1;
        }
        .ticket-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.75rem;
        }
        .detail-label {
            color: #64748b;
        }
        .detail-value {
            font-weight: 600;
            text-align: right;
        }
        .badge {
            background-color: #dcfce7;
            color: #166534;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 600;
            display: inline-block;
        }
        .total-price {
            font-size: 1.5rem;
            color: #2563eb;
            font-weight: 700;
        }
        .ticket-footer {
            background-color: #f8fafc;
            padding: 1.5rem;
            text-align: center;
            font-size: 0.875rem;
            color: #64748b;
            border-top: 1px solid #e2e8f0;
        }
        @media print {
            body {
                background-color: white;
                padding: 0;
            }
            .ticket {
                box-shadow: none;
                border: 2px solid #e2e8f0;
                border-radius: 0;
                width: 100%;
                max-width: 100%;
            }
            /* Hide print dialog buttons if any */
        }
    </style>
</head>
<body>

    <div class="ticket">
        <div class="ticket-header">
            <h1>Maaafiqs Padel</h1>
            <p>E-Ticket Bukti Reservasi</p>
        </div>
        
        <div class="ticket-body">
            <div class="ticket-section">
                <div class="detail-row">
                    <span class="detail-label">Booking ID</span>
                    <span class="detail-value text-primary">{{ $reservation->reservation_code ?? '#RES-' . str_pad($reservation->id, 4, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Status</span>
                    <span class="detail-value"><span class="badge">Terkonfirmasi</span></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Nama Pemesan</span>
                    <span class="detail-value">{{ $reservation->user->name }}</span>
                </div>
            </div>

            <div class="ticket-section">
                @php
                    $start = \Carbon\Carbon::parse($reservation->start_time);
                    $end = \Carbon\Carbon::parse($reservation->end_time);
                    $durationInHours = $start->diffInMinutes($end) / 60;
                @endphp
                <div class="detail-row">
                    <span class="detail-label">Tanggal Main</span>
                    <span class="detail-value">{{ \Carbon\Carbon::parse($reservation->reservation_date)->translatedFormat('l, d F Y') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Waktu Main</span>
                    <span class="detail-value">{{ \Carbon\Carbon::parse($reservation->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($reservation->end_time)->format('H:i') }} ({{ $durationInHours }} Jam)</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Sewa Lapangan ({{ $reservation->court->name }})</span>
                    <span class="detail-value">
                        @php
                            $courtPrice = $reservation->court->price_per_hour * $durationInHours;
                        @endphp
                        Rp {{ number_format($courtPrice, 0, ',', '.') }}
                    </span>
                </div>
                @if($reservation->coach)
                <div class="detail-row">
                    <span class="detail-label">Sewa Pelatih ({{ $reservation->coach->name }})</span>
                    <span class="detail-value">
                        @php
                            $coachPrice = $reservation->coach->price_per_hour * $durationInHours;
                        @endphp
                        Rp {{ number_format($coachPrice, 0, ',', '.') }}
                    </span>
                </div>
                @endif
            </div>

            @if($reservation->inventories->count() > 0)
            <div class="ticket-section">
                <div style="font-weight: 600; color: #0f172a; margin-bottom: 0.75rem;">Sewa Perlengkapan:</div>
                @foreach($reservation->inventories as $inv)
                <div class="detail-row" style="margin-bottom: 0.25rem;">
                    <span class="detail-label">{{ $inv->name }} (x{{ $inv->pivot->quantity }})</span>
                    <span class="detail-value">Rp {{ number_format($inv->pivot->price * $inv->pivot->quantity, 0, ',', '.') }}</span>
                </div>
                @endforeach
            </div>
            @endif

            <div class="ticket-section" style="border-bottom: none; margin-bottom: 0; padding-bottom: 0;">
                @if($reservation->discount_amount > 0)
                <div class="detail-row" style="margin-bottom: 0.5rem;">
                    <span class="detail-label" style="font-size: 1rem; color: #64748b;">Subtotal</span>
                    <span class="detail-value" style="font-size: 1rem;">Rp {{ number_format($reservation->total_price, 0, ',', '.') }}</span>
                </div>
                <div class="detail-row" style="margin-bottom: 0.75rem;">
                    <span class="detail-label" style="font-size: 1rem; color: #166534;">Diskon ({{ $reservation->discount_code }})</span>
                    <span class="detail-value" style="font-size: 1rem; color: #166534;">- Rp {{ number_format($reservation->discount_amount, 0, ',', '.') }}</span>
                </div>
                @endif
                <div class="detail-row">
                    <span class="detail-label" style="font-size: 1.125rem; font-weight: 500; color: #0f172a;">Total Pembayaran</span>
                    <span class="detail-value total-price">Rp {{ number_format($reservation->final_price, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <div class="ticket-footer">
            <p style="margin: 0 0 0.5rem 0;">Harap tunjukkan e-ticket ini kepada petugas saat Anda tiba di lokasi.</p>
            <p style="margin: 0;">Dicetak pada: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
        </div>
    </div>

    <script>
        // Otomatis memicu dialog print saat halaman dimuat
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</body>
</html>
