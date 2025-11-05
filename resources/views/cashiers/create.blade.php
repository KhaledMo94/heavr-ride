<x-dashboard.main-layout>

    <div class="card-body">
        <form class="my-3" action="{{ route('cashiers.orders.store') }}" method="post">
            @csrf

            <div class="form-group">
                <label for="card-no">{{ __('Client Card Number') }}</label>
                <input type="text" name="card_no" class="form-control" id="card-no"
                    placeholder="{{ __('Card Number') }}" required value="{{ old('card_no') }}">
            </div>

            <div class="form-group">
                <label for="sum">{{ __('Select A Service') }}</label>
                <div class="input-group">
                    <select name="service_id" id="service_id" class="custom-select" required>
                        <option value="" disabled selected>{{ __('Select Service') }}</option>
                        @foreach ($services as $service)
                            <option value="{{ $service->id }}"
                                {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                {{ $service->getTranslation('name', app()->getLocale()) }}</option>
                        @endforeach
                    </select>
                    <div class="input-group-append">
                        <span class="input-group-text"><i class="fas fa-concierge-bell"></i></span>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="sum">{{ __('Order Sum') }}</label>
                <input type="number" name="sum" class="form-control" id="sum"
                    placeholder="{{ __('Order Sum') }}" required value="{{ old('sum') }}">
            </div>

            <div class="form-group">
                <label for="client_name">{{ __('Client Name') }}</label>
                <input type="text" name="client_name" class="form-control" disabled id="client_name"
                    placeholder="{{ __('Client Name') }}" value="{{ old('client_name') }}">
            </div>

            <div class="form-group">
                <label for="client_phone">{{ __('Client Phone') }}</label>
                <input type="text" name="client_phone" class="form-control " disabled id="client_phone"
                    placeholder="{{ __('Client Phone') }}" value="{{ old('client_phone') }}">
            </div>

            <div class="form-group">
                <label for="payable_amount">{{ __('Payable Amount') }}</label>
                <input type="text" name="payable_amount" class="form-control " disabled id="payable_amount"
                    placeholder="{{ __('Payable Amount') }}" value="{{ old('payable_amount') }}">
            </div>

            <button type="submit" class="btn btn-success btn-block mb_40">{{ __('Create') }}</button>
        </form>

    </div>

    <script>
        $(document).ready(function() {
            $('#sum').on('keyup', function() {
                if ($('#card-no').val().length >= 8 && $('#sum').val() > 0) {
                    // setTimeout(function() {
                        $.ajax({
                            url: "{{ route('cashiers.orders.getPayableDetails') }}",
                            type: "get",
                            dataType: "json",

                            data: {
                                card_no: $('#card-no').val(),
                                sum: $('#sum').val(),
                                service_id: $('#service_id').val()
                            },
                            success: function(response) {
                                document.getElementById('client_name').value = response
                                    .name;
                                document.getElementById('client_phone').value = response
                                    .phone;
                                document.getElementById('payable_amount').value =
                                    response.payable_amount;
                            },
                            error: function(xhr) {
                                alert("{{ __('Error fetching client data') }}");
                            }
                        });
                    // }, 500);
                }
            })
        });
    </script>
</x-dashboard.main-layout>
