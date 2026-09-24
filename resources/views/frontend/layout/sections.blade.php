@php
    $pageId = $page ?? '/';
    $sections = \App\Models\HomeSection::where('is_visible', 1)->orderBy('sort_order')->get();
@endphp

@foreach($sections as $section)
    @php
        $visiblePages = $section->visible_pages ?? ['/'];
    @endphp
    @if(in_array($pageId, $visiblePages))
        @includeIf('frontend.pages.home.' . $section->key)
    @endif
@endforeach
