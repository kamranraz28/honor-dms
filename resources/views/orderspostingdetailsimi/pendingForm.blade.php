<div class="box box-info padding-1">
    <div class="box-body">
        {{--  @dump($orderspostingdetail)  --}}

        <input name="orderspostingdetails_id" value="{{ $orderspostingdetail->id }}" type="hidden" />
        @for ($i = 0; $i < $remainQuantity; $i++)
            <div class="form-group">
                <span class="">{{ $i + 1 }}</span><label>{{ $orderspostingdetail->Product->name }} <span class="text-danger">(IMEI/SNO)</span></label>
                <input type="text" name="imi[]" class="form-control verify-imei" required data-hid="snos{{$i}}" />
                <span id="snos{{$i}}text" class="text-danger"></span>
            </div>
        @endfor
    </div>
    <div class="box-footer mt20">
        <button type="submit"  class="btn btn-primary btn-lg">{{ __('Submit') }}</button>
    </div>
</div>

<!-- Add this modal code to your HTML -->
<div class="modal fade" id="imeiAlertModal" tabindex="-1" role="dialog" aria-labelledby="imeiAlertModalLabel" aria-hidden="true" style="background-color: rgba(0, 0, 0, 0.5);">
    <div class="modal-dialog" role="document" style="margin-top: 10%;">
        <div class="modal-content" style="border-radius: 10px; box-shadow: 0px 0px 20px 0px rgba(0,0,0,0.5);">
            <div class="modal-header" style="background-color: #dc3545; color: #fff; border-bottom: none; border-top-left-radius: 10px; border-top-right-radius: 10px;">
                <h5 class="modal-title" id="imeiAlertModalLabel">IMEI Alert for: <span style="margin-left: 20px; font-size: 20px" id="imeiValue"></span></h5>
                
            </div>
            <div class="modal-body" style="font-size: 16px;">
                <p>Please change this IMEI. This IMEI has already been assigned to another order.</p>
            </div>
            <div class="modal-footer" style="border-top: none;">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>



<script>
    $(document).ready(function () {
        $(".verify-imei").on('keyup', function (event) {
            var hid = $(this).data('hid');
            var sno = $(this).val().trim();
            var route = "{{ route('ajax.varifyimeino') }}/" + sno;

            if (sno === '') {
                clearImeiVerification(hid);
                return; 
            }

            $.get(route)
                .done(function (data) {
                    handleImeiVerification(hid, data, sno); // Pass sno to the handleImeiVerification function
                })
                .fail(function () {
                    console.error("Error in AJAX request");
                });
        });
    });

    function handleImeiVerification(hid, data, sno) {
        var productArea = $("#" + hid);
        var textArea = $("#" + hid + "text");

        if (data == 1) {
            productArea.val('');
            $('#imeiValue').text(sno); // Display sno in the modal
            $('#imeiAlertModal').modal('show');
        } else {
            textArea.text(data);
            productArea.val(data);
        }
    }

    function clearImeiVerification(hid) {
        var textArea = $("#" + hid + "text");
        textArea.text("");
    }
</script>


