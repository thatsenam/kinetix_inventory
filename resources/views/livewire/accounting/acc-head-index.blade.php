<div>
    @section('title', 'Account Head')

    <div>
        <h1 class="ml-3">Account Head</h1>


        @if($updateAccHead)
            @include('livewire.accounting.acc-head.edit')
        @else
            @include('livewire.accounting.acc-head.create')
        @endif

        @if (session('success'))
            <div class="alert alert-success font-weight-bold">
                {{ session('success') }}
            </div>
        @endif
        @if (session('danger'))
            <div class="alert alert-danger font-weight-bold">
                {{ session('danger') }}
            </div>
        @endif

    <div class="card mt-2">

        <div class="card-body">
            @if(env('APP_DEBUG'))
                <button wire:click="fillDemoData" class="btn btn-info rounded-0 mb-3">
                    Fill Demo Data
                </button>
            @endif
            @forelse ($acc_heads as $acc_head)
                <div class="form-group row">
                    <div class="col-md-3">
                        <input value="{{ $acc_head->parent_head }}" class="form-control bg-white" type="text" disabled>
                    </div>
                    <div class="col-md-3">
                        <input value="{{ $acc_head->sub_head }}" class="form-control bg-white" type="text" disabled>
                    </div>
                    <div class="col-md-4">
                        <input value="{{ $acc_head->head }}" class="form-control bg-white" type="text" disabled>
                    </div>
                    <div class="col-md-2">
                        <button wire:click.prevent="edit({{ $acc_head->id }})" class="btn btn-info rounded-0">
                          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                          </svg>
                        </button>

                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#exampleModal{{$acc_head->id}}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                            </svg>
                        </button>

                        <!-- Modal -->
                        <div class="modal fade" id="exampleModal{{$acc_head->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        Are You Sure ?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="button" data-dismiss="modal" wire:click.prevent="destroy({{ $acc_head->id }})" class="btn btn-primary">Save changes</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            @empty
                <div class="text-center">
                    <h3><span class="badge badge-pill badge-danger">No Account Head to Show</span></h3>
                </div>
            @endforelse
        </div>
    </div>

    @include('livewire.accounting.acc-head.sub-head-modal')

    </div>
</div>

<script>
    window.addEventListener('openSubHeadModal', event => {
        $('#subHeadModal').modal('show');
    });
    window.addEventListener('closeSubHeadModal', event => {
        $('#subHeadModal').modal('hide');
    });
</script>
