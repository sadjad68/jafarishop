@if(count($services) > 0)
    <nav class="sk-sample-tabs" aria-label="فیلتر نمونه کارها بر اساس خدمت">
        <div class="sk-sample-tabs__list">
            <button type="button"
                    class="sk-sample-tabs__tab"
                    :class="{ 'is-active': !selectedService }"
                    :aria-pressed="!selectedService ? 'true' : 'false'"
                    :disabled="loading"
                    @click="selectService('')">
                همه
            </button>
            @foreach($services as $service)
                <button type="button"
                        class="sk-sample-tabs__tab"
                        :class="{ 'is-active': selectedService === {{ (int) $service['id'] }} }"
                        :aria-pressed="selectedService === {{ (int) $service['id'] }} ? 'true' : 'false'"
                        :disabled="loading"
                        @click="selectService({{ (int) $service['id'] }})">
                    {{ $service['title'] }}
                </button>
            @endforeach
        </div>
    </nav>
@endif
