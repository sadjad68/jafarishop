@php
    $productDetailUrl = \App\Library\SiteUrl::product($product);
    $schemaCountry = $settings['schema_address_country'] ?? 'IR';
    $shippingRate = (string) ($settings['schema_shipping_rate'] ?? '0');
    $merchantReturnDays = (int) ($settings['schema_merchant_return_days'] ?? 7);

    $brandName = data_get($brand, 'title');
    if (empty($brandName) && ! empty($settings['siteName_fa'])) {
        $brandName = $settings['siteName_fa'];
    }

    $offers = [
        '@type' => 'Offer',
        'priceCurrency' => 'IRR',
        'price' => (string) (int) ($product['final_price'] ?? 0),
        'availability' => (int) ($product->stock ?? 0) !== 0
            ? 'https://schema.org/InStock'
            : 'https://schema.org/OutOfStock',
        'url' => $productDetailUrl,
        'itemCondition' => 'https://schema.org/NewCondition',
        'shippingDetails' => [
            '@type' => 'OfferShippingDetails',
            'shippingRate' => [
                '@type' => 'MonetaryAmount',
                'value' => $shippingRate,
                'currency' => 'IRR',
            ],
            'shippingDestination' => [
                '@type' => 'DefinedRegion',
                'addressCountry' => $schemaCountry,
            ],
            'deliveryTime' => [
                '@type' => 'ShippingDeliveryTime',
                'handlingTime' => [
                    '@type' => 'QuantitativeValue',
                    'minValue' => 1,
                    'maxValue' => 3,
                    'unitCode' => 'DAY',
                ],
                'transitTime' => [
                    '@type' => 'QuantitativeValue',
                    'minValue' => 1,
                    'maxValue' => 5,
                    'unitCode' => 'DAY',
                ],
            ],
        ],
        'hasMerchantReturnPolicy' => [
            '@type' => 'MerchantReturnPolicy',
            'applicableCountry' => $schemaCountry,
            'returnPolicyCategory' => 'https://schema.org/MerchantReturnFiniteReturnWindow',
            'merchantReturnDays' => $merchantReturnDays,
            'returnMethod' => 'https://schema.org/ReturnByMail',
            'returnFees' => 'https://schema.org/FreeReturn',
        ],
    ];

    $productSchema = [
        '@context' => 'https://schema.org/',
        '@type' => 'Product',
        'name' => $product['title'] ?? '',
        'sku' => (string) ($product['id'] ?? ''),
        'image' => $product->getImage('big'),
        'offers' => $offers,
        'description' => strip_tags($product->seoDescription ?? ''),
    ];

    if (! empty($brandName)) {
        $productSchema['brand'] = [
            '@type' => 'Brand',
            'name' => $brandName,
        ];
    }

    if (count($comments) > 0) {
        $productSchema['aggregateRating'] = [
            '@type' => 'AggregateRating',
            'ratingValue' => number_format((float) $rate, 1, '.', ''),
            'ratingCount' => (string) count($comments),
        ];

        $reviews = $comments->map(function ($comment) {
            return [
                '@type' => 'Review',
                'author' => [
                    '@type' => 'Person',
                    'name' => $comment['name'] ?? '',
                ],
                'datePublished' => (string) $comment->created_at->timestamp,
                'reviewBody' => strip_tags($comment['content'] ?? ''),
                'reviewRating' => [
                    '@type' => 'Rating',
                    'bestRating' => '5',
                    'ratingValue' => (string) (int) ($comment['rate'] ?? 1),
                    'worstRating' => '1',
                ],
            ];
        })->values()->all();

        $productSchema['review'] = count($reviews) === 1 ? $reviews[0] : $reviews;

        $productSchema = [
            '@context' => $productSchema['@context'],
            '@type' => $productSchema['@type'],
            'name' => $productSchema['name'],
            'sku' => $productSchema['sku'],
            'image' => $productSchema['image'],
            'offers' => $productSchema['offers'],
            'aggregateRating' => $productSchema['aggregateRating'],
            'review' => $productSchema['review'],
            'description' => $productSchema['description'],
        ] + (isset($productSchema['brand']) ? ['brand' => $productSchema['brand']] : []);
    } else {
        $productSchema = [
            '@context' => $productSchema['@context'],
            '@type' => $productSchema['@type'],
            'name' => $productSchema['name'],
            'sku' => $productSchema['sku'],
            'image' => $productSchema['image'],
            'offers' => $productSchema['offers'],
            'description' => $productSchema['description'],
        ] + (isset($productSchema['brand']) ? ['brand' => $productSchema['brand']] : []);
    }
@endphp
<script type="application/ld+json">
{!! json_encode($productSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
<script type="application/ld+json">
  {
    "@@context": "https://schema.org/",
    "@@type": "BreadcrumbList",
    "itemListElement": [
      {
        "@@type": "ListItem",
        "position": 1,
        "name": "{{ $settings['siteName_fa'] }}",
        "item": "{{ route('index') }}"
      },
      {
        "@@type": "ListItem",
        "position": 2,
        "name": "{{ $settings['all_product_title'] }}",
        "item": "{{ route('product.get-all') }}"
      },
      {
        "@@type": "ListItem",
        "position": 3,
        "name": "{{ @$product['title'] }}",
        "item": "{{ \App\Library\SiteUrl::product($product) }}"
      }
    ]
  }
</script>
@if (count($faqs) > 0)
  <script type="application/ld+json">
    {!! json_encode([
      "@@context" => "https://schema.org",
      "@@type" => "FAQPage",
      "mainEntity" => $faqs->map(function($faq) {
          return [
              "@@type" => "Question",
              "name" => strip_tags($faq['question']),
              "acceptedAnswer" => [
                  "@@type" => "Answer",
                  "text" => strip_tags($faq['answer']),
              ]
          ];
      })->toArray()
    ], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT) !!}
  </script>
@endif
