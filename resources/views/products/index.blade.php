@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <div class="card shadow-lg bg-white rounded" style="border-radius: 25px; overflow: hidden; padding-bottom: 20px;">
        <div class="card-header text-white px-3 py-2 d-flex justify-content-between align-items-center" 
            style="background-color:rgb(9, 12, 151); font-weight: bold; font-size: 20px; 
                border-radius: 2px 2px 0 0; margin: 0;">
            <span>List Produk</span>
        </div>
        
        <div class="card-body" style="border-radius: 0 0 25px 25px;">
            <div class="table-responsive"> 
                <table class="table table-striped table-bordered mb-0">
                    <thead class="text-center">
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Nama</th>
                            <th scope="col">Stok</th>
                            <th scope="col">Harga</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            <tr>
                                <td scope="row" class="text-center">{{ $loop->index + 1 }}</td>
                                <td>{{ $product->nama }}</td>
                                <td class="text-center">{{ $product->stok }}</td>
                                <td>Rp {{ number_format($product->harga, 2, ',', '.') }}</td>
                                <td class="text-center">
                                    <form action="{{ route('products.destroy', $product->id) }}" method="post">
                                        @csrf
                                        @method('DELETE')

                                        @can('edit-product')
                                            <a href="{{ route('products.edit', $product->id) }}" 
                                            class="btn btn-primary btn-sm">
                                            <i class="bi bi-pencil-square"></i> Edit
                                            </a>
                                        @endcan

                                        @can('delete-product')
                                            <button type="submit" class="btn btn-sm text-white"
                                                style="background-color: rgb(144, 5, 5); border: none;"
                                                onclick="return confirm('Do you want to delete this product?');">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        @endcan
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-danger">
                                    <strong>Produk Tidak Ditemukan!</strong>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <h2></h2>

                    {{ $products->links() }}

                    
                @can('create-product')
                    <a href="{{ route('products.create') }}" class="btn btn-success btn-sm">
                        <i class="bi bi-plus-circle"></i> Tambah Produk
                    </a>
                @endcan

                </div>
            </div>
        </div>
    </div>

</div>
@endsection
