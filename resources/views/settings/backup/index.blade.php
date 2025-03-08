@extends('layouts.app')

@section('title', 'Backup do Sistema')

@section('content_header')
    <h1>Backup do Sistema</h1>
@stop

@section('content')
<div class="container-fluid">
    @if(session('success'))
        <div class="card-panel green white-text">
            <i class="material-icons left">check_circle</i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="card-panel red white-text">
            <i class="material-icons left">error</i>
            {{ session('error') }}
        </div>
    @endif

    <div class="row">
        <!-- Backup Manual -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-download mr-2"></i>
                        Backup Manual
                    </h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('settings.backup.create') }}" method="POST">
                        @csrf
                        <p class="text-muted">
                            Clique no botão abaixo para realizar um backup completo do sistema agora.
                        </p>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-download mr-2"></i>
                            Realizar Backup Agora
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Agendamento -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-clock mr-2"></i>
                        Agendar Backup
                    </h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('settings.backup.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Frequência</label>
                            <select name="frequency" class="form-control @error('frequency') is-invalid @enderror">
                                <option value="daily">Diário</option>
                                <option value="weekly">Semanal</option>
                                <option value="monthly">Mensal</option>
                            </select>
                            @error('frequency')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Horário</label>
                            <input type="time" name="time" class="form-control @error('time') is-invalid @enderror">
                            @error('time')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group" id="dayField" style="display: none;">
                            <label>Dia</label>
                            <input type="number" name="day" min="1" max="31" class="form-control @error('day') is-invalid @enderror">
                            @error('day')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save mr-2"></i>
                            Salvar Agendamento
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Histórico de Backups -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-history mr-2"></i>
                        Histórico de Backups
                    </h3>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="striped">
                        <thead>
                            <tr>
                                <th>Arquivo</th>
                                <th>Data</th>
                                <th>Tamanho</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($backupFiles as $backup)
                                <tr>
                                    <td>{{ $backup['name'] }}</td>
                                    <td>{{ $backup['date'] }}</td>
                                    <td>{{ $backup['size'] }}</td>
                                    <td>
                                        <a href="{{ route('settings.backup.download', ['filename' => $backup['name']]) }}" 
                                           class="btn-floating btn-small waves-effect waves-light blue" 
                                           title="Download">
                                            <i class="material-icons">download</i>
                                        </a>
                                        
                                        <button type="button" 
                                                onclick="confirmDelete('{{ $backup['name'] }}')"
                                                class="btn-floating btn-small waves-effect waves-light red" 
                                                title="Excluir">
                                            <i class="material-icons">delete</i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="center-align">Nenhum backup encontrado</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script>
document.querySelector('select[name="frequency"]').addEventListener('change', function() {
    const dayField = document.getElementById('dayField');
    if (this.value === 'weekly' || this.value === 'monthly') {
        dayField.style.display = 'block';
    } else {
        dayField.style.display = 'none';
    }
});

function confirmDelete(filename) {
    if (confirm('Tem certeza que deseja excluir este backup?')) {
        window.location.href = "{{ route('settings.backup.delete', '') }}/" + filename;
    }
}
</script>
@stop 