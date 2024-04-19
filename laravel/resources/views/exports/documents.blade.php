<table>
    <thead>
    <tr>
        <th>Name</th>
        <th>Email</th>
    </tr>
    </thead>
    <tbody>
    @foreach($documents as $doc)
        <tr>
            <td>{{ $doc->key }}</td>
            <td>{{ $doc->consecutive }}</td>
        </tr>
    @endforeach
    </tbody>
</table>