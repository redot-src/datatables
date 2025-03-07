<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-3">
    @foreach ($filters as $filter)
        <div>
            {!! $filter->render() !!}
        </div>
    @endforeach
</div>
