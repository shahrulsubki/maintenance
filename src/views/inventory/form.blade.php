<legend>Item Information</legend>

<div class="form-group">
    <label class="col-sm-2 control-label">Category</label>

    <div class="col-md-4">
        @include('maintenance::select.inventory-category', [
            'category_name' => (isset($item) ? $item->category->name : null),
            'category_id'=> (isset($item) ? $item->category->id : null)
        ])
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">Metric</label>

    <div class="col-md-4">
        @include('maintenance::select.metric', [
            'metric' => (isset($item) ? ($item->metric ?: $item->metric->id) : null)
        ])
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">Name</label>

    <div class="col-md-4">
        {!! Form::text('name', (isset($item) ? $item->name : null), ['class'=>'form-control', 'placeholder'=>'Name']) !!}
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">Description</label>

    <div class="col-md-4">
        {!! Form::textarea('description', (isset($item) ? htmlspecialchars($item->description) : null), ['class'=>'form-control', 'placeholder'=>'Description']) !!}
    </div>
</div>

<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        {!! Form::submit('Save', ['class'=>'btn btn-primary']) !!}
    </div>
</div>
