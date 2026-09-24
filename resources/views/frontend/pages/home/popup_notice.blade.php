@if(!empty($popupNotice) && !session()->has('popupClosed'))
    <div class="gplc-popup-wrap" id="gplcPopup">
        <div class="gplc-popup">
            <a href="{{ route('popup.close') }}" class="gplc-popup-close" title="Close">&times;</a>
            @if($popupNotice->image)
                <img src="{{ $popupNotice->image ? asset($popupNotice->image) : ($siteSettings->site_logo ? asset($siteSettings->site_logo) : asset('backend/images/logo.png')) }}" alt="{{ $popupNotice->title }}">
            @endif
            <div class="popup-body">
                <h3>{{ $popupNotice->title }}</h3>
                <a href="{{ url('notice/detail/' . $popupNotice->id) }}" class="btn-gplc">Read More</a>
            </div>
        </div>
    </div>
@endif
