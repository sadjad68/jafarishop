<div class="site-page-loader__steps">
    <span class="site-page-loader__bone site-page-loader__step"></span>
    <span class="site-page-loader__bone site-page-loader__step"></span>
    <span class="site-page-loader__bone site-page-loader__step"></span>
</div>
<div class="site-page-loader__cart">
    <div class="site-page-loader__cart-list">
        @for ($i = 0; $i < 3; $i++)
            <span class="site-page-loader__cart-row">
                <span class="site-page-loader__bone site-page-loader__cart-thumb"></span>
                <span class="site-page-loader__cart-copy">
                    <span class="site-page-loader__bone site-page-loader__line site-page-loader__line--wide"></span>
                    <span class="site-page-loader__bone site-page-loader__line"></span>
                </span>
            </span>
        @endfor
    </div>
    <span class="site-page-loader__bone site-page-loader__panel site-page-loader__panel--tall"></span>
</div>
