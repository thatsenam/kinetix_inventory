<div wire:ignore.self class="modal fade text-dark" id="subHeadModal" tabindex="-1" role="dialog" aria-labelledby="subHeadModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header bg-white text-center">
          <h3 class="modal-title w-100" id="subHeadModalLabel">Add Sub Head</h3>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body bg-white">
            <form class="">
                <div class="form-group row">
                    <label for="new_subhead" class="col-sm-4 col-form-label text-dark font-weight-bold">
                        Add Sub Head
                        <span class="text-danger">*</span>
                    </label>
                    <div class="col-md-8">
                        <input type="text" wire:model.lazy="new_subhead" id="new_subhead"
                            class="form-control form-control-sm @error('new_subhead') is-invalid @enderror">
                        @error('new_subhead')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer bg-white">
          <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Cancel</button>
          <button type="button" wire:click.prevent="newSubhead" class="btn btn-primary">SAVE</button>
        </div>
      </div>
    </div>
  </div>