<html>
<body>
<h1>Create a new note</h1>
<form method="POST" action="/notes">
    {{@csrf_field()}}
    <div>
        <input type="text" name="note" placeholder="Note title">
    </div>
    <div>
        <textarea name="description" placeholder="Note description"></textarea>
    </div>
    <input type="radio" name="status" value="Public"> Public <br>
    <input type="radio" name="status" value="Private"> Private <br>
    <fieldset>
        @foreach($tags as $tag)
        <input type="checkbox" name="tag[]" value="{{$tag}}"/>{{$tag}}
        @endforeach
    </fieldset>
    <div>
        <button type="submit">Create Note</button>
    </div>
</form>
</body>
</html>