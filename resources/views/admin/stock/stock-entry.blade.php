@extends('layouts.admin.app_pos')
@section('content')
    <div class="content-wrapper">
        <div class="card">
            <div class="card-body">
                <h4>Opening Stock Entry</h4>
                <div class="form-group">
                    <label for="category">Select Category</label>
                    <select name="category" id="category" class="form-control searchable" style="width: 300px">
                        <option></option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}"> {{ $category->name }}</option>
                        @endforeach
                    </select>
                    <hr>
                    <form action="{{ route('save-stock-entry') }}" method="post">
                        @csrf
                        <div id="content">

                        </div>
                        <div class="float-right">
                            <button class="btn btn-primary" id="saveBtn" disabled>Save Stock</button>
                        </div>
                    </form>

                </div>

            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $('#category').on('change', changeProductListByCategory);

            async function changeProductListByCategory() {
                let category_id = $('#category').val()
                let request = await fetch("{{ url('product-list') }}/" + category_id);
                let response = await request.text();
                $('#content').html(response)
                $('#saveBtn').prop('disabled', false)
            }
        });
    </script>

@endsection


