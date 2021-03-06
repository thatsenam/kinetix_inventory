<div>
    @if($updateMode)
        @include('livewire.warehouse.update')
    @else
        @include('livewire.warehouse.create')
    @endif
    <table class="table table-sm table-bordered mt-5">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Address</th>
                <th>City</th>
                <th>Option</th>
            </tr>
        </thead>
        <tbody>
            @php $i = 1; @endphp
            @foreach($warehouses as $value)
            <tr>
                <td>{{ $i++ }}</td>
                <td>{{ $value->name }}</td>
                <td>{{ $value->phone }}</td>
                <td>{{ $value->address }}</td>
                <td>{{ $value->city }}</td>
                <td>
                <button wire:click="edit({{ $value->id }})" class="btn btn-primary btn-sm">Edit</button>
                <button wire:click="delete({{ $value->id }})" class="btn btn-danger btn-sm" @if ($loop->first)
                disabled @endif>Delete</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
