@if($settings['show_share_button'] == 1)
@php
    $platform = $settings['share_type'] ?? '';
    $message  = $settings['share_link_message'] ?? '';
    $title    = $settings['share_button_title'] ?? 'پیام';
    $target   = $settings['share_button_number'] ?? '';
    $link     = $settings['share_button_link'] ?? '';
    $productUrl = url()->current();

    $finalMessage = trim($message . "\n" . $productUrl);
    $encodedMessage = urlencode($finalMessage);

    if (!$link){
        switch($platform) {
        case 'whatsapp':
            $shareLink = "https://api.whatsapp.com/send/?phone={$target}&text={$encodedMessage}";
            break;
        case 'telegram':
            $shareLink = "https://t.me/{$target}?text={$encodedMessage}";
            break;
        case 'bale':
            $shareLink = "https://ble.ir/{$target}?text={$encodedMessage}";
            break;
        case 'eitaa':
            $shareLink = "https://eitaa.com/{$target}?text={$encodedMessage}";
            break;
        case 'soroush':
            $shareLink = "https://messenger.soroushapp.com/";
            break;
        case 'facebook':
            $shareLink = "https://www.facebook.com/sharer/sharer.php?u=" . urlencode($productUrl);
            break;
        case 'linkedin':
            $shareLink = "https://www.linkedin.com/sharing/share-offsite/?url=" . urlencode($productUrl);
            break;
        case 'twitter':
            $shareLink = "https://twitter.com/intent/tweet?text={$encodedMessage}";
            break;
        case 'instagram':
            $shareLink = $link ?: 'https://www.instagram.com/';
            break;
        case 'pinterest':
            $shareLink = "https://pinterest.com/pin/create/button/?url=" . urlencode($productUrl) . "&description={$encodedMessage}";
            break;
        case 'tiktok':
            $shareLink = $link ?: 'https://www.tiktok.com/';
            break;
        case 'youtube':
            $shareLink = $link ?: 'https://www.youtube.com/';
            break;
        case 'rubika':
            $shareLink = $link ?: 'https://rubika.ir/';
            break;
        case 'castbox':
            $shareLink = $link ?: 'https://castbox.fm/';
            break;
        case 'aparat':
            $shareLink = $link ?: 'https://www.aparat.com/';
            break;
        default:
            $shareLink = $link ?: $productUrl;
            break;
        }
    } else {
        $shareLink = $link;
    }
@endphp

<a href="{{ $shareLink }}"
    class="{{ $platform }} btn btn-social-pm text-decoration-none{{ !empty($compact) ? ' btn-social-pm--compact' : '' }}"
    target="_blank"
    rel="nofollow noopener"
    aria-label="{{ $title }}">
    @include('layouts.common.social-logo', ['platform' => $platform, 'size' => !empty($compact) ? 22 : 25])
    <span class="pdp-social__label">{{ $title }}</span>
</a>
@endif
