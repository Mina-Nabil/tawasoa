@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-5">
        <x-datatable id="myTable" :title="$varsTitle" :subtitle="$varsSubtitle" :cols="$varsCols" :items="$varsItems" :atts="$varsAtts" />
    </div>

    <div class="col-lg-7">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Results</h4>
                <h6 class="card-subtitle">Check all equations results for {{$entry->company_name}}</h6>
                <ul class="list-group">
                    <?php $total=0 ?>
                    @foreach($equations as $eq)
                    @if(Auth::user()->isAdmin())
                    <a href="javascript:void(0)" class="list-group-item list-group-item-action flex-column align-items-start">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-4 text-dark">{{$eq->name}}</h5>
                            <small>{{$eq->expression}}</small>
                        </div>
                        <p class="mb-1">{{$eq->result}} ({{$eq->mapped}})</p>
                    </a>
                    @endif
                    <?php 
                        if(isset($eq->mapped) && is_numeric($eq->mapped))
                        $total+=$eq->mapped;
                    ?>
                    @endforeach
                    <a href="javascript:void(0)" class="list-group-item list-group-item-action flex-column align-items-start">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-4 text-dark">Entry Score</h5>
                        </div>
                        <p class="mb-1">{{$total}}</p>
                    </a>
                </ul>
                <div class="form-group mt-4">
                    <button type="button" onclick="confirmAndGoTo('{{$deleteURL}}', 'delete this Entry ?')" class="btn btn-danger mr-2">Delete Entry</button>
                    @if($entry->is_main==0)
                    <button type="button" onclick="confirmAndGoTo('{{$setAsMain}}', 'set the Entry as Main ?')" class="btn btn-info mr-2">Set as Main</button>
                    @else
                    <button type="button" onclick="confirmAndGoTo('{{$setAsNotMain}}', 'unset the Entry as Main ?')" class="btn btn-warning mr-2">Unset as Main</button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection