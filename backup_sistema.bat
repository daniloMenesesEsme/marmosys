@echo off
:: backup_sistema.bat

:: Define a data atual no formato YYYYMMDD
for /f "tokens=2 delims==" %%I in ('wmic os get localdatetime /value') do set datetime=%%I
set data=%datetime:~0,8%

:: Define os caminhos
set BACKUP_PATH=C:\laragon\backup\backup_sistema
set PROJECT_PATH=C:\laragon\www\marmosys
set TEMP_PATH=%BACKUP_PATH%\temp_%data%

:: Cria o diretório de backup se não existir
if not exist "%BACKUP_PATH%" mkdir "%BACKUP_PATH%"
if not exist "%TEMP_PATH%" mkdir "%TEMP_PATH%"

:: Mensagem inicial
echo Iniciando backup do sistema Marmosys...
echo Data: %data%

:: Copiar todos os diretórios do sistema
echo Copiando pastas do sistema...
xcopy "%PROJECT_PATH%\app" "%TEMP_PATH%\app\" /E /I /Y /Q
xcopy "%PROJECT_PATH%\bootstrap" "%TEMP_PATH%\bootstrap\" /E /I /Y /Q
xcopy "%PROJECT_PATH%\config" "%TEMP_PATH%\config\" /E /I /Y /Q
xcopy "%PROJECT_PATH%\database" "%TEMP_PATH%\database\" /E /I /Y /Q
xcopy "%PROJECT_PATH%\docs" "%TEMP_PATH%\docs\" /E /I /Y /Q
xcopy "%PROJECT_PATH%\git-hooks" "%TEMP_PATH%\git-hooks\" /E /I /Y /Q
xcopy "%PROJECT_PATH%\lang" "%TEMP_PATH%\lang\" /E /I /Y /Q
xcopy "%PROJECT_PATH%\public" "%TEMP_PATH%\public\" /E /I /Y /Q
xcopy "%PROJECT_PATH%\resources" "%TEMP_PATH%\resources\" /E /I /Y /Q
xcopy "%PROJECT_PATH%\routes" "%TEMP_PATH%\routes\" /E /I /Y /Q
xcopy "%PROJECT_PATH%\storage" "%TEMP_PATH%\storage\" /E /I /Y /Q
xcopy "%PROJECT_PATH%\tests" "%TEMP_PATH%\tests\" /E /I /Y /Q
xcopy "%PROJECT_PATH%\vendor" "%TEMP_PATH%\vendor\" /E /I /Y /Q

:: Adicionar diretórios ocultos (se existirem)
if exist "%PROJECT_PATH%\.git" xcopy "%PROJECT_PATH%\.git" "%TEMP_PATH%\.git\" /E /I /Y /Q /H

:: Copiar todos os arquivos da raiz do projeto
echo Copiando arquivos da raiz...
copy "%PROJECT_PATH%\*.php" "%TEMP_PATH%\" /Y
copy "%PROJECT_PATH%\*.json" "%TEMP_PATH%\" /Y
copy "%PROJECT_PATH%\*.js" "%TEMP_PATH%\" /Y
copy "%PROJECT_PATH%\*.xml" "%TEMP_PATH%\" /Y
copy "%PROJECT_PATH%\*.lock" "%TEMP_PATH%\" /Y
copy "%PROJECT_PATH%\*.example" "%TEMP_PATH%\" /Y
copy "%PROJECT_PATH%\.*" "%TEMP_PATH%\" /Y
copy "%PROJECT_PATH%\*.*" "%TEMP_PATH%\" /Y

:: Garantir que arquivos específicos importantes sejam copiados (explicitamente)
copy "%PROJECT_PATH%\.env" "%TEMP_PATH%\" /Y
copy "%PROJECT_PATH%\.env.example" "%TEMP_PATH%\" /Y
copy "%PROJECT_PATH%\.cursorrules" "%TEMP_PATH%\" /Y
copy "%PROJECT_PATH%\artisan" "%TEMP_PATH%\" /Y
copy "%PROJECT_PATH%\composer.json" "%TEMP_PATH%\" /Y
copy "%PROJECT_PATH%\composer.lock" "%TEMP_PATH%\" /Y
copy "%PROJECT_PATH%\package.json" "%TEMP_PATH%\" /Y
copy "%PROJECT_PATH%\package-lock.json" "%TEMP_PATH%\" /Y
copy "%PROJECT_PATH%\phpunit.xml" "%TEMP_PATH%\" /Y

:: Compactar arquivos
echo Compactando arquivos...
powershell Compress-Archive -Path "%TEMP_PATH%\*" -DestinationPath "%BACKUP_PATH%\marmosys_%data%.zip" -Force

:: Remover pasta temporária
echo Limpando arquivos temporários...
rmdir /S /Q "%TEMP_PATH%"

:: Verifica se o arquivo ZIP foi criado
if exist "%BACKUP_PATH%\marmosys_%data%.zip" (
    echo.
    echo Backup realizado com sucesso!
    echo Arquivo salvo em: %BACKUP_PATH%\marmosys_%data%.zip
) else (
    echo.
    echo Erro ao realizar o backup!
)

:: Pausa para ver o resultado
pause 