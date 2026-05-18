<div class="box box-info padding-1">
    <div class="box-body">
        
        <div class="form-group">
            {{ Form::label('orader_number') }}
            {{ Form::text('orader_number', $orderspostingdetail->orader_number, ['class' => 'form-control' . ($errors->has('orader_number') ? ' is-invalid' : ''), 'placeholder' => 'Orader Number']) }}
            {!! $errors->first('orader_number', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('product_id') }}
            {{ Form::text('product_id', $orderspostingdetail->product_id, ['class' => 'form-control' . ($errors->has('product_id') ? ' is-invalid' : ''), 'placeholder' => 'Product Id']) }}
            {!! $errors->first('product_id', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('quantity') }}
            {{ Form::text('quantity', $orderspostingdetail->quantity, ['class' => 'form-control' . ($errors->has('quantity') ? ' is-invalid' : ''), 'placeholder' => 'Quantity']) }}
            {!! $errors->first('quantity', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('quantity_acc') }}
            {{ Form::text('quantity_acc', $orderspostingdetail->quantity_acc, ['class' => 'form-control' . ($errors->has('quantity_acc') ? ' is-invalid' : ''), 'placeholder' => 'Quantity Acc']) }}
            {!! $errors->first('quantity_acc', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('price') }}
            {{ Form::text('price', $orderspostingdetail->price, ['class' => 'form-control' . ($errors->has('price') ? ' is-invalid' : ''), 'placeholder' => 'Price']) }}
            {!! $errors->first('price', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('price_acc') }}
            {{ Form::text('price_acc', $orderspostingdetail->price_acc, ['class' => 'form-control' . ($errors->has('price_acc') ? ' is-invalid' : ''), 'placeholder' => 'Price Acc']) }}
            {!! $errors->first('price_acc', '<div class="invalid-feedback">:message</div>') !!}
        </div>

    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>