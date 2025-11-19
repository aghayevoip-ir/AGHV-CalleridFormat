<?php
/**
 * AghayeVOIP CallerID Formatter - Debug Tool
 * https://aghayevoip.ir
 */

// Check if running on Asterisk server
if (!file_exists('/etc/asterisk')) {
    die('❌ این ابزار فقط روی سرور استریسک کار می‌کنه!');
}

// Function to execute command and return output
function runCommand($cmd) {
    $output = shell_exec($cmd . ' 2>&1');
    return $output ?: 'No output';
}

// Function to format output
function formatOutput($title, $output, $success = null) {
    $color = $success === true ? 'green' : ($success === false ? 'red' : 'blue');
    echo "<div class='section'>";
    echo "<h3 style='color: $color;'>$title</h3>";
    echo "<pre class='output'>" . htmlspecialchars($output) . "</pre>";
    echo "</div>";
}

?>
<!DOCTYPE html>
<html dir="rtl" lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AghayeVOIP CallerID Formatter - Debug Tool</title>
    <style>
        body {
            font-family: 'Tahoma', Arial, sans-serif;
            background: #f5f5f5;
            margin: 0;
            padding: 20px;
            direction: rtl;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
        }
        .controls {
            padding: 30px;
            text-align: center;
            background: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
        }
        .btn {
            background: #28a745;
            color: white;
            border: none;
            padding: 15px 30px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            margin: 0 10px;
            transition: all 0.3s;
        }
        .btn:hover {
            background: #218838;
            transform: translateY(-2px);
        }
        .btn-danger {
            background: #dc3545;
        }
        .btn-danger:hover {
            background: #c82333;
        }
        .results {
            padding: 30px;
            display: none;
        }
        .section {
            margin-bottom: 30px;
            border: 1px solid #e9ecef;
            border-radius: 5px;
            padding: 20px;
            background: #fafafa;
        }
        .section h3 {
            margin-top: 0;
            padding-bottom: 10px;
            border-bottom: 2px solid #007bff;
        }
        .output {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 15px;
            font-family: 'Courier New', monospace;
            font-size: 12px;
            white-space: pre-wrap;
            max-height: 300px;
            overflow-y: auto;
            margin: 10px 0;
        }
        .status-ok {
            color: #28a745;
            font-weight: bold;
        }
        .status-error {
            color: #dc3545;
            font-weight: bold;
        }
        .copy-btn {
            background: #17a2b8;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 3px;
            cursor: pointer;
            font-size: 12px;
            float: left;
        }
        .loading {
            text-align: center;
            padding: 50px;
            font-size: 18px;
            color: #007bff;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🛠️ AghayeVOIP CallerID Formatter - Debug Tool</h1>
            <p>ابزار عیب‌یابی فرمتر کالرآیدی - aghayevoip.ir</p>
        </div>
        
        <div class="controls">
            <button class="btn" onclick="runDebug()">🚀 اجرای تست کامل</button>
            <button class="btn btn-danger" onclick="clearResults()">🗑️ پاک کردن نتایج</button>
            <button class="btn" onclick="copyAllResults()">📋 کپی همه نتایج</button>
        </div>
        
        <div id="results" class="results">
            <div id="loading" class="loading" style="display:none;">
                ⏳ در حال اجرای تست‌ها... لطفاً صبر کنید
            </div>
            <div id="output"></div>
        </div>
    </div>

    <script>
        function runDebug() {
            document.getElementById('results').style.display = 'block';
            document.getElementById('loading').style.display = 'block';
            document.getElementById('output').innerHTML = '';
            
            // Run debug via AJAX
            fetch('debug.php?action=run')
                .then(response => response.text())
                .then(data => {
                    document.getElementById('loading').style.display = 'none';
                    document.getElementById('output').innerHTML = data;
                })
                .catch(error => {
                    document.getElementById('loading').style.display = 'none';
                    document.getElementById('output').innerHTML = '<div class="status-error">❌ خطا: ' + error + '</div>';
                });
        }
        
        function clearResults() {
            document.getElementById('results').style.display = 'none';
            document.getElementById('output').innerHTML = '';
        }
        
        function copyAllResults() {
            const output = document.querySelectorAll('.output');
            let allText = '';
            output.forEach(section => {
                allText += section.textContent + '\n\n';
            });
            
            navigator.clipboard.writeText(allText).then(() => {
                alert('✅ همه نتایج کپی شد!');
            }).catch(() => {
                alert('❌ خطا در کپی کردن');
            });
        }
        
        function copySection(element) {
            const text = element.parentElement.querySelector('.output').textContent;
            navigator.clipboard.writeText(text).then(() => {
                alert('✅ کپی شد!');
            });
        }
    </script>

    <?php
    if (isset($_GET['action']) && $_GET['action'] === 'run') {
        ob_start();
        
        echo "<div class='section'>";
        echo "<h3>🔍 وضعیت کلی سیستم</h3>";
        
        // Check if Asterisk is running
        $asterisk_status = runCommand("systemctl is-active asterisk");
        $asterisk_ok = strpos($asterisk_status, 'active') !== false;
        echo "<p>وضعیت استریسک: " . ($asterisk_ok ? "<span class='status-ok'>✅ فعال</span>" : "<span class='status-error'>❌ غیرفعال</span>") . "</p>";
        
        // Check file permissions
        $file_exists = file_exists('/etc/asterisk/extensions_aghayevoip_numberformatter.conf');
        echo "<p>فایل فرمتر: " . ($file_exists ? "<span class='status-ok'>✅ موجود</span>" : "<span class='status-error'>❌ موجود نیست</span>") . "</p>";
        echo "</div>";
        
        // 1. Check installed files
        formatOutput("📁 بررسی فایل‌های نصب شده", 
            runCommand("ls -la /etc/asterisk/extensions_aghayevoip_numberformatter.conf"), 
            file_exists('/etc/asterisk/extensions_aghayevoip_numberformatter.conf'));
        
        // 2. Check includes
        formatOutput("🔗 بررسی include در extensions_custom.conf", 
            runCommand("grep -A2 -B2 'AghayeVOIP' /etc/asterisk/extensions_custom.conf"));
        
        // 3. Check from-pstn-custom context
        formatOutput("🎯 بررسی from-pstn-custom کانتکس", 
            runCommand("grep -A5 'from-pstn-custom' /etc/asterisk/extensions_custom.conf"));
        
        // 4. Check dialplan
        formatOutput("📋 بررسی دیال‌پلن فرمت‌کننده", 
            runCommand("asterisk -rx 'dialplan show AGHV_numberformatter'"));
        
        // 5. Check from-pstn-custom dialplan
        formatOutput("🔍 بررسی from-pstn-custom دیال‌پلن", 
            runCommand("asterisk -rx 'dialplan show from-pstn-custom'"));
        
        // 6. Check recent logs
        formatOutput("📜 بررسی لاگ‌های اخیر", 
            runCommand("tail -50 /var/log/asterisk/full | grep -E '(from-pstn|AGHV|CALLERID)'"));
        
        // 7. Check CDR
        formatOutput("📊 بررسی CDR", 
            runCommand("tail -20 /var/log/asterisk/cdr-csv/Master.csv"));
        
        // 8. Test formatter
        echo "<div class='section'>";
        echo "<h3>🧪 تست فرمت‌کننده</h3>";
        echo "<p>در حال تست با شماره نمونه 09123456789...</p>";
        
        $test_result = runCommand("asterisk -rx 'channel originate Local/09123456789@from-pstn extension s@AGHV_numberformatter'");
        echo "<pre class='output'>" . htmlspecialchars($test_result) . "</pre>";
        
        if (strpos($test_result, 'Success') !== false || strpos($test_result, 'Originate') !== false) {
            echo "<p class='status-ok'>✅ تست با موفقیت انجام شد</p>";
        } else {
            echo "<p class='status-error'>❌ تست با مشکل مواجه شد</p>";
        }
        echo "</div>";
        
        // 9. System info
        formatOutput("ℹ️ اطلاعات سیستم", 
            "سرور: " . php_uname() . "\n" .
            "زمان: " . date('Y-m-d H:i:s') . "\n" .
            "نسخه PHP: " . phpversion());
        
        $output = ob_get_clean();
        echo $output;
        exit;
    }
    ?>
</body>
</html>