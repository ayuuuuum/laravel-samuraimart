<div>
    @foreach ($major_categories as $major_category)
        <div class="mb-4">
            <h2>{{ $major_category->name }}</h2>
            @foreach ($categories as $category)
                @if ($category->major_category_id === $major_category->id)
                    <div class="mb-3">
                        <label class="samuraimart-sidebar-category-label"><a href="{{ route('products.index', ['category' => $category->id]) }}" class="h6 link-dark text-decoration-none">{{ $category->name }}</a></label>
                    </div>
                @endif
            @endforeach
        </div>
    @endforeach
</div>