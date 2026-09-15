@extends('layouts.app')
@section('title', 'Manage Categories')
@section('content')
<h2 class="mb-4">Categories</h2>
<div class="row g-4">
    <div class="col-md-8">
        <table class="table">
            <thead><tr><th>Name</th><th>Parent</th><th>Active</th><th></th></tr></thead>
            <tbody>
            @foreach ($categories as $cat)
                <tr>
                    <td>{{ $cat->name }}</td>
                    <td>{{ $cat->parent->name ?? '—' }}</td>
                    <td>{{ $cat->is_active ? 'Yes' : 'No' }}</td>
                    <td>
                        <form method="POST" action="{{ route('admin.categories.destroy', $cat) }}">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $categories->links() }}
    </div>
    <div class="col-md-4">
        <div class="card p-3">
            <h6>Add Category</h6>
            <form method="POST" action="{{ route('admin.categories.store') }}">
                @csrf
                <input type="text" name="name" class="form-control form-control-sm mb-2" placeholder="Category name" required>
                <select name="parent_id" class="form-select form-select-sm mb-2">
                    <option value="">No parent (top-level)</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
                <button class="btn btn-primary btn-sm w-100">Add</button>
            </form>
        </div>
    </div>
</div>
@endsection
