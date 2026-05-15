<form class="form-horizontal"
      action="{{ route('commissions.pay_to_seller') }}"
      method="POST">
    @csrf

    <!-- Modal Header -->
    <div class="modal-header">
        <h5 class="modal-title h6">{{ translate('Pay to Seller') }}</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>

    <!-- Modal Body -->
    <div class="modal-body">

        <!-- Seller Info -->
        <table class="table table-bordered table-striped">
            <tbody>
                <tr>
                    <td>{{ translate('Due to Seller') }}</td>
                    <td>{{ single_price($user->shop->admin_to_pay) }}</td>
                </tr>

                <tr>
                    <td>{{ translate('Requested Amount') }}</td>
                    <td>{{ single_price($seller_withdraw_request->amount) }}</td>
                </tr>

                @if ($user->shop->bank_payment_status == 1)
                    <tr>
                        <td>{{ translate('Bank Name') }}</td>
                        <td>{{ $user->shop->bank_name }}</td>
                    </tr>
                    <tr>
                        <td>{{ translate('Account Name') }}</td>
                        <td>{{ $user->shop->bank_acc_name }}</td>
                    </tr>
                    <tr>
                        <td>{{ translate('Account Number') }}</td>
                        <td>{{ $user->shop->bank_acc_no }}</td>
                    </tr>
                    <tr>
                        <td>{{ translate('Routing Number') }}</td>
                        <td>{{ $user->shop->bank_routing_no }}</td>
                    </tr>
                @endif
            </tbody>
        </table>

        @if ($user->shop->admin_to_pay > 0)

            <!-- Hidden Inputs -->
            <input type="hidden" name="shop_id" value="{{ $user->shop->id }}">
            <input type="hidden" name="withdraw_request_id" value="{{ $seller_withdraw_request->id }}">
            <input type="hidden" name="payment_withdraw" value="withdraw_request">

            <!-- Pay Amount -->
            <div class="form-group row">
                <label class="col-sm-3 col-from-label">
                    {{ translate('Pay Amount') }}
                </label>
                <div class="col-sm-9">
                    <input type="number"
                           name="amount"
                           step="0.01"
                           min="0"
                           class="form-control"
                           value="{{ min($seller_withdraw_request->amount, $user->shop->admin_to_pay) }}"
                           required>
                </div>
            </div>

            <!-- Payment Method (FIXED) -->
            <div class="form-group row">
                <label class="col-sm-3 col-from-label">
                    {{ translate('Payment Method') }}
                </label>
                <div class="col-sm-9">
                    <select name="payment_option"
                            id="payment_option"
                            class="form-control"
                            required>
                        <option value="">{{ translate('Select Payment Method') }}</option>
                        <option value="cash">{{ translate('Cash') }}</option>
                        <option value="bank_payment">{{ translate('Bank Payment') }}</option>
                    </select>
                </div>
            </div>

            <!-- Transaction Code -->
            <div class="form-group row d-none" id="txn_div">
                <label class="col-sm-3 col-from-label">
                    {{ translate('Transaction Code') }}
                </label>
                <div class="col-sm-9">
                    <input type="text"
                           name="txn_code"
                           class="form-control"
                           placeholder="Enter bank transaction code">
                </div>
            </div>

        @endif
    </div>

    <!-- Modal Footer -->
    <div class="modal-footer">
        @if ($user->shop->admin_to_pay > 0)
            <button type="submit" class="btn btn-primary">
                {{ translate('Pay') }}
            </button>
        @endif
        <button type="button" class="btn btn-light" data-dismiss="modal">
            {{ translate('Cancel') }}
        </button>
    </div>
</form>

<!-- JS -->
<script>
$(document).ready(function () {
    $('#payment_option').on('change', function () {
        if ($(this).val() === 'bank_payment') {
            $('#txn_div').removeClass('d-none');
        } else {
            $('#txn_div').addClass('d-none');
        }
    });
});
</script>
