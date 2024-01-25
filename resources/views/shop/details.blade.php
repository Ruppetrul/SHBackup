<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3><strong>{{ $shop['name'] }}</strong> ({{__('states.' . $shop['state'])}})</h3>
                    <br>
                    <p>{{__('general.created_at')}} {{ $shop['created_at']->format('Y-m-d') }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <ul class="nav nav-tabs" id="shopTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active"
                                    id="home-tab"
                                    data-bs-toggle="tab"
                                    data-bs-target="#home"
                                    type="button"
                                    role="tab"
                                    aria-controls="home"
                                    aria-selected="true">{{__('shop.items')}}</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link"
                                    id="profile-tab"
                                    data-bs-toggle="tab"
                                    data-bs-target="#profile"
                                    type="button" role="tab"
                                    aria-controls="profile"
                                    aria-selected="false">{{__('shop.order_history')}}</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link"
                                    id="contact-tab"
                                    data-bs-toggle="tab"
                                    data-bs-target="#contact"
                                    type="button"
                                    role="tab"
                                    aria-controls="contact"
                                    aria-selected="false">{{__('shop.analytics')}}</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="shopTabsContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <div class="container py-6">
                                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4">
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">...</div>
                        <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">...</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
