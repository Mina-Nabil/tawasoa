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
                    <input type=hidden name=id value="{{(isset($user)) ? $user->id : ''}}">
                    <div class="form-group">
                        <label>User Name*</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon11"><i class="ti-user"></i></span>
                            </div>
                            <input type="text" class="form-control" placeholder="Username" name=username aria-label="Username" aria-describedby="basic-addon11"
                                value="{{ (isset($user)) ? $user->username : old('username')}}" required>
                        </div>
                        <small class="text-danger">{{$errors->first('username')}}</small>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Full Name*</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon22"><i class="ti-user"></i></span>
                            </div>
                            <input type="text" class="form-control" name=fullname placeholder="Full Name" aria-label="Full Name" aria-describedby="basic-addon22"
                                value="{{ (isset($user)) ? $user->fullname : old('fullname')}}" required>
                        </div>
                        <small class="text-danger">{{$errors->first('fullname')}}</small>
                    </div>

                    <div class="form-group">
                        <label for="input-file-now-custom-1">User Type</label>
                        <div class="input-group mb-3">
                            <select name=type class="form-control">
                                <option value="Entry" {{ (isset($user) && $user->type=="Entry") ? "selected" : "" }}>Entry</option>
                                <option value="Admin" {{ (isset($user) && $user->type=="Admin") ? "selected" : "" }}>Admin</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Password*</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon33"><i class="ti-lock"></i></span>
                            </div>
                            <input type="text" class="form-control" name=password placeholder="Password" aria-label="Password" aria-describedby="basic-addon33" @if($isPassNeeded) required @endif>
                            <small class="text-danger">{{$errors->first('password')}}</small>

                        </div>
                    </div>
                    <button type="submit" class="btn btn-success mr-2">Submit</button>
                </form>
                @isset($user)
                <hr>
                <h4 class="card-title">Delete User</h4>
                <div class="form-group">
                    <button type="button" onclick="confirmAndGoTo('{{$deleteURL}}', 'delete this User ?')" class="btn btn-danger mr-2">Delete User</button>
                </div>
                <div class="form-group">
                    <small class="text-muted">User won't be deleted if he created entries on the system!</small>
                </div>
                @endisset
            </div>
        </div>
    </div>
</div>
@endsection