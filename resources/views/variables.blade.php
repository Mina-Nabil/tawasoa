@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-7">
        <x-datatable id="myTable" :title="$title" :subtitle="$subTitle" :cols="$cols" :items="$items" :atts="$atts" />
    </div>

    <div class="col-lg-5">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{ $formTitle }}</h4>
                <form class="form pt-3" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type=hidden name=id value="{{(isset($variable)) ? $variable->id : ''}}">

                    <div class="form-group">
                        <label>Variable Name</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon11"><i class="ti-user"></i></span>
                            </div>
                            <input type="text" class="form-control" placeholder="Name" name=name value="{{ (isset($variable)) ? $variable->name : old('name')}}" required>
                        </div>
                        <small class="text-danger">{{$errors->first('name')}}</small>
                    </div>

                    <div class="form-group">
                        <label>Unit</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon11"><i class="ti-ruler-alt-2"></i></span>
                            </div>
                            <input type="text" class="form-control" placeholder="Unit" name=unit value="{{ (isset($variable)) ? $variable->unit : old('unit')}}" required>
                        </div>
                        <small class="text-danger">{{$errors->first('unit')}}</small>
                    </div>

                    <button type="submit" class="btn btn-success mr-2">Submit</button>
                </form>
                @isset($variable)
                <hr>
                <h4 class="card-title">Delete Variable</h4>
                <div class="form-group">
                    <button type="button" onclick="confirmAndGoTo('{{$deleteURL}}', 'delete this Variable ?')" class="btn btn-danger mr-2">Delete Variable</button>
                </div>
                <div class="form-group">
                    <small class="text-muted">Variable won't be deleted if it is linked to saved entries on the tool!</small>
                </div>
                @endisset
            </div>
        </div>
    </div>
</div>
@endsection