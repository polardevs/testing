<div class="box-body">
  <table id="example1" class="table table-striped table-bordered">
  <!-- table table-bordered table-striped -->
  <!-- table table-striped table-bordered dt-responsive nowrap -->
    <thead>
      <tr>
        @foreach($dataTables['colums'] as $colum)
          <th>{{ $colum }}</th>
        @endforeach
      </tr>
    </thead>
    <tbody>
      @foreach($dataTables['datas'] as $dataFields)
        <tr>
          @foreach($dataFields as $field)
            <td>{!! $field !!}</td>
          @endforeach
        </tr>
      @endforeach
    </tbody>
    <tfoot>
      <tr>
        @foreach($dataTables['colums'] as $colum)
          <th>{{ $colum }}</th>
        @endforeach
      </tr>
    </tfoot>
  </table>
</div>

@section('scripts')
  @parent
  <script type="text/javascript">
    $('#example1').DataTable({
      responsive: true
    });
  </script>
@endsection
