@extends('layouts.app')

@section('title', 'Mapa de Áreas de Atendimento')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col s12">
            <h4>Mapa de Áreas de Atendimento</h4>
        </div>
    </div>

    <div class="row">
        <div class="col s12 m3">
            <div class="card">
                <div class="card-content">
                    <span class="card-title">Filtros</span>
                    <div class="input-field">
                        <select id="seller_filter">
                            <option value="">Todos os Vendedores</option>
                            @foreach($sellerAreas as $sellerId => $data)
                                @if($sellerId > 0)
                                    <option value="{{ $sellerId }}">{{ $data['seller']->nome }}</option>
                                @endif
                            @endforeach
                            <option value="0">(Sem vendedor)</option>
                        </select>
                        <label>Filtrar por Vendedor</label>
                    </div>
                    
                    <div class="input-field">
                        <select id="type_filter">
                            <option value="">Todos os Tipos</option>
                            @foreach($establishmentTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                            <option value="null">(Sem tipo específico)</option>
                        </select>
                        <label>Filtrar por Tipo</label>
                    </div>
                    
                    <a class="btn waves-effect waves-light" id="reset_filters">
                        <i class="material-icons left">clear</i>Limpar Filtros
                    </a>
                </div>
            </div>
            
            <div class="card">
                <div class="card-content">
                    <span class="card-title">Legenda</span>
                    <ul class="collection" id="map_legend">
                        @foreach($sellerAreas as $sellerId => $data)
                            <li class="collection-item avatar seller-legend" data-seller="{{ $sellerId }}">
                                <i class="material-icons circle" style="background-color: {{ $sellerId == 0 ? '#9e9e9e' : 'hsl(' . (($sellerId * 50) % 360) . ', 70%, 50%)' }}">place</i>
                                <span class="title">
                                    @if($sellerId > 0)
                                        {{ $data['seller']->nome }}
                                    @else
                                        Sem vendedor
                                    @endif
                                </span>
                                <p>{{ count($data['areas']) }} área(s) de atendimento</p>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="col s12 m9">
            <div class="card">
                <div class="card-content" style="padding: 0;">
                    <div id="map" style="height: 600px; width: 100%;"></div>
                </div>
                <div class="card-action">
                    <a href="{{ route('service-areas.index') }}" class="btn-flat waves-effect">
                        <i class="material-icons left">arrow_back</i>Voltar para Lista
                    </a>
                    <a href="{{ route('service-areas.create') }}" class="btn waves-effect waves-light">
                        <i class="material-icons left">add</i>Nova Área
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&callback=initMap" async defer></script>
<script>
    let map;
    let markers = [];
    let infoWindows = [];
    let serviceAreas = @json($sellerAreas);
    
    function initMap() {
        // Centraliza o mapa no Brasil
        map = new google.maps.Map(document.getElementById("map"), {
            zoom: 5,
            center: { lat: -15.7801, lng: -47.9292 }, // Brasília
        });
        
        // Adiciona os marcadores
        let bounds = new google.maps.LatLngBounds();
        let hasMarkers = false;
        
        Object.keys(serviceAreas).forEach(sellerId => {
            let color = sellerId == 0 ? '#9e9e9e' : `hsl(${(sellerId * 50) % 360}, 70%, 50%)`;
            
            serviceAreas[sellerId].areas.forEach(area => {
                if (area.location && area.location.latitude && area.location.longitude) {
                    let location = { 
                        lat: parseFloat(area.location.latitude), 
                        lng: parseFloat(area.location.longitude) 
                    };
                    
                    let marker = new google.maps.Marker({
                        position: location,
                        map: map,
                        title: area.location.name,
                        icon: {
                            path: google.maps.SymbolPath.CIRCLE,
                            fillColor: color,
                            fillOpacity: 0.9,
                            strokeWeight: 1,
                            strokeColor: '#ffffff',
                            scale: 10
                        },
                        sellerId: parseInt(sellerId),
                        typeId: area.establishment_type_id ? area.establishment_type_id : 'null'
                    });
                    
                    // Conteúdo da janela de informações
                    let infoContent = `
                        <div style="width: 250px;">
                            <h5>${area.location.name}</h5>
                            <p><strong>Tipo:</strong> ${area.establishment_type ? area.establishment_type.name : 'Todos'}</p>
                            <p><strong>Vendedor:</strong> ${serviceAreas[sellerId].seller ? serviceAreas[sellerId].seller.nome : 'Não atribuído'}</p>
                            ${area.goal_amount ? `<p><strong>Meta:</strong> R$ ${parseFloat(area.goal_amount).toLocaleString('pt-BR', {minimumFractionDigits: 2})}</p>` : ''}
                            <p><a href="/service-areas/${area.id}" class="waves-effect waves-light btn-small">Ver Detalhes</a></p>
                        </div>
                    `;
                    
                    let infoWindow = new google.maps.InfoWindow({
                        content: infoContent
                    });
                    
                    marker.addListener('click', () => {
                        // Fecha todas as janelas abertas
                        infoWindows.forEach(iw => iw.close());
                        // Abre a janela deste marcador
                        infoWindow.open(map, marker);
                    });
                    
                    markers.push(marker);
                    infoWindows.push(infoWindow);
                    bounds.extend(location);
                    hasMarkers = true;
                }
            });
        });
        
        // Ajusta o zoom para mostrar todos os marcadores
        if (hasMarkers) {
            map.fitBounds(bounds);
        }
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        let selects = document.querySelectorAll('select');
        M.FormSelect.init(selects);
        
        // Filtro por vendedor
        document.getElementById('seller_filter').addEventListener('change', function() {
            let selectedSellerId = this.value ? parseInt(this.value) : null;
            filterMarkers();
        });
        
        // Filtro por tipo
        document.getElementById('type_filter').addEventListener('change', function() {
            filterMarkers();
        });
        
        // Botão para limpar filtros
        document.getElementById('reset_filters').addEventListener('click', function() {
            document.getElementById('seller_filter').selectedIndex = 0;
            document.getElementById('type_filter').selectedIndex = 0;
            M.FormSelect.init(selects);
            filterMarkers();
        });
        
        // Clique na legenda
        document.querySelectorAll('.seller-legend').forEach(item => {
            item.addEventListener('click', function() {
                let sellerId = this.getAttribute('data-seller');
                document.getElementById('seller_filter').value = sellerId;
                M.FormSelect.init(document.getElementById('seller_filter'));
                filterMarkers();
            });
        });
    });
    
    function filterMarkers() {
        let selectedSellerId = document.getElementById('seller_filter').value;
        let selectedTypeId = document.getElementById('type_filter').value;
        
        // Atualiza a legenda
        document.querySelectorAll('.seller-legend').forEach(item => {
            if (!selectedSellerId || item.getAttribute('data-seller') === selectedSellerId) {
                item.style.opacity = 1;
            } else {
                item.style.opacity = 0.5;
            }
        });
        
        // Filtra os marcadores
        let bounds = new google.maps.LatLngBounds();
        let hasVisibleMarkers = false;
        
        markers.forEach(marker => {
            let showMarker = true;
            
            if (selectedSellerId) {
                let sellerIdInt = parseInt(selectedSellerId);
                showMarker = marker.sellerId === sellerIdInt;
            }
            
            if (showMarker && selectedTypeId) {
                if (selectedTypeId === 'null' && marker.typeId !== 'null') {
                    showMarker = false;
                } else if (selectedTypeId !== 'null' && marker.typeId != selectedTypeId) {
                    showMarker = false;
                }
            }
            
            marker.setVisible(showMarker);
            
            // Se o marcador está visível, inclui na área de zoom
            if (showMarker) {
                bounds.extend(marker.getPosition());
                hasVisibleMarkers = true;
            }
        });
        
        // Ajusta o zoom para mostrar todos os marcadores visíveis
        if (hasVisibleMarkers) {
            map.fitBounds(bounds);
            
            // Limita o zoom máximo para não aproximar demais quando houver poucos marcadores
            let maxZoom = 15;
            if (map.getZoom() > maxZoom) map.setZoom(maxZoom);
        }
    }
</script>
@endsection 