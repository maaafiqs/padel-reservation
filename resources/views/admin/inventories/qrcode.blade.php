<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code - {{ $inventory->name }}</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            background-color: #f8f9fa;
        }
        .qr-card-container {
            text-align: center;
        }
        .qr-card {
            background-color: white;
            border: 2px dashed #ccc;
            padding: 30px;
            text-align: center;
            width: 300px;
            /* No shadow on the actual card so the JPG looks clean */
            margin: 0 auto 20px auto;
        }
        .qr-image {
            margin: 15px auto;
            width: 200px;
            height: 200px;
        }
        .qr-title {
            font-size: 1.25rem;
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }
        .qr-code-text {
            font-size: 1rem;
            color: #666;
            margin-bottom: 15px;
        }
        .download-btn {
            padding: 12px 24px;
            background-color: #2563eb;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: bold;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .download-btn:hover {
            background-color: #1d4ed8;
        }
    </style>
</head>
<body>

    <div class="qr-card-container">
        <!-- This is the element we will convert to JPG -->
        <div class="qr-card" id="qr-card-element">
            <div class="qr-title">{{ $inventory->name }}</div>
            <div class="qr-code-text">{{ $inventory->item_code }}</div>
            
            <div class="qr-image">
                {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(200)->generate($qrData) !!}
            </div>
            
            <div style="font-size: 0.85rem; color: #888; margin-top: 10px;">
                Rp {{ number_format($inventory->price, 0, ',', '.') }}
            </div>
        </div>

        <button class="download-btn" onclick="downloadJPG()">Unduh sebagai JPG</button>
    </div>

    <script>
        function downloadJPG() {
            const element = document.getElementById('qr-card-element');
            const btn = document.querySelector('.download-btn');
            const originalText = btn.innerText;
            
            btn.innerText = "Memproses...";
            btn.disabled = true;

            html2canvas(element, {
                scale: 3, // Higher scale for better print quality
                backgroundColor: "#ffffff"
            }).then(canvas => {
                const link = document.createElement('a');
                link.download = 'QR_{{ $inventory->item_code }}.jpg';
                link.href = canvas.toDataURL('image/jpeg', 0.95);
                link.click();
                
                btn.innerText = originalText;
                btn.disabled = false;
            });
        }
    </script>
</body>
</html>
