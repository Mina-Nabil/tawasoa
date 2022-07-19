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
                    <input type=hidden name=id value="{{(isset($equation)) ? $equation->id : ''}}">

                    <div class="form-group">
                        <label>Equation Name</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon11"><i class="ti-user"></i></span>
                            </div>
                            <input type="text" class="form-control" placeholder="Name" name=name value="{{ (isset($equation)) ? $equation->name : old('name')}}" required>
                        </div>
                        <small class="text-danger">{{$errors->first('name')}}</small>
                    </div>

                    <div class="form-group">
                        <label>Expression</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon11"><i class="ti-ruler-alt-2"></i></span>
                            </div>
                            <input type="text" class="form-control" placeholder="Expression" name=expression id=expression value="{{ (isset($equation)) ? $equation->expression : old('expression')}}" required>

                        </div>
                        <small class="text-danger">{{$errors->first('expression')}}</small>

                        <h5 class="box-title">Variables</h5>
                        <small class="text-grey">Select Variables to add it to the expression</small>

                        <select multiple class="form-control" id="vars-select" onclick="appendToExp()">
                           @foreach ($variables as $var)
                               <option value='{{$var->name}}'>{{$var->name}}</option>
                           @endforeach
                        </select>

                    </div>

                    <div class="form-group">
                        <label>Mapping</label>
                        <div class=row>
                            <div class="col-12">
                                <div id="dynamicContainer">
                                    <?php $i=1 ?>
                                    @isset($equation)
                                    @foreach ($equation->maps as $map)
                                    <div class="removeclass{{$i}} row">
                                        <div class="col-4">
                                            <div class="input-group mb-3">
                                                <input id="from{{$i}}" type="number" step="0.01" class="form-control" placeholder="From" name=from[] value='{{$map->lower_limit}}' required>
                                            </div>
                                        </div>

                                        <div class="col-4">
                                            <div class="input-group mb-3">
                                                <input id="to{{$i}}" type="number" step="0.01" class="form-control" placeholder="To" value='{{$map->higher_limit}}' name=to[] required>
                                            </div>
                                        </div>

                                        <div class="col-4">
                                            <div class="input-group mb-3">
                                                <input id="value{{$i}}" type="number" step="1" class="form-control amount" placeholder="Value" value='{{$map->result}}' name=value[] required>
                                                <div class="input-group-append">
                                                    <button class="btn btn-danger" type="button" onclick="removeRange({{$i++}});"><i class="fa fa-minus"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                    @endisset
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" onclick="addRange()" class="btn btn-info mr-2">Add Mapping</button>

                    <button type="submit" class="btn btn-success mr-2">Submit</button>
                </form>
                @isset($equation)
                <hr>
                <h4 class="card-title">Delete Equation</h4>
                <div class="form-group">
                    <button type="button" onclick="confirmAndGoTo('{{$deleteURL}}', 'delete this Equation ?')" class="btn btn-danger mr-2">Delete Equation</button>
                </div>
                @endisset
            </div>
        </div>

    </div>
</div>
<script>
function appendToExp(){
    console.log("HEEEH")
    var selected = $('#vars-select').val()
    $('#expression').val(function() {
    return this.value + selected;
});
}

var room = {{$i}};

function addRange() {

    var objTo = document.getElementById('dynamicContainer')
    var divtest = document.createElement("div");
    divtest.setAttribute("class", "row removeclass" + room);
    var latestRange = document.getElementById('to' + (room-1))?.value ?? -1
    var concatString = "";

    concatString +=`<div class="col-4">
                        <div class="input-group mb-3">
                            <input id="from`+room+`" type="number" step="0.01" class="form-control" placeholder="From" name=from[] value='` + (latestRange!=-1 ? latestRange : '') + `' required>
                        </div>
                    </div>`

    concatString +=`<div class="col-4">
                        <div class="input-group mb-3">
                            <input id="to`+room+`" type="number" step="0.01" class="form-control" placeholder="To" name=to[] required>
                        </div>
                    </div>`

    concatString +=`<div class="col-4">
                        <div class="input-group mb-3">
                            <input id="value`+room+`" type="number" step="1" class="form-control amount" placeholder="Value" name=value[]  required>
                                <div class="input-group-append">\
                                    <button class="btn btn-danger" type="button" onclick="removeRange(`+room+`);"><i class="fa fa-minus"></i></button>
                                </div>
                        </div>
                    </div>`
    
    divtest.innerHTML = concatString;

    objTo.appendChild(divtest);
                            
    room++
}

function removeRange(rid) {
    $('.removeclass' + rid).remove();
}

</script>
@endsection