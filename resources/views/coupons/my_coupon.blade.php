<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Получить купон с персональной скидкой
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div style="width: 30%;float: left;">
                <button class="btn btn-primary js-get_coupon"  type="button">
                    Получить скидку
                </button>
            </div>
            <div style="width: 60%;float: left; margin-left: 10%">

                <div class="card">
                    <div class="card-body js-text-coupon"></div>
                </div>

                @push('scripts')
                        <script>
                            $(document).ready(function() {
                                $(".js-get_coupon").on('click', function(e) {
                                    e.preventDefault();

                                    get('{{ route('coupon.generate_discount') }}')
                                    .then(response => {

                                        $(".js-text-coupon").html(`
                                        <div class="alert alert-primary" role="alert">
                                            <p class="card-text">Ваш купон: <strong>${response.data.coupon}</strong> со скидкой: <strong>${response.data.discount}%</strong></p>
                                            <p class="card-text">Время действия купона: <strong>С ${response.data.start_discount} По ${response.data.stop_discount}</strong></p>
                                        </div>
                                        `);
                                    })
                                    .catch(error => {
                                        $(".js-text-coupon").html(`
                                            <div class="alert alert-primary" role="alert">
                                                <p class="card-text">Произошла ошибка</p>
                                            </div>
                                        `);
                                    });
                                })
                            })
                        </script>
                    @endpush
            </div>
        </div>
    </div>
</x-app-layout>
