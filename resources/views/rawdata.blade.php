@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{$title}}</h4>
                <h6 class="card-subtitle">{{$subtitle}}</h6>
                <div class="table-responsive m-t-5">
                    <table id="entriesTable" class="table color-bordered-table table-striped full-color-table full-info-table hover-table" data-display-length='-1' data-order="[]">
                        <thead>
                            <tr>
                                <th>Company Name</th>
                                @foreach($variables as $var)
                                <th>{{$var->title}}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rawdata as $row)
                            <tr>
                                <td><a href={{url('entry/' . $row->id)}} target="_blank">{{$row->company_name}}</a></td>
                                @foreach($variables as $var)
                                <td>{{ $row->variables->find($var->id)?->pivot->value}}</td>
                                @endforeach

                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <script src="{{ asset('assets/node_modules/jquery/jquery-3.2.1.min.js') }}"></script>
        <script src="{{ asset('assets/node_modules/datatables/datatables.min.js') }}"></script>
        <script>
            $(function () {
                    $(function () {
        
                        var table = $('#entriesTable').DataTable({
                            "displayLength": 25,
                            dom: 'Bfrtip',
                            buttons: [
                                {
                                    extend: 'excel',
                                    title: 'Tawasoa',
                                    footer: true,
                                    className: 'btn-info'
                                }
                            ]
                        });
                    })
                })
        </script>
    </div>
</div>
@endsection