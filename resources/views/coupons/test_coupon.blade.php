<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Проверить купон
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div style="width: 30%;float: left;">

                <input type="text" name="coupon" class="form-control" placeholder="Ваш купон"  style="height: 30px; margin: 10px 0;"/>

                <button class="btn btn-primary js-test_coupon"  type="button">
                    Проверить купон
                </button>

            </div>
            <div style="width: 60%;float: left; margin-left: 10%">

                <div class="card">
                    <div class="card-body js-text-coupon"></div>
                </div>

                @push('scripts')
                    <script>
                        $(document).ready(function() {
                            $(".js-test_coupon").on('click', function(e) {
                                e.preventDefault();

                                get('{{ route('coupon.get_test_coupon') }}', {
                                    coupon: $("[name='coupon']").val()
                                })
                                .then(response => {
                                    if(response.status === 'success') {
                                        $(".js-text-coupon").html(`
                                            <div class="alert alert-primary" role="alert">
                                                <p class="card-text">Ваш купон: <strong>${response.data.coupon}</strong> со скидкой: <strong>${response.data.discount}%</strong></p>
                                                <p class="card-text">Время действия купона: <strong>С ${response.data.start_discount} По ${response.data.stop_discount}</strong></p>
                                            </div>
                                        `);
                                    } else if(response.status === 'no coupon') {
                                        $(".js-text-coupon").html(`
                                            <div class="alert alert-primary" role="alert">
                                                <p class="card-text">Скидка недоступна</p>
                                            </div>
                                        `);
                                    }
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
