<h1>Vous êtes bien sur votre premiere page</h1>


<div>
    <h3>echo</h3>
    <div></div>
</div>

<div>
    <a href="{{ route('article.add') }}"><button name="ajouter" class="ajouter" id="ajouter"> + </button></a>
    @foreach ($article as $articleItem)
        <h3>{{ $articleItem->title }}</h3>
        <p>{{ $articleItem->content }}</p>
    @endforeach
</div>
