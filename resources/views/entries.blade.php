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
                                @foreach($equations as $eq)
                                <th>{{$eq->name}}</th>
                                @endforeach
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($entries as $entry)
                            <?php $total=0 ?>
                            <tr>
                                <td><a href={{url('entry/' . $entry->id)}} target="_blank">{{$entry->company_name}}</a></td>
                                @foreach($equations as $eq)
                                <td>{{ $entry->{$eq->safe_name}['value'] . " (".$entry->{$eq->safe_name}['map'] . ")" }}</td>
                                <?php 
                                    if(isset($entry->{$eq->safe_name}['map']) && is_numeric($entry->{$eq->safe_name}['map']))
                                    $total+=$entry->{$eq->safe_name}['map'];
                                ?>
                                @endforeach
                                <td>{{$total}}</td>
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