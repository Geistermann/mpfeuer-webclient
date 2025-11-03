<table border=1>
    <thead>
        <tr>
            <th>ID</th>
            <th>Eigentuümer</th>
            <th>Standort</th>
            <th>Gruppe</th>
            <th>Bezeichnung</th>
            <th>Barcode</th>
            <!-- weitere Spalten -->
        </tr>
    </thead>
    <tbody>
        @foreach($daten as $datensatz)
            <tr>
                <td>{{ $datensatz->GHR_ID ?? '' }}</td>
                <td>{{ $datensatz->GHR_EIGENTUM ?? '' }}</td>
                <td>{{ $datensatz->GHR_STANDORT ?? '' }}</td>
                <td>{{ $datensatz->GHR_GRUPPE ?? '' }}</td>
                <td>{{ $datensatz->GHR_BEZEICHNUNG ?? '' }}</td>
                <td>{{ $datensatz->GHR_BARCODE_NR ?? '' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
