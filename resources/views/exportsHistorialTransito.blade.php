<table>
    <thead>
        <tr>
            <th>Placa</th>
            <th>Recibe</th>
            <th>Fecha de entrega</th>
            <th>Observaciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $item)
            <tr>
                <td>{{ $item->placas }}</td>
                <td>{{ $item->recibe }}</td>
                <td>{{ $item->fecha_de_entrega }}</td>
                <td>{{ $item->observaciones }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
