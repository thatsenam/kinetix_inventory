
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                            @if($showDialog)
                                <dialog style="background:#cad6e2; min-width: 390px; border: none;border-radius: 6px;" class="col-12">
                                    <p>Add Subhead</p>
                                    <input wire:model.lazy="sub_head" type="text" class="form-control" placeholder="Enter Subhead" class="">
                                    <br>
                                    <button wire:click="$set('showDialog',false)" class="btn btn-default">Cancel</button>
                                    <button class="btn btn-success" wire:click="createsub" class="">Add New</button>
                                </dialog>
                            @else
                            <div>
                                @if (session()->has('message'))
                                    <div class="alert alert-success">
                                        {{ session('message') }}
                                    </div>
                                @endif

                                @if($updateMode)
                                    @include('livewire.update-acc')
                                @else
                                    @include('livewire.create-acc')
                                @endif
                                <table id="heads" class="table table-bordered mt-3 table-sm">
                                    <thead>
                                        <tr>
                                            <th>Parent</th>
                                            <th>Subhead</th>
                                            <th>Head</th>
                                            <th width="150px">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($accheads as $head)
                                        <tr>
                                            <td>{{ $head->parent_head }}</td>
                                            <td>{{ $head->sub_head }}</td>
                                            <td>{{ $head->head }}</td>
                                            <td>
                                            <button wire:click="edit({{ $head->id }})" class="btn btn-primary btn-sm">Edit</button>
                                                <button wire:click="delete({{ $head->id }})" class="btn btn-danger btn-sm">Delete</button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @endif
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
  <!-- /.content-wrapper -->
