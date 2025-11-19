<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Translation Test - Public</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background: #f5f5f5;
        }
        .test-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #2E8B57;
        }
        .test-section {
            background: #f9f9f9;
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
            border-left: 4px solid #2E8B57;
        }
        button {
            background: #2E8B57;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin: 5px;
        }
        button:hover {
            background: #1A5D34;
        }
        .result {
            margin-top: 10px;
            padding: 10px;
            background: white;
            border-left: 4px solid #2E8B57;
            white-space: pre-wrap;
            font-family: monospace;
            font-size: 12px;
        }
        .success {
            color: #28a745;
        }
        .error {
            color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="test-container">
        <h1><i class="fas fa-language"></i> Public Translation API Test</h1>

        <div class="test-section">
            <h3>Test Status</h3>
            <p><strong>CSRF Token:</strong> <span id="csrf-token">{{ csrf_token() }}</span></p>
            <p><strong>Test URL:</strong> /accessibility/translate-batch</p>
        </div>

        <div class="test-section">
            <h3>Run Tests</h3>
            <button onclick="testTranslationAPI()">Test Translation API</button>
            <button onclick="testQuickTool()">Test Quick Tool API</button>
            <button onclick="clearResults()">Clear Results</button>
        </div>

        <div class="test-section">
            <h3>Results:</h3>
            <div id="results" class="result">Click a button above to run tests...</div>
        </div>
    </div>

    <script>
        function log(message, type = 'info') {
            const resultsDiv = document.getElementById('results');
            const timestamp = new Date().toLocaleTimeString();
            const icon = type === 'success' ? '✓' : type === 'error' ? '✗' : 'ℹ';
            const className = type === 'success' ? 'success' : type === 'error' ? 'error' : '';
            resultsDiv.innerHTML += `<span class="${className}">[${timestamp}] ${icon} ${message}</span>\n`;
        }

        function clearResults() {
            document.getElementById('results').innerHTML = 'Results cleared...\n';
        }

        async function testTranslationAPI() {
            clearResults();
            log('Starting translation API test...', 'info');

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            log('CSRF Token: ' + csrfToken.substring(0, 20) + '...', 'info');

            const testTexts = ['language', 'small', 'medium', 'large', 'accessibility'];
            log('Testing texts: ' + testTexts.join(', '), 'info');

            try {
                log('Sending POST request to /accessibility/translate-batch...', 'info');

                const response = await fetch('/accessibility/translate-batch', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        texts: testTexts,
                        target_lang: 'tl'
                    })
                });

                log('Response status: ' + response.status, response.ok ? 'success' : 'error');

                if (!response.ok) {
                    const errorText = await response.text();
                    log('Error response: ' + errorText, 'error');
                    return;
                }

                const data = await response.json();
                log('Response received successfully!', 'success');
                log('Full response: ' + JSON.stringify(data, null, 2), 'info');

                if (data.success && data.translations) {
                    log('Translations received:', 'success');
                    Object.keys(data.translations).forEach(key => {
                        log(`  ${key} → ${data.translations[key]}`, 'success');
                    });
                } else {
                    log('Unexpected response format', 'error');
                }

            } catch (error) {
                log('Network error: ' + error.message, 'error');
                log('Stack: ' + error.stack, 'error');
            }
        }

        async function testQuickTool() {
            clearResults();
            log('Starting quick-tool API test...', 'info');

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            try {
                const response = await fetch('/accessibility/quick-tool', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        language: 'tl'
                    })
                });

                log('Response status: ' + response.status, response.ok ? 'success' : 'error');

                if (!response.ok) {
                    const errorText = await response.text();
                    log('Error response: ' + errorText, 'error');
                    return;
                }

                const data = await response.json();
                log('Response: ' + JSON.stringify(data, null, 2), 'success');

            } catch (error) {
                log('Network error: ' + error.message, 'error');
            }
        }
    </script>
</body>
</html>
