<div class="table-responsive-sm">
    <table class="table table-striped" id="posts-table">
        <thead>
            <tr>
                <th>Title</th>
        <th>Content</th>
        <th>Author Id</th>
        <th>Farm Id</th>
        <th>Farmed Type Id</th>
        <th>Post Type Id</th>
        <th>Solved</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($posts as $post)
            <tr>
                <td>{{ $post->title }}</td>
            <td>{{ $post->content }}</td>
            <td>{{ $post->author_id }}</td>
            <td>{{ $post->farm_id }}</td>
            <td>{{ $post->farmed_type_id }}</td>
            <td>{{ $post->post_type_id }}</td>
            <td>{{ $post->solved }}</td>
                <td>
                    {!! Form::open(['route' => ['posts.destroy', $post->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('posts.show', [$post->id]) }}" class='btn btn-ghost-success'><i class="fa fa-eye"></i></a>
                        <a href="{{ route('posts.edit', [$post->id]) }}" class='btn btn-ghost-info'><i class="fa fa-edit"></i></a>
                        {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-ghost-danger', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>