<?php
// =====================================================================
// PHP FRONTEND SETUP LOGIC (Hanya untuk TAMPILAN AWAL)
// =====================================================================

$scan_data = null;
$error_message = null;

// Variabel ini hanya digunakan untuk nilai awal input.
$url_to_scan = isset($_POST["url_input"]) ? $_POST["url_input"] : '';

// Variabel ini tidak lagi diperlukan karena rendering dilakukan oleh JS
$scan_data_json = 'null';
$error_message_js = 'null';
$scan_url_js = json_encode($url_to_scan); 
$initial_status_message = '';

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>F55119093</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f7f6;
            color: #333;
            line-height: 1.6;
            padding: 20px;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #2c3e50;
            font-weight: 700;
            margin-bottom: 25px;
        }

        /* Form Styling */
        .scan-form {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
        }

        .scan-form input[type="text"] {
            flex-grow: 1;
            padding: 12px 15px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        .scan-form input[type="text"]:focus {
            border-color: #3498db;
            outline: none;
        }

        .scan-form button {
            padding: 12px 25px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: background-color 0.3s;
        }

        .scan-form button:hover {
            background-color: #2980b9;
        }

        /* Loading/Status */
        #status-message {
            text-align: center;
            padding: 15px;
            border-radius: 8px;
            font-weight: 600;
            margin-bottom: 20px;
            display: none; /* Default hidden */
        }
        .loading { background-color: #f1c40f50; color: #e67e22; }
        .success { background-color: #2ecc7150; color: #27ae60; }
        .error { background-color: #e74c3c50; color: #c0392b; }

        /* Result Summary */
        .summary-card {
            background: #ecf0f1;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-around;
            align-items: center;
            text-align: center;
            box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.05);
        }

        .grade-circle {
            width: 120px;
            height: 120px;
            line-height: 120px;
            border-radius: 50%;
            color: black; /* Diubah ke white agar kontras */
            font-size: 36px;
            font-weight: 700;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            margin-bottom: 10px;
        }

        .summary-info {
            font-size: 1.1em;
            font-weight: 600;
            color: #2c3e50;
        }

        /* Color Grading */
        .Aplus, .A { background-color: #27ae60; }
        .Bplus, .B, .A- { background-color: #2ecc71; }
        .Cplus, .C, .B- { background-color: #f39c12; }
        .Dplus, .D, .C- { background-color: #e67e22; }
        .F { background-color: #e74c3c; }


        /* Detailed Analysis */
        #detailed-analysis {
            display: none; /* Default hidden */
        }

        h2 {
            color: #2c3e50;
            border-bottom: 3px solid #3498db;
            padding-bottom: 5px;
            margin-top: 30px;
            font-weight: 600;
        }

        .header-section {
            background: #ffffff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin-top: 15px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .header-title {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            font-size: 1.2em;
            font-weight: 700;
        }

        .header-title .score {
            font-size: 1.5em;
            font-weight: 700;
        }

        .status-pass { color: #27ae60; }
        .status-fail { color: #e74c3c; }

        .detail-box {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 6px;
            margin-top: 10px;
            border-left: 5px solid;
        }

        .detail-box.fail { border-left-color: #e74c3c; }
        .detail-box.pass { border-left-color: #27ae60; }

        .raw-header {
            background: #34495e;
            color: #ecf0f1;
            padding: 10px;
            border-radius: 4px;
            font-family: monospace;
            white-space: pre-wrap;
            word-break: break-all;
            margin-top: 10px;
            font-size: 0.9em;
        }

        ul {
            list-style: none;
            padding-left: 0;
        }
        ul li:before {
            content: "•";
            color: #3498db;
            font-weight: bold;
            display: inline-block;
            width: 1em;
            margin-left: -1em;
        }

        /* Footer */
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 0.9em;
            color: #7f8c8d;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>SISTEM ANALISIS HTTP SECURITY HEADERS SCANNER RISK-BASED ALGORITHM</h1>
    <p style="text-align: center; color: #7f8c8d;">Berdasarkan OWASP Best Practices.</p>

    <form id="scan-form" class="scan-form">
        <input type="text" name="url_input" id="url-input" 
               placeholder="Masukkan URL, contoh: https://example.com" required 
               value="<?php echo htmlspecialchars($url_to_scan); ?>">
        <button type="submit" id="scan-button">ANALISIS</button>
    </form>
    
    <div id="status-message" style="display: none;">
        <?php echo htmlspecialchars($initial_status_message); ?>
    </div>

    <div id="result-summary" style="display: none;">
        <h2>Ringkasan Hasil</h2>
        <div class="summary-card">
            <div>
                <div id="grade-circle" class="grade-circle">N/A</div>
                <div class="summary-info">Grade Keamanan</div>
            </div>
            <div>
                <p><strong id="score-text">0/100</strong></p>
                <div class="summary-info">Skor Total</div>
            </div>
            <div>
                <p><strong id="pct-text">0.00%</strong></p>
                <div class="summary-info">Persentase</div>
            </div>
        </div>

        <p><strong>URL yang dianalisis:</strong> <span id="analyzed-url"></span></p>
        <p><strong>Status Code:</strong> <span id="status-code"></span></p>
    </div>

    <div id="detailed-analysis" style="display: none;">
        <h2>Analisis Detail Header</h2>
        <div id="analysis-results">
            </div>
    </div>
</div>

<div class="footer">
SEBAGAI TIKET SARJANA ANDI NURUN MUHAMMAD AKBAR F55119093
</div>

<script>
    // --- 1. DEFINISI KONSTANTA DAN ELEMEN DOM ---
    const API_ENDPOINT = "http://localhost:8000/scan"; 
    
    const scanForm = document.getElementById('scan-form');
    const urlInput = document.getElementById('url-input');
    const scanButton = document.getElementById('scan-button');
    const statusMessage = document.getElementById('status-message');
    const resultSummary = document.getElementById('result-summary');
    const detailedAnalysis = document.getElementById('detailed-analysis');
    const analysisResults = document.getElementById('analysis-results');

    const gradeCircle = document.getElementById('grade-circle');
    const scoreText = document.getElementById('score-text');
    const pctText = document.getElementById('pct-text');
    const analyzedUrl = document.getElementById('analyzed-url');
    const statusCode = document.getElementById('status-code');

    // --- PENTING: UPDATE MAX SCORES DENGAN BOBOT BARU (Total 100) ---
    // BOBOT INI HARUS SINKRON DENGAN HEADER_WEIGHTS DI header_analyzer.py
    const MAX_SCORES = {
        "Content-Security-Policy": 35,
        "Strict-Transport-Security": 30,
        "X-Content-Type-Options": 15,
        "Cache-Control": 10,
        "X-Frame-Options": 5,
        "Permissions-Policy": 5,
    };
    const MAX_TOTAL_SCORE = 100;
    // --- END MAX SCORES ---

    // --- 2. FUNGSI UNTUK MERENDER DETAIL HEADER ---
    function renderHeaderDetails(headerName, details) {
        const statusClass = details.status.toLowerCase().includes('fail') ? 'fail' : 'pass';
        
        // Mengambil skor maksimum dari map yang baru
        const maxScore = MAX_SCORES[headerName] || 0;

        // Render Alasan dan Rekomendasi
        const renderList = (items) => {
            let list = '';
            // Memastikan items adalah array sebelum di-loop
            if (Array.isArray(items)) {
                items.forEach(item => {
                    // Mengganti **teks** menjadi <strong>teks</strong> untuk penekanan
                    const formattedItem = item.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
                    list += `<li>${formattedItem}</li>`;
                });
            }
            return `<ul>${list}</ul>`;
        };

        const rawHeader = details.raw 
            ? `<p style="margin-top: 10px;"><strong>Raw Header Value:</strong></p><pre class="raw-header">${details.raw}</pre>`
            : '';
        
        // Modifikasi display score: menampilkan skor aktual / skor maksimum header
        return `
            <div class="header-section">
                <div class="header-title">
                    <span>${headerName}</span>
                    <div>
                        <span class="score status-${statusClass}">${details.score}</span> /
                        <span style="font-size: 1em; font-weight: 700; color: #7f8c8d;">${maxScore} (${details.status})</span>
                    </div>
                </div>

                <div class="detail-box ${statusClass}">
                    <p style="margin-top: 10px;"><strong>Alasan:</strong></p>
                    ${renderList(details.reason)}

                    <p style="margin-top: 10px;"><strong>Rekomendasi:</strong></p>
                    ${renderList(details.recommendation)}

                    ${rawHeader}
                </div>
            </div>
        `;
    }

    // --- 3. EVENT LISTENER FORM SUBMIT (AJAX/FETCH) ---
    scanForm.addEventListener('submit', async (e) => {
        e.preventDefault(); 
        
        const url = urlInput.value.trim();
        if (!url) return;

        // Reset tampilan
        resultSummary.style.display = 'none';
        detailedAnalysis.style.display = 'none';
        analysisResults.innerHTML = '';
        gradeCircle.className = 'grade-circle'; 

        // Tampilkan status loading
        statusMessage.className = 'loading';
        statusMessage.textContent = 'Memindai URL... Tunggu sebentar.';
        statusMessage.style.display = 'block';
        scanButton.disabled = true;

        try {
            const startTime = performance.now();
            
            // 1. PANGGILAN FETCH ASINKRON LANGSUNG KE FASTAPI
            const apiUrl = `${API_ENDPOINT}?url=${encodeURIComponent(url)}`;
            const response = await fetch(apiUrl);

            const data = await response.json();
            const durationMs = performance.now() - startTime;
            const duration = (durationMs / 1000).toFixed(2);

            if (!response.ok) {
                // Tangani error HTTP status non-200 (error dari FastAPI)
                const errorDetail = data.detail || 'Terjadi kesalahan saat memproses data.';
                // Jika error adalah 500 dari API, tampilkan detailnya
                throw new Error(`API Error: HTTP Status ${response.status} - ${errorDetail}`);
            }

            // 2. Jika berhasil, update Ringkasan
            gradeCircle.textContent = data.grade;
            // Hapus kelas lama, tambahkan kelas baru
            gradeCircle.className = 'grade-circle';
            gradeCircle.classList.add(data.grade);
            
            // Menggunakan data total_score dari backend
            const totalScore = data.total_score || 0; 
            const percentage = (totalScore / MAX_TOTAL_SCORE * 100).toFixed(2);

            scoreText.textContent = `${totalScore}/${MAX_TOTAL_SCORE}`; 
            pctText.textContent = `${percentage}%`;
            analyzedUrl.textContent = data.scan_url;
            statusCode.textContent = data.http_status;
            
            // 3. Update Detail Analisis
            let detailsHtml = '';
            for (const headerName in data.security_headers) {
                // Pastikan hanya 6 header yang diproses sesuai dengan MAX_SCORES
                if (MAX_SCORES.hasOwnProperty(headerName)) {
                    detailsHtml += renderHeaderDetails(headerName, data.security_headers[headerName]);
                }
            }
            analysisResults.innerHTML = detailsHtml;

            // 4. Update status sukses dan tampilkan hasil
            statusMessage.className = 'success';
            statusMessage.textContent = `Pemindaian selesai dalam ${duration} detik.`;
            resultSummary.style.display = 'block';
            detailedAnalysis.style.display = 'block';

        } catch (error) {
            // Tangani error jaringan (misal, FastAPI belum jalan) atau error dari blok try
            console.error('Scan Error:', error);
            statusMessage.className = 'error';
            statusMessage.textContent = `Pindai Gagal: ${error.message}. (Pastikan server http://localhost:8000 berjalan)`;
        } finally {
            scanButton.disabled = false;
        }
    });

</script>
</body>
</html>