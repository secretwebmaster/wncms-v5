<?php

// Define translations
$translations = [
    'en' => [
        'install_instructions' => 'Before Installation',
        'description' => 'Please follow the instructions below to complete the installation. After completing the setup, refresh the page to proceed.',
        'enable_exec_function' => 'Method 1: Enable exec function',
        'enable_exec_desc' => 'The <code>exec</code> function is disabled on this server. Please enable it in your php.ini configuration.',
        'command_line_installation' => 'Method 2: Command Line Installation',
        'command_line_desc' => 'Run the following commands to install:',
        'copy_code' => 'Copy Code',
        'copied' => 'Copied',
    ],
    'zh_TW' => [
        'install_instructions' => '安裝前準備',
        'description' => '請按照以下說明完成安裝。完成設置後，刷新頁面以開始安裝。',
        'enable_exec_function' => '方法 1: 啟用 exec 函數',
        'enable_exec_desc' => '該伺服器上禁用了 <code>exec</code> 函數。請在你的 php.ini 配置中啟用它。',
        'command_line_installation' => '方法 2: 命令行安裝',
        'command_line_desc' => '運行以下命令來安裝：',
        'copy_code' => '複製代碼',
        'copied' => '已複製',
    ],
    'zh_CN' => [
        'install_instructions' => '安装前准备',
        'description' => '请按照以下说明完成安装。完成设置后，刷新页面以开始安装。',
        'enable_exec_function' => '方法 1: 启用 exec 函数',
        'enable_exec_desc' => '该服务器上禁用了 <code>exec</code> 函数。请在你的 php.ini 配置中启用它。',
        'command_line_installation' => '方法 2: 命令行安装',
        'command_line_desc' => '运行以下命令来安装：',
        'copy_code' => '复制代码',
        'copied' => '已复制',
    ],
];

require('language.php');

?>

<!DOCTYPE html>
<html lang="<?php echo $currentLang; ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo getTranslation('install_instructions'); ?></title>
    
</head>

<body>

    <div class="header">
        <div class="language-switcher-wrapper">
            <ul class="language-switcher-item-list">
                <?php foreach (getLocaleList() as $key => $locale): ?>
                    <li>
                        <a class="language-switcher-item" href="<?php echo getLocalizedURL($key); ?>"><?php echo $locale['native']; ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <div class="container">
        <h1><?php echo getTranslation('install_instructions'); ?></h1>
        <div class="card card-1">
            <h2><?php echo getTranslation('enable_exec_function'); ?></h2>
            <div>
                <?php echo getTranslation('enable_exec_desc'); ?>
            </div>
        </div>
        <div class="card card-2">
            <h2><?php echo getTranslation('command_line_installation'); ?></h2>
            <div class="code-block" id="code-block"><code>composer install<br>cp .env.example .env</code></div>
            <button class="copy-button" onclick="copyCode()"><?php echo getTranslation('copy_code'); ?></button>
        </div>
        <p class="description"><?php echo getTranslation('description'); ?></p>
    </div>

    <script>
        function copyCode() {
            const code = document.getElementById('code-block').innerText;
            navigator.clipboard.writeText(code).then(() => {
                const button = document.querySelector('.copy-button');
                button.textContent = '<?php echo getTranslation('copied'); ?>';
                button.classList.add('copied');
                setTimeout(() => {
                    button.textContent = '<?php echo getTranslation('copy_code'); ?>';
                    button.classList.remove('copied');
                }, 2000);
            });
        }
    </script>
</body>

</html>