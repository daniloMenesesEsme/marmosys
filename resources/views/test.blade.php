<!DOCTYPE html>
<html>
<head>
    <title>Teste de Diagnóstico</title>
</head>
<body>
    <h1>Página de Teste</h1>
    <p>Esta página está sendo usada para diagnosticar o erro.</p>
    
    <form action="{{ route('financial.budgets.store') }}" method="POST">
        @csrf
        <input type="text" name="numero" value="TEST-001">
        <input type="date" name="data" value="{{ date('Y-m-d') }}">
        <button type="submit">Enviar</button>
    </form>
</body>
</html> 