<#
.SYNOPSIS
    Builds dist/pkg_pankylottie-<version>.zip and refreshes update.xml.

.DESCRIPTION
    The version is read from pkg_pankylottie.xml. Zip entries use forward
    slashes (Compress-Archive in Windows PowerShell writes backslashes, which
    break installation on Linux servers).
#>

$ErrorActionPreference = 'Stop'
Add-Type -AssemblyName System.IO.Compression, System.IO.Compression.FileSystem

$root     = $PSScriptRoot
$manifest = [xml](Get-Content -Raw -Encoding UTF8 (Join-Path $root 'pkg_pankylottie.xml'))
$version  = $manifest.extension.version
$repo     = 'https://github.com/pnkr/mod_pankylottiefiles'
$include  = @('pkg_pankylottie.xml', 'script.php', 'language', 'mod_pankylottiefiles', 'plg_content_pankylottie', 'plg_editors-xtd_pankylottie')

foreach ($xml in Get-ChildItem $root -Recurse -Filter *.xml | Where-Object { $_.Name -in 'pkg_pankylottie.xml', 'mod_pankylottiefiles.xml', 'pankylottie.xml' }) {
    $extensionVersion = ([xml](Get-Content -Raw -Encoding UTF8 $xml.FullName)).extension.version

    if ($extensionVersion -ne $version) {
        throw "$($xml.FullName) has version $extensionVersion, expected $version"
    }
}

$dist = Join-Path $root 'dist'
New-Item -ItemType Directory -Force $dist | Out-Null
$zipPath = Join-Path $dist "pkg_pankylottie-$version.zip"

if (Test-Path $zipPath) {
    Remove-Item $zipPath
}

$zip = [System.IO.Compression.ZipFile]::Open($zipPath, 'Create')

try {
    foreach ($item in $include) {
        $path  = Join-Path $root $item
        $files = if (Test-Path $path -PathType Container) { Get-ChildItem $path -Recurse -File } else { Get-Item $path }

        foreach ($file in $files) {
            $entry = $file.FullName.Substring($root.Length + 1).Replace('\', '/')
            [System.IO.Compression.ZipFileExtensions]::CreateEntryFromFile($zip, $file.FullName, $entry, 'Optimal') | Out-Null
        }
    }
} finally {
    $zip.Dispose()
}

$sha512 = (Get-FileHash $zipPath -Algorithm SHA512).Hash.ToLower()

$update = @"
<?xml version="1.0" encoding="utf-8"?>
<updates>
	<update>
		<name>Panky Lottie Files</name>
		<description>Panky Lottie Files $version</description>
		<element>pkg_pankylottie</element>
		<type>package</type>
		<version>$version</version>
		<infourl title="Panky Lottie Files">$repo/releases/tag/v$version</infourl>
		<downloads>
			<downloadurl type="full" format="zip">$repo/releases/download/v$version/pkg_pankylottie-$version.zip</downloadurl>
		</downloads>
		<sha512>$sha512</sha512>
		<tags>
			<tag>stable</tag>
		</tags>
		<maintainer>Panayiotis Kiriakopoulos</maintainer>
		<maintainerurl>https://github.com/pnkr</maintainerurl>
		<targetplatform name="joomla" version="6\.[0-9]+" />
		<php_minimum>8.3</php_minimum>
	</update>
</updates>
"@

[IO.File]::WriteAllText((Join-Path $root 'update.xml'), $update.Replace("`r`n", "`n"), (New-Object Text.UTF8Encoding $false))

Write-Output "Built $zipPath"
Write-Output "SHA-512 $sha512"
Write-Output "Upload the zip to the v$version release on GitHub, then commit update.xml."
