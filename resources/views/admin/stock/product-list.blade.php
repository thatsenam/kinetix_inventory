<div class="">

    @if(count($products)>0)
        <div class="row align-items-center">
            <div class="col">Product</div>
            <div class="col">Quantity</div>
            <div class="col">Price (<small>Per Quantity</small>)</div>
        </div>
    @else
        <div class="row align-items-center">
            <h4>No Products are available in selected category</h4>
        </div>
    @endif


    @foreach($products as $index=> $product)
        <div class="row mb-2">
            <div class="col">
                <span> {{ $index + 1 }}. </span> <span>{{ $product->product_name }}
                    <input type="text" name="product_id[]" hidden value="{{ $product->id }}"></span>
            </div>
            <div class="col">
                <input type="number" class="form-control" step="any" name="qnt[]" value="{{ $product->qnt??0  }}">
            </div>
            <div class="col">
                <input type="number" class="form-control" step="any" name="price[]" value="{{ $product->price??0  }}">
            </div>
        </div>
    @endforeach
</div>


