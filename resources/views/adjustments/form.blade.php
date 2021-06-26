<div class="form-group">
    <div class="row">
        <div class="col">
            <label for="warehouse_id">Warehouse</label>

            <span class="text-danger font-bolder">*</span>

            <select class="form-control selectable" id="warehouse_id" name="warehouse_id" required="true">
                <option value="" style="display: none;"
                        {{ old('warehouse_id', optional($adjustment)->warehouse_id ?: '') == '' ? 'selected' : '' }} disabled
                        selected>Select warehouse
                </option>
                @foreach ($warehouses as $key => $warehouse)
                    <option
                        value="{{ $key }}" {{ old('warehouse_id', optional($adjustment)->warehouse_id) == $key ? 'selected' : '' }}>
                        {{ $warehouse }}
                    </option>
                @endforeach
            </select>

            {!! $errors->first('warehouse_id', '<p class="form-text text-danger">:message</p>') !!}

        </div>
        <div class="col">
            <label for="date">Date</label>


            <span class="text-danger font-bolder">*</span>
            <input class="form-control  {{ $errors->has('date') ? 'is-invalid' : '' }}" name="date" type="date"
                   id="date" value="{{ old('date', optional($adjustment)->date) }}" required>

            {!! $errors->first('date', '<p class="form-text text-danger">:message</p>') !!}

        </div>

    </div>
</div>

<div class="form-group">
    <table class="table table-bordered">
        <thead class="bg-gray-300">
        <tr>
            <th scope="col">#</th>
            <th scope="col">Product</th>
            <th scope="col">Quantity</th>
            <th scope="col">Type</th>
            <th scope="col" class="text-center"><i class="fa fa-trash"></i></th>
        </tr>
        </thead>


        <tbody id="content">
        @foreach($ad??[] as $a)
            <tr>
                <td id="sl"><span class="slsd"></span></td>
                <td>
                    <select name="pid[]" class="form-control selectable">
                        <option></option>
                        @foreach($products as $product)
                            <option
                                value="{{ $product->id }}" {{ $a->pid==$product->id?'selected':'' }}> {{ $product->product_name }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="number" step="any" name="qnt[]" class="form-control"
                           value="{{ $a->add_qnt>0?$a->add_qnt:$a->sub_qnt }}">
                </td>
                <td>
                    <select name="type[]" class="form-control selectable">
                        <option {{ $a->add_qnt>0?'selected':'' }} value="Add">Add (+)</option>
                        <option {{ $a->sub_qnt>0?'selected':'' }} value="Subtract">Subtract (-)</option>
                    </select>
                </td>
                <td>
                    <button type="button" class="btn delete"><i class="fa fa-trash text-danger"></i></button>
                </td>
            </tr>

        @endforeach
        </tbody>

    </table>
    <button type="button" class="btn btn-primary" id="addRowBtn">+ Add Row</button>
</div>

<div class="form-group">
    <div class="col">
        <label for="note">Note</label>


        <textarea class="form-control" name="note" cols="50" rows="10" id="note" minlength="1"
                  maxlength="1000">{{ old('note', optional($adjustment)->note) }}</textarea>
        {!! $errors->first('note', '<p class="form-text text-danger">:message</p>') !!}

    </div>
</div>

<div class="hidden" hidden>
    <div>
        <table>
            <tr id="from">
                <td id="sl"><span class="slsd"></span></td>
                <td>
                    <select name="pid[]" class="form-control">
                        <option></option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}"> {{ $product->product_name }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="number" step="any" name="qnt[]" class="form-control" value="0">
                </td>
                <td>
                    <select name="type[]" class="form-control">
                        <option value="Add">Add (+)</option>
                        <option value="Subtract">Subtract (-)</option>
                    </select>
                </td>
                <td>
                    <button type="button" class="btn"><i class="fa fa-trash"></i></button>
                </td>
            </tr>
        </table>
    </div>
</div>
