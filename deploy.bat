@echo off
setlocal enabledelayedexpansion

REM Configuration
set "SOURCE_DIR=%~dp0"
set "TARGET_DIR=F:\LOGICIEL\XAMP\htdocs\Takalo"
set "BACKUP_DIR=%TARGET_DIR%_backup_%date:~-4,4%%date:~-10,2%%date:~-7,2%_%time:~0,2%%time:~3,2%"
set "BACKUP_DIR=!BACKUP_DIR: =0!"

echo Début du déploiement...
echo Source: %SOURCE_DIR%
echo Cible: %TARGET_DIR%
echo.

REM Vérifier si le répertoire cible existe et faire une sauvegarde
if exist "%TARGET_DIR%" (
    echo Sauvegarde de l'ancienne version...
    xcopy "%TARGET_DIR%" "%BACKUP_DIR%" /E /I /H /Y
    if !errorlevel! neq 0 (
        echo Erreur lors de la sauvegarde. Annulation du déploiement.
        exit /b 1
    )
    echo Sauvegarde effectuée dans: %BACKUP_DIR%
    echo.
    
    echo Suppression de l'ancienne version...
    rmdir /s /q "%TARGET_DIR%"
)

echo Copie des fichiers...
xcopy "%SOURCE_DIR%*" "%TARGET_DIR%\" /E /I /H /Y
if !errorlevel! neq 0 (
    echo Erreur lors de la copie des fichiers.
    exit /b 1
)

echo.
echo Déploiement réussi!
echo.
echo Accès à l'application: http://localhost/Takalo/public/
echo.
pause