 <div class="box-body">

     <div class="form-group">
         {{ Form::hidden('orader_number', $ordersposting->orader_number, ['class' => 'form-control' . ($errors->has('orader_number') ? ' is-invalid' : ''), 'placeholder' => 'Orader Number']) }}
         {!! $errors->first('orader_number', '<div class="invalid-feedback">:message</div>') !!}
     </div>

     <table class="table-hover table">
         @foreach (@$ordersposting->OrderspostingDetails as $item)
             <input type="hidden" name="id[]" value=" {{ $item->id }} " />
             <input type="hidden" name="orderspostings_id" value=" {{ $item->orderspostings_id }} " />
             <tr>
                 <td>
                     <label class="form-label d-block">Product name</label>
                     <select name="product[]" id="model" class="form-control select2">
                         <option value="All">Select</option>
                         @foreach ($productList as $key => $iteam)
                             <option value="{{ $iteam }}" {{ $iteam == $item->product_id ? 'selected' : '' }}>
                                 {{ $key }}</option>
                         @endforeach
                     </select>
                 </td>
                 <td> <label class="form-label">Quintity</label>
                     <input type="number" name="quintity[]" value="{{ $item->quantity }}"
                         class="form-control quintity_quintity" />
                 </td>

                 <td> <label class="form-label">Price</label>
                     <input type="number" name="price[]" value="{{ $item->price }}"
                         class="form-control price_quintity" />
                 </td>


                 <td> <label class="form-label">Dicount</label>
                     <input type="number" name="price_acc[]" value="0.00" class="form-control price_acc_quintity" />
                 </td>

                 <td>
                 <td><label style=" width: 100%; "> <br></label><button type="button" name="remove" id="2"
                         class="btn btn-danger btn_remove">X</button></td>
                 </td>
             </tr>
         @endforeach

     </table>

 </div>
 <div class="box-footer mt20">
     <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
 </div>
