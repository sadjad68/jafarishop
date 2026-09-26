@foreach($galleries as $gallery)
    @php
        $modalId = 'galleryModal' . $gallery['id'];
        $titleId = 'galleryModalTitle' . $gallery['id'];
        $hasCaption = filled($gallery['title']) || filled($gallery['description']);
    @endphp
    <div
        class="modal fade gallery-lightbox"
        id="{{ $modalId }}"
        tabindex="-1"
        aria-labelledby="{{ $hasCaption && filled($gallery['title']) ? $titleId : $modalId }}"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content gallery-lightbox__panel">
                <button
                    type="button"
                    class="gallery-lightbox__close"
                    data-bs-dismiss="modal"
                    aria-label="بستن"
                >
                    <i class="bi bi-x-lg" aria-hidden="true"></i>
                </button>
                <figure class="gallery-lightbox__figure">
                    <img
                        src="{{ $gallery['item_image'] }}"
                        class="gallery-lightbox__img"
                        alt="{{ $gallery['title'] }}"
                        title="{{ $gallery['title'] }}"
                    >
                    @if($hasCaption)
                        <figcaption class="gallery-lightbox__caption">
                            @if(filled($gallery['title']))
                                <p class="gallery-lightbox__title" id="{{ $titleId }}">{{ $gallery['title'] }}</p>
                            @endif
                            @if(filled($gallery['description']))
                                <p class="gallery-lightbox__text">{{ $gallery['description'] }}</p>
                            @endif
                        </figcaption>
                    @endif
                </figure>
            </div>
        </div>
    </div>
@endforeach
