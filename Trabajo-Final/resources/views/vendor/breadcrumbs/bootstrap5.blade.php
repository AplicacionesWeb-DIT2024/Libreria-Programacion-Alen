@if (count($breadcrumbs))
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb bg-white p-2 rounded shadow-sm">
            @foreach ($breadcrumbs as $breadcrumb)
                @if ($loop->first)
                    <li class="breadcrumb-item">
                        <a href="{{ $breadcrumb->url }}" class="text-decoration-none text-primary">
                            <i class="fa-solid fa-house me-1"></i> {{ $breadcrumb->title }}
                        </a>
                    </li>
                @elseif ($breadcrumb->url && !$loop->last)
                    <li class="breadcrumb-item">
                        <a href="{{ $breadcrumb->url }}" class="text-decoration-none text-secondary">
                            {{ $breadcrumb->title }}
                        </a>
                    </li>
                @else
                    <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">
                        {{ $breadcrumb->title }}
                    </li>
                @endif
            @endforeach
        </ol>
    </nav>
@endif
