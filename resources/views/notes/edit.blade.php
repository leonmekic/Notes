<html>
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
</head>
<body>
<h1>Edit note</h1>
<form method="post" action="/notes/{{$note->id}}">
    {{method_field('PATCH')}}
    {{ @csrf_field() }}
    <div>
        <input type="text" name="note" placeholder="Note title" value="{{ $note->note }}">
    </div>
    <div>
        <textarea name="description" placeholder="Note description">{{ $note->description }}</textarea>
    </div>
    <input type="radio" name="status" value="Public"> Public <br>
    <input type="radio" name="status" value="Private"> Private <br>
    <fieldset>
        @foreach($tags as $tag)
        <input type="checkbox" name="tag[]" value="{{$tag}}">{{$tag}}
        @endforeach
    </fieldset>
    <div>
        <button type="submit">Update Note</button>
    </div>
</form>
</body>
</html>