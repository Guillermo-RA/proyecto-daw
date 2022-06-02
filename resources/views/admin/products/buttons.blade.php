<div class="dropdown d-flex justify-content-center">
    <button class="bg-transparent border-0" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown"
            aria-expanded="false">
        <i class="fa fa-caret-square-down blue-color"></i>
    </button>
    <ul class="dropdown-menu dark-background" aria-labelledby="dropdownMenuButton1">
        <li>
            <a href="{{route('products.view', $product)}}" class="btn btn-primary button-primary-dark btn-sm"><i
                    class="fa fa-eye"></i></a>
        </li>
        <li><a href="{{route('products.edit', $product)}}" class="btn btn-secondary btn-sm"><i
                    class="fa fa-pen"></i></a>
        </li>
        <li>
            <a id="{{$product->id}}" class="btn btn-danger btn-sm products-delete-btn"><i class="fa fa-trash"></i></a>
        </li>
    </ul>
</div>




