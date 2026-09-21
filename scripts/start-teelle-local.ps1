param(
    [switch] $OpenBrowser
)

$ErrorActionPreference = 'Stop'

$projectRoot = Split-Path -Parent $PSScriptRoot
$webRoot = Join-Path $projectRoot 'apps\web'
$runtimeRoot = Join-Path $env:LOCALAPPDATA 'Teelle\local-preview'
$mysqlHome = Join-Path $runtimeRoot 'mysql-8.4.11-winx64'
$mysqlData = Join-Path $runtimeRoot 'mysql-data'
$logRoot = Join-Path $runtimeRoot 'logs'
$mysqlPort = 14018
$webPort = 8136
$siteUrl = "http://127.0.0.1:$webPort"

function Test-LocalPort {
    param([int] $Port)

    return [bool](Get-NetTCPConnection -State Listen -LocalPort $Port -ErrorAction SilentlyContinue)
}

function Wait-LocalPort {
    param(
        [int] $Port,
        [int] $TimeoutSeconds = 30
    )

    $deadline = (Get-Date).AddSeconds($TimeoutSeconds)
    while ((Get-Date) -lt $deadline) {
        if (Test-LocalPort -Port $Port) {
            return
        }

        Start-Sleep -Milliseconds 500
    }

    throw "Local port $Port did not become ready within $TimeoutSeconds seconds."
}

if (-not (Test-Path -LiteralPath $webRoot)) {
    throw "Teelle web application was not found at $webRoot"
}

$mysqlExecutable = Join-Path $mysqlHome 'bin\mysqld.exe'
if (-not (Test-Path -LiteralPath $mysqlExecutable)) {
    throw "Teelle local MySQL runtime was not found at $mysqlExecutable"
}

if (-not (Test-Path -LiteralPath $mysqlData)) {
    throw "Teelle local MySQL data was not found at $mysqlData"
}

New-Item -ItemType Directory -Path $logRoot -Force | Out-Null

if (-not (Test-LocalPort -Port $mysqlPort)) {
    Start-Process -FilePath $mysqlExecutable `
        -ArgumentList @(
            "--datadir=$mysqlData",
            "--port=$mysqlPort",
            '--bind-address=127.0.0.1',
            '--mysqlx-port=14019',
            '--console'
        ) `
        -RedirectStandardOutput (Join-Path $logRoot 'mysql.out.log') `
        -RedirectStandardError (Join-Path $logRoot 'mysql.err.log') `
        -WindowStyle Hidden

    Wait-LocalPort -Port $mysqlPort
}

if (-not (Test-LocalPort -Port $webPort)) {
    $phpExecutable = (Get-Command php.exe -ErrorAction Stop).Source

    $env:APP_ENV = 'local'
    $env:APP_DEBUG = 'true'
    $env:APP_URL = $siteUrl
    $env:DB_CONNECTION = 'mysql'
    $env:DB_HOST = '127.0.0.1'
    $env:DB_PORT = [string] $mysqlPort
    $env:DB_DATABASE = 'teelle_taxonomy_test'
    $env:DB_USERNAME = 'root'
    $env:DB_PASSWORD = ''

    # هر بار که وب بالا می‌آید، مهاجرت‌های فقط-افزایشی و سیدر تاکسونومی ایدمپوتنت اجرا می‌شوند
    # تا فرم‌ها هیچ‌وقت برچسب/ایمنی/بازه سنی خالی نبینند؛ داده‌های کاربر دست‌نخورده می‌ماند.
    & $phpExecutable artisan migrate --force | Out-Null
    & $phpExecutable artisan db:seed --class=SystemTaxonomySeeder --force | Out-Null

    Start-Process -FilePath $phpExecutable `
        -ArgumentList @('artisan', 'serve', '--host=127.0.0.1', "--port=$webPort") `
        -WorkingDirectory $webRoot `
        -RedirectStandardOutput (Join-Path $logRoot 'laravel.out.log') `
        -RedirectStandardError (Join-Path $logRoot 'laravel.err.log') `
        -WindowStyle Hidden

    Wait-LocalPort -Port $webPort
}

if ($OpenBrowser) {
    Start-Process $siteUrl
}

Write-Output "Teelle local preview is ready at $siteUrl"
