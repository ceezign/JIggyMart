@extends('layouts.app')
@section('title', 'Manage Reviews')
@section('content')
<h2 class="mb-4">Reviews</h2>
<table class="table">
    <thead><tr><th>Product</th><th>User</th><th>Rating</th><th>Comment</th><th>Status</th><th></th></tr></thead>
    <tbody>
    @foreach ($reviews as $review)
        <tr>
            <td>{{ $review->product->name }}</td>
            <td>{{ $review->user->name }}</td>
            <td>{{ $review->rating }} ★</td>
            <td>{{ Str::limit($review->comment, 50) }}</td>
            <td class="text-capitalize">{{ $review->status }}</td>
            <td>
                <form method="POST" action="{{ route('admin.reviews.moderate', $review) }}" class="d-flex gap-1">
                    @csrf @method('PATCH')
                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="approved" @selected($review->status=='approved')>Approved</option>
                        <option value="pending" @selected($review->status=='pending')>Pending</option>
                        <option value="rejected" @selected($review->status=='rejected')>Rejected</option>
                    </select>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
{{ $reviews->links() }}
@endsection
