<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Translation Test - Fixed</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .test-card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        button {
            background: #2E8B57;
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin: 10px;
        }
        button:hover {
            background: #1A5D34;
        }
        .result {
            background: #f9f9f9;
            padding: 15px;
            border-left: 4px solid #2E8B57;
            margin-top: 20px;
            white-space: pre-wrap;
            font-family: monospace;
            max-height: 400px;
            overflow-y: auto;
        }
        .translatable {
            font-size: 24px;
            padding: 20px;
            background: #e8f5e9;
            border-radius: 5px;
            margin: 10px 0;
        }
        .success { color: green; }
        .error { color: red; }
        .warning { color: orange; }
    </style>
</head>
<body>
    <div class="test-card">
        <h1>🌐 Translation System Test</h1>
        <p><strong>CSRF Token Status:</strong> <span style="color: green;">✅ Valid</span></p>
        <p>This page tests the Tagalog translation system with proper CSRF protection.</p>
        
        <h3>Test Elements:</h3>
        <div class="translatable">
            <span data-translate="home">Home</span> | 
            <span data-translate="about_us">About Us</span> | 
            <span data-translate="contact_us">Contact Us</span>
        </div>
        
        <div class="translatable">
            <span data-translate="your_abilities">Your Abilities</span>, 
            <span data-translate="our_priority">Our Priority</span>
        </div>
        
        <div>
            <button onclick="testTranslation('tl')">🇵🇭 Translate to Tagalog</button>
            <button onclick="testTranslation('en')">🇺🇸 Translate to English</button>
            <button onclick="clearLog()">🗑️ Clear Log</button>
        </div>
        
        <div class="result" id="log">
            Click a button to test translation...
        </div>
    </div>

    <script>
        let logs = [];

        function log(message, type = 'info') {
            const timestamp = new Date().toLocaleTimeString();
            const icon = type === 'success' ? '✅' : type === 'error' ? '❌' : type === 'warning' ? '⚠️' : 'ℹ️';
            const className = type === 'success' ? 'success' : type === 'error' ? 'error' : type === 'warning' ? 'warning' : '';
            logs.push(`<span class="${className}">[${timestamp}] ${icon} ${message}</span>`);
            document.getElementById('log').innerHTML = logs.join('\n');
        }

        function clearLog() {
            logs = [];
            document.getElementById('log').innerHTML = 'Log cleared...';
        }

        async function testTranslation(targetLang) {
            clearLog();
            log(`━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━`);
            log(`🧪 TESTING TRANSLATION TO: ${targetLang}`);
            log(`━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━`);

            // Check CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (csrfToken) {
                log(`🔐 CSRF Token: ${csrfToken.substring(0, 20)}...`, 'success');
            } else {
                log(`❌ CSRF Token NOT FOUND!`, 'error');
                return;
            }

            // Find all translatable elements
            const elements = document.querySelectorAll('[data-translate]');
            log(`📝 Found ${elements.length} elements with data-translate`);

            const keys = [];
            const elementMap = {};

            elements.forEach(el => {
                const key = el.getAttribute('data-translate');
                if (!el.dataset.originalText) {
                    el.dataset.originalText = el.textContent.trim();
                }
                if (!elementMap[key]) {
                    elementMap[key] = [];
                    keys.push(key);
                }
                elementMap[key].push(el);
                log(`   - [${key}] = "${el.textContent}"`);
            });

            if (targetLang === 'en') {
                log(`🔙 Restoring to English...`);
                elements.forEach(el => {
                    if (el.dataset.originalText) {
                        el.textContent = el.dataset.originalText;
                    }
                });
                log(`✅ Restored to English`, 'success');
                return;
            }

            // Test translation API
            try {
                log(`📤 Sending request to /accessibility/translate-batch`);
                log(`   Keys: ${JSON.stringify(keys)}`);
                log(`   Target Language: ${targetLang}`);

                const response = await fetch('/accessibility/translate-batch', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        texts: keys,
                        target_lang: targetLang
                    })
                });

                log(`📥 Response: ${response.status} ${response.statusText}`);

                if (!response.ok) {
                    const errorText = await response.text();
                    log(`❌ Error Response:`, 'error');
                    try {
                        const errorJson = JSON.parse(errorText);
                        log(JSON.stringify(errorJson, null, 2), 'error');
                    } catch {
                        log(errorText, 'error');
                    }
                    return;
                }

                const data = await response.json();
                log(`📦 Data received:`, 'success');
                log(JSON.stringify(data, null, 2));

                if (data.success && data.translations) {
                    log(`━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━`);
                    log(`📝 Applying translations...`);
                    
                    let successCount = 0;
                    let failCount = 0;
                    
                    Object.keys(elementMap).forEach(key => {
                        const translated = data.translations[key];
                        if (translated && translated !== key) {
                            log(`   ✅ ${key} → ${translated}`, 'success');
                            elementMap[key].forEach(el => {
                                el.textContent = translated;
                            });
                            successCount++;
                        } else {
                            log(`   ⚠️ No translation for: ${key}`, 'warning');
                            failCount++;
                        }
                    });
                    
                    log(`━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━`);
                    log(`✅✅✅ TRANSLATION COMPLETE!`, 'success');
                    log(`   Success: ${successCount} | Failed: ${failCount}`);
                } else {
                    log(`❌ Invalid response format`, 'error');
                    log(`Expected: {success: true, translations: {...}}`, 'error');
                    log(`Received: ${JSON.stringify(data)}`, 'error');
                }
            } catch (error) {
                log(`❌ ERROR: ${error.message}`, 'error');
                log(`Stack: ${error.stack}`, 'error');
            }
        }
    </script>
</body>
</html>
