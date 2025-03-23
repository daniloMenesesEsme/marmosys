<!DOCTYPE html>
<html>
<head>
    <title>Diagnóstico do Sistema</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .section { margin-bottom: 20px; padding: 10px; border: 1px solid #ddd; }
        h2 { color: #333; }
        pre { background: #f5f5f5; padding: 10px; overflow: auto; }
        .button { padding: 10px; background: #007bff; color: white; border: none; cursor: pointer; }
    </style>
</head>
<body>
    <h1>Página de Diagnóstico do Sistema</h1>
    
    <div class="section">
        <h2>Informações do Ambiente</h2>
        <ul>
            <li>Laravel: {{ app()->version() }}</li>
            <li>PHP: {{ phpversion() }}</li>
            <li>Sistema: {{ php_uname() }}</li>
        </ul>
    </div>
    
    <div class="section">
        <h2>Log dos últimos erros (últimas 10 linhas)</h2>
        <pre>
            @php
                $logPath = storage_path('logs/laravel.log');
                if(file_exists($logPath)) {
                    $logFile = file($logPath);
                    $lastLines = array_slice($logFile, -10);
                    echo implode('', $lastLines);
                } else {
                    echo "Arquivo de log não encontrado";
                }
            @endphp
        </pre>
    </div>
    
    <div class="section">
        <h2>Formulário de Teste</h2>
        <p>Use este formulário para testar o envio de dados sem a interface completa</p>
        
        <form action="{{ route('financial.budgets.store') }}" method="POST">
            @csrf
            <div>
                <label>Número:</label>
                <input type="text" name="numero" value="TEST-{{ date('YmdHis') }}">
            </div>
            <div>
                <label>Data:</label>
                <input type="date" name="data" value="{{ date('Y-m-d') }}">
            </div>
            <div>
                <label>Cliente:</label>
                <select name="client_id">
                    @php
                        $clients = \App\Models\Client::where('ativo', true)->limit(5)->get();
                    @endphp
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}">{{ $client->nome }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label>Previsão de Entrega:</label>
                <input type="date" name="previsao_entrega" value="{{ date('Y-m-d', strtotime('+7 days')) }}">
            </div>
            
            <!-- Array simples para rooms -->
            <input type="hidden" name="rooms[0][nome]" value="Ambiente de Teste">
            <input type="hidden" name="rooms[0][items][0][material_id]" value="1">
            <input type="hidden" name="rooms[0][items][0][quantidade]" value="1">
            <input type="hidden" name="rooms[0][items][0][unidade]" value="m²">
            <input type="hidden" name="rooms[0][items][0][largura]" value="1">
            <input type="hidden" name="rooms[0][items][0][altura]" value="1">
            
            <button type="submit" class="button">Enviar Formulário de Teste</button>
        </form>
    </div>
</body>
</html> 