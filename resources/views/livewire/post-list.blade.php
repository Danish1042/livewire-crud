<style>
    .img-fluid {
    max-width: 100%;
    height: 100px;
}
</style>
<div class="container my-3">
    <div class="row border-bottom py-2">
        <div class="col-xl-11">
            <h4 class="text-center fw-bold"> SPA - CRUD App using livewire 3 + laravel 11</h4>
        </div>

        <div class="col-xl-1">
            <a wire:navigate href="{{ route('posts.create') }}" class="btn btn-primary btn-sm">Add Posts</a>
        </div>
    </div>

    {{-- Alert Component --}}
    <div class="my-2">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                {{ session('success') }}
            </div>
        @elseif (session('error'))
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                {{ session('error') }}
            </div>
        @endif
    </div>

    {{-- Table Design --}}
    <div class="card shadow">
        <div class="card-body mt-4 table-responsive">
            <table class="table table-striped">
                <thead>
                    <th>#</th>
                    <th>Featured image</th>
                    <th>Title</th>
                    <th>Content</th>
                    <th>Date</th>
                    <th>Actions</th>
                </thead>

                <tbody>
                    @forelse ($posts as $post)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <a wire:navigate href="{{ route('posts.view', $post->id ) }}"> <img src="{{ Storage::url($post->featured_image) }}" class="img-fluid" width="150px" /></a>
                                </td>
                            <td>
                                <a wire:navigate href="{{ route('posts.view', $post->id ) }}" class="text-decoration-none">{{ $post->title }}</a>
                            </td>
                            <td>{{ $post->content }}</td>
                            <td>
                                <p><small><strong>Posted: </strong>{{ \Carbon\Carbon::parse($post->created_at)->diffForHumans() }}</small>
                                </p>
                                <p><small><strong>Updated: </strong>{{ \Carbon\Carbon::parse($post->updated_at)->diffForHumans() }}</small>
                                </p>
                            </td>
                            <td>
                                <a wire:navigate href="{{ route('posts.edit', $post->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <button type="submit" class="btn btn-danger btn-sm" wire:click="deletePost({{ $post->id }})" wire:confirm = "Are you sure you want to delete this Post?">Delete</button>
                            </td>
                        </tr>
                    @empty
                        <p>No records found!</p>
                    @endforelse
                </tbody>
            </table>
            {{ $posts->links() }}
        </div>
    </div>
</div>

<script>

</script>
