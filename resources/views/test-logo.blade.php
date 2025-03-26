<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Teste de Logo</title>
</head>
<body>
    <h1>Teste de carregamento do logo</h1>
    
    <h2>1. Imagem direta:</h2>
    <img src="{{ asset('images/logo.png') }}" alt="Logo">
    
    <h2>2. Imagem via Base64:</h2>
    @php
        $logoPath = public_path('images/logo.png');
        $logoData = '';
        if (file_exists($logoPath)) {
            $logoData = base64_encode(file_get_contents($logoPath));
            echo "<p>O arquivo existe e tem " . filesize($logoPath) . " bytes.</p>";
        } else {
            echo "<p>O arquivo não existe em: " . $logoPath . "</p>";
        }
    @endphp
    
    @if($logoData)
        <img src="data:image/png;base64,{{ $logoData }}" alt="Logo via Base64">
    @else
        <p>Não foi possível carregar a imagem via Base64</p>
    @endif
    
    <h2>3. Detalhes do arquivo:</h2>
    <pre>
    @php
        if (file_exists($logoPath)) {
            $info = getimagesize($logoPath);
            echo "Dimensões: " . $info[0] . "x" . $info[1] . "\n";
            echo "Tipo MIME: " . $info['mime'] . "\n";
            echo "Permissões: " . substr(sprintf('%o', fileperms($logoPath)), -4) . "\n";
        }
    @endphp
    </pre>
</body>
</html> 